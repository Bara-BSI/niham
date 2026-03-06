<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Services\AssetImportService;
use App\Services\EntityCodeGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AssetImportController extends Controller
{
    private AssetImportService $importService;

    public function __construct(AssetImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * AJAX: Parse uploaded XLSX/CSV file using native heuristic engine.
     * Returns JSON with cache key for the review page redirect.
     */
    public function parse(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,txt|max:20480',
        ]);

        $file = $request->file('import_file');
        $realPath = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());

        // Normalize extension
        if (! in_array($extension, ['csv', 'xlsx'])) {
            $extension = 'csv'; // Fallback for .txt
        }

        try {
            // ═══════════════════════════════════════════
            // NATIVE HEURISTIC PARSE (zero external calls)
            // ═══════════════════════════════════════════
            $parsedData = $this->importService->parseFile($realPath, $extension);

            // ═══════════════════════════════════════════
            // GARBAGE COLLECTION — strict file deletion
            // ═══════════════════════════════════════════
            clearstatcache();
            if (file_exists($realPath)) {
                @unlink($realPath);
            }
            unset($file);

            // Store in cache for the review page
            $cacheKey = 'import_review_'.auth()->id();
            Cache::put($cacheKey, $parsedData, now()->addMinutes(30));

            $rowCount = count($parsedData);

            return response()->json([
                'success' => true,
                'cache_key' => $cacheKey,
                'row_count' => $rowCount,
                'redirect' => route('assets.import-rapid-add'),
            ]);

        } catch (\Exception $e) {
            Log::error('Import Parse Failure: '.$e->getMessage());

            // Backup garbage collection
            if (isset($realPath) && file_exists($realPath)) {
                @unlink($realPath);
            }

            return response()->json([
                'success' => false,
                'message' => __('assets.import_parse_error', ['message' => $e->getMessage()]),
            ], 422);
        }
    }

    /**
     * Rapid Add interception: cross-reference category/department hints
     * against the database. If missing, proceed to rapid-add workflow.
     */
    public function rapidAdd(Request $request)
    {
        $cacheKey = 'import_review_'.auth()->id();
        $data = Cache::get($cacheKey);

        if ($data === null) {
            return redirect()->route('assets.index')
                ->with('warning', __('assets.import_parse_error', ['message' => 'Import session expired or not found.']));
        }

        // 1. Extract unique hints from the cached data
        $categoryHints = collect($data)
            ->pluck('_category_hint')
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $departmentHints = collect($data)
            ->pluck('_department_hint')
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // 2. Query existing names case-insensitively
        // Using LOWER() for PostgreSQL compatibility to match hints effectively
        $existingCategories = Category::whereIn(\DB::raw('LOWER(name)'), array_map('strtolower', $categoryHints))
            ->pluck('name')
            ->toArray();

        $existingDepartments = Department::whereIn(\DB::raw('LOWER(name)'), array_map('strtolower', $departmentHints))
            ->pluck('name')
            ->toArray();

        // 3. Find missing items (case-insensitive difference)
        $missingCategories = array_values(array_udiff($categoryHints, $existingCategories, 'strcasecmp'));
        $missingDepartments = array_values(array_udiff($departmentHints, $existingDepartments, 'strcasecmp'));

        $warnings = [];
        $hasMissingCategories = !empty($missingCategories);
        $hasMissingDepartments = !empty($missingDepartments);

        // Check category auth
        if ($hasMissingCategories && !auth()->user()->can('create', Category::class)) {
            $warnings[] = __('assets.import_unauthorized_category_add', ['count' => count($missingCategories)]);
            // Strip missing category hints
            foreach ($data as &$row) {
                $hint = trim($row['_category_hint']);
                $isMissing = collect($missingCategories)->contains(fn($c) => strcasecmp($c, $hint) === 0);
                if ($isMissing) {
                    $row['_category_hint'] = '';
                }
            }
            $missingCategories = [];
        }

        // Check department auth
        if ($hasMissingDepartments && !auth()->user()->can('create', Department::class)) {
            $warnings[] = __('assets.import_unauthorized_department_add', ['count' => count($missingDepartments)]);
            // Strip missing department hints
            foreach ($data as &$row) {
                $hint = trim($row['_department_hint']);
                $isMissing = collect($missingDepartments)->contains(fn($c) => strcasecmp($c, $hint) === 0);
                if ($isMissing) {
                    $row['_department_hint'] = '';
                }
            }
            $missingDepartments = [];
        }

        // --- MAP EXISTING ENTITIES IMMEDIATELY ---
        // Fetch full collections of existing models matching names case-insensitively
        $existingCatModels = Category::whereIn(\DB::raw('LOWER(name)'), array_map('strtolower', $categoryHints))->get();
        $existingDeptModels = Department::whereIn(\DB::raw('LOWER(name)'), array_map('strtolower', $departmentHints))->get();

        foreach ($data as &$row) {
            $cHint = trim($row['_category_hint']);
            $dHint = trim($row['_department_hint']);

            if (!empty($cHint)) {
                $matched = $existingCatModels->first(fn($c) => strcasecmp($c->name, $cHint) === 0);
                if ($matched) {
                    $row['category_id'] = $matched->id;
                }
            }

            if (!empty($dHint)) {
                $matched = $existingDeptModels->first(fn($d) => strcasecmp($d->name, $dHint) === 0);
                if ($matched) {
                    $row['department_id'] = $matched->id;
                }
            }
        }
        // -----------------------------------------

        // Re-cache data with mapped existing IDs (and potentially stripped unauthorized hints)
        Cache::put($cacheKey, $data, now()->addMinutes(30));

        // If none are missing (or were stripped), bypass and go to standard review
        if (empty($missingCategories) && empty($missingDepartments)) {
            $redirect = redirect()->route('assets.import-review');
            if (!empty($warnings)) {
                $redirect->with('warning', implode(' ', $warnings));
            }
            return $redirect;
        }

        // Return view (for rapid add display, which will be implemented in Batch 3)
        if (view()->exists('assets.import-rapid-add')) {
            return view('assets.import-rapid-add', compact('missingCategories', 'missingDepartments'));
        }

        // Demo output for Batch 1/2 Verification
        return response()->json([
            'status' => 'intercepted_for_rapid_add',
            'missing_categories' => $missingCategories,
            'missing_departments' => $missingDepartments,
            'warnings' => $warnings,
        ]);
    }

    /**
     * Process Rapid Add submissions.
     */
    public function storeRapidAdd(Request $request, EntityCodeGeneratorService $codeGen)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'string|max:255',
            'departments' => 'nullable|array',
            'departments.*' => 'string|max:255',
        ]);

        $cacheKey = 'import_review_'.auth()->id();
        $data = Cache::get($cacheKey);

        if ($data === null) {
            return redirect()->route('assets.index')
                ->with('warning', __('assets.import_parse_error', ['message' => 'Import session expired or not found.']));
        }

        $propertyId = auth()->user()->isSuperAdmin() ? session('active_property_id') : auth()->user()->property_id;

        $createdCategories = [];
        $createdDepartments = [];

        // Create Categories
        if ($request->filled('categories') && auth()->user()->can('create', Category::class)) {
            foreach ($request->categories as $name) {
                $code = $codeGen->generateUniqueCode($name, Category::class, $propertyId);
                $category = Category::create([
                    'name' => $name,
                    'code' => $code,
                    'property_id' => $propertyId,
                ]);
                $createdCategories[$name] = $category->id;
            }
        }

        // Create Departments
        if ($request->filled('departments') && auth()->user()->can('create', Department::class)) {
            foreach ($request->departments as $name) {
                $code = $codeGen->generateUniqueCode($name, Department::class, $propertyId);
                $department = Department::create([
                    'name' => $name,
                    'code' => $code,
                    'property_id' => $propertyId,
                ]);
                $createdDepartments[$name] = $department->id;
            }
        }

        // Map the IDs back
        foreach ($data as &$row) {
            $catHint = trim($row['_category_hint']);
            $deptHint = trim($row['_department_hint']);

            $matchedCat = collect($createdCategories)->first(function ($id, $name) use ($catHint) {
                return strcasecmp($name, $catHint) === 0;
            });
            if ($matchedCat) {
                $row['category_id'] = $matchedCat;
                $row['_category_hint'] = '';
            }

            $matchedDept = collect($createdDepartments)->first(function ($id, $name) use ($deptHint) {
                return strcasecmp($name, $deptHint) === 0;
            });
            if ($matchedDept) {
                $row['department_id'] = $matchedDept;
                $row['_department_hint'] = '';
            }
        }

        // Re-cache updated data
        Cache::put($cacheKey, $data, now()->addMinutes(30));

        return redirect()->route('assets.import-review');
    }

    /**
     * Review page: hydrate the bulk form from cached parsed data.
     */
    public function review(Request $request)
    {
        $cacheKey = 'import_review_'.auth()->id();
        $data = Cache::get($cacheKey);
        $warning = null;

        if ($data === null) {
            return redirect()->route('assets.index')
                ->with('warning', __('assets.import_parse_error', ['message' => 'Import session expired or not found.']));
        }

        if (empty($data)) {
            $data = array_fill(0, 5, [
                'tag' => '', 'name' => '', 'category_id' => '', 'department_id' => '',
                'status' => 'in_service', 'model' => '', 'serial_number' => '', 'purchase_date' => '',
            ]);
            $warning = __('assets.no_data_found');
        }

        $categories = \App\Models\Category::all();
        $departments = \App\Models\Department::all();

        return view('assets.import-review', compact('data', 'categories', 'departments', 'warning'));
    }

    /**
     * Manual bulk entry page: 5 blank rows.
     */
    public function bulkManual(Request $request)
    {
        $data = array_fill(0, 5, [
            'tag' => '', 'name' => '', 'category_id' => '', 'department_id' => '',
            'status' => 'in_service', 'model' => '', 'serial_number' => '', 'purchase_date' => '',
        ]);

        $categories = \App\Models\Category::all();
        $departments = \App\Models\Department::all();
        $warning = null;

        return view('assets.import-review', compact('data', 'categories', 'departments', 'warning'));
    }

    /**
     * Final DB insertion with strict Tenancy enforcement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'assets' => 'required|array',
            'assets.*.name' => 'required|string|max:255',
            'assets.*.tag' => 'required|string|max:64',
            'assets.*.category_id' => 'required|exists:categories,id',
            'assets.*.department_id' => 'required|exists:departments,id',
            'assets.*.status' => 'required|in:in_service,out_of_service,disposed',
            'assets.*.model' => 'nullable|string|max:255',
            'assets.*.serial_number' => 'nullable|string|max:255',
            'assets.*.purchase_date' => 'nullable|date',
        ]);

        $assetsData = $request->input('assets');

        \DB::beginTransaction();
        try {
            foreach ($assetsData as $item) {
                \App\Models\Asset::create([
                    'name' => $item['name'],
                    'tag' => $item['tag'],
                    'category_id' => $item['category_id'],
                    'department_id' => $item['department_id'],
                    'status' => $item['status'],
                    'serial_number' => $item['serial_number'] ?? null,
                    'purchase_date' => $item['purchase_date'] ?? null,
                    'remarks' => ! empty($item['model']) ? 'Imported. Model: '.$item['model'] : 'Imported.',
                    'editor' => auth()->id(),
                ]);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Bulk Insert Failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Failed to save assets. '.$e->getMessage()]);
        }

        // Clean cache after successful import
        Cache::forget('import_review_'.auth()->id());

        return redirect()->route('assets.index')->with('ok', __('assets.import_success'));
    }
}
