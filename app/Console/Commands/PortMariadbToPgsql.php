<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * One-time data porting command: MariaDB → PostgreSQL.
 *
 * Reads from the 'mariadb' connection and writes to the default 'pgsql' connection.
 * Tables are ported in strict FK-dependency order. Existing PG data is truncated
 * (with CASCADE) before insertion to ensure idempotency.
 *
 * Usage:
 *   php artisan app:port-mariadb-to-pgsql
 *   php artisan app:port-mariadb-to-pgsql --fresh   # also runs migrate:fresh first
 */
class PortMariadbToPgsql extends Command
{
    protected $signature = 'app:port-mariadb-to-pgsql
                            {--fresh : Run migrate:fresh on pgsql before porting}';

    protected $description = 'Port all data from MariaDB to PostgreSQL (one-time migration)';

    /**
     * Tables in strict FK-dependency order.
     * System tables (migrations, cache, sessions, jobs, etc.) are excluded —
     * they are framework-managed and should not be ported.
     */
    private array $tables = [
        'properties',
        'roles',
        'departments',
        'users',
        'categories',
        'assets',
        'attachments',
        'asset_histories',
        'notifications',
    ];

    /**
     * Columns that need UUID generation if they are stored as CHAR(36)
     * in MariaDB but need native uuid in PostgreSQL. The HasUuids trait
     * already populates these, so we just pass them through.
     */
    private array $uuidColumns = ['uuid'];

    public function handle(): int
    {
        // ── Pre-flight checks ──────────────────────────────────────
        $this->info('🔍 Pre-flight: testing MariaDB source connection...');

        try {
            DB::connection('mariadb')->getPdo();
            $this->info('   ✅ MariaDB connection OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Cannot connect to MariaDB: ' . $e->getMessage());
            $this->newLine();
            $this->warn('   Ensure MARIADB_* variables are set in your .env file.');
            return self::FAILURE;
        }

        try {
            DB::connection('pgsql')->getPdo();
            $this->info('   ✅ PostgreSQL connection OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Cannot connect to PostgreSQL: ' . $e->getMessage());
            return self::FAILURE;
        }

        // ── Optional: fresh migration ──────────────────────────────
        if ($this->option('fresh')) {
            $this->info('');
            $this->info('🗄️  Running migrate:fresh on PostgreSQL...');
            $this->call('migrate:fresh', ['--force' => true]);
        }

        // ── Porting ────────────────────────────────────────────────
        $this->newLine();
        $this->info('🚀 Starting data port: MariaDB → PostgreSQL');
        $this->info('   Tables: ' . implode(', ', $this->tables));
        $this->newLine();

        $totalRows = 0;

        DB::connection('pgsql')->statement('SET session_replication_role = replica;');

        try {
            DB::connection('pgsql')->beginTransaction();

            foreach ($this->tables as $table) {
                $totalRows += $this->portTable($table);
            }

            DB::connection('pgsql')->commit();
        } catch (\Exception $e) {
            DB::connection('pgsql')->rollBack();
            $this->error('');
            $this->error('❌ Porting FAILED — transaction rolled back.');
            $this->error('   ' . $e->getMessage());
            return self::FAILURE;
        } finally {
            DB::connection('pgsql')->statement('SET session_replication_role = DEFAULT;');
        }

        // ── Reset sequences ────────────────────────────────────────
        $this->newLine();
        $this->info('🔄 Resetting PostgreSQL sequences...');
        foreach ($this->tables as $table) {
            $this->resetSequence($table);
        }

        $this->newLine();
        $this->info("✅ Data port complete. {$totalRows} total rows ported across " . count($this->tables) . " tables.");

        return self::SUCCESS;
    }

    /**
     * Port a single table from MariaDB to PostgreSQL.
     */
    private function portTable(string $table): int
    {
        $source = DB::connection('mariadb')->table($table);
        $count = $source->count();

        if ($count === 0) {
            $this->warn("   ⏭️  {$table}: 0 rows (skipped)");
            return 0;
        }

        // Truncate target (CASCADE to handle FK dependencies from child tables)
        DB::connection('pgsql')->statement("TRUNCATE TABLE \"{$table}\" CASCADE");

        $bar = $this->output->createProgressBar($count);
        $bar->setFormat("   📋 {$table}: %current%/%max% [%bar%] %percent%%");

        // Chunk to avoid memory issues on large tables
        $inserted = 0;
        $source->orderBy('id')->chunk(500, function ($rows) use ($table, $bar, &$inserted) {
            $batch = [];

            foreach ($rows as $row) {
                $record = (array) $row;

                // Ensure boolean columns are cast properly (MariaDB tinyint → PG boolean)
                $record = $this->castBooleans($table, $record);

                $batch[] = $record;
                $inserted++;
            }

            DB::connection('pgsql')->table($table)->insert($batch);
            $bar->advance(count($batch));
        });

        $bar->finish();
        $this->newLine();

        return $inserted;
    }

    /**
     * Cast MariaDB tinyint(1) values to proper PHP booleans for PostgreSQL.
     */
    private function castBooleans(string $table, array $record): array
    {
        $booleanMap = [
            'departments' => ['is_executive_oversight'],
            'users' => [
                'is_super_admin',
                'notify_department',
                'notify_all_properties',
                'notify_email',
            ],
        ];

        if (isset($booleanMap[$table])) {
            foreach ($booleanMap[$table] as $col) {
                if (array_key_exists($col, $record)) {
                    $record[$col] = (bool) $record[$col];
                }
            }
        }

        return $record;
    }

    /**
     * Reset the auto-increment sequence for a table so new inserts get the correct next ID.
     */
    private function resetSequence(string $table): void
    {
        // Only reset if the table has an 'id' serial column
        $hasId = DB::connection('pgsql')
            ->select("SELECT column_name FROM information_schema.columns WHERE table_name = ? AND column_name = 'id'", [$table]);

        if (empty($hasId)) {
            return;
        }

        $sequenceName = "{$table}_id_seq";

        try {
            $maxId = DB::connection('pgsql')->table($table)->max('id') ?? 0;
            DB::connection('pgsql')->statement(
                "SELECT setval('{$sequenceName}', ?, true)",
                [max($maxId, 1)]
            );
            $this->line("   🔗 {$table}_id_seq → {$maxId}");
        } catch (\Exception $e) {
            // Sequence may not exist (e.g. notifications uses uuid PK)
            $this->line("   ⏭️  {$sequenceName}: skipped (no serial sequence)");
        }
    }
}
