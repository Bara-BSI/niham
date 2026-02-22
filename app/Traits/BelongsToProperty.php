<?php

namespace App\Traits;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;

/**
 * Auto-scopes queries by the authenticated user's property_id.
 *
 * - Super admin with an active property in session → filter by that property.
 * - Super admin without active property → show all.
 * - Normal user → always filter by their own property_id.
 * - Guest / CLI → no scope applied.
 */
trait BelongsToProperty
{
    public static function bootBelongsToProperty(): void
    {
        // ── Query scope ────────────────────────────────────────────
        static::addGlobalScope('property', function (Builder $builder) {
            if (app()->runningInConsole() && ! app()->runningUnitTests()) {
                return; // skip scope for artisan commands (seeder, tinker, etc.)
            }

            $user = auth()->user();
            if (! $user) {
                return;
            }

            if ($user->isSuperAdmin()) {
                // Super admin: scope only when a property is selected in session
                $activePropertyId = session('active_property_id');
                if ($activePropertyId) {
                    $builder->where((new static)->getTable().'.property_id', $activePropertyId);
                }
                // else: show all → no where clause
            } else {
                // Normal user: always scope to their property
                $builder->where((new static)->getTable().'.property_id', $user->property_id);
            }
        });

        // ── Auto-assign property_id on create ──────────────────────
        static::creating(function ($model) {
            if (! $model->property_id) {
                $user = auth()->user();
                if ($user) {
                    if ($user->isSuperAdmin()) {
                        $activePropertyId = session('active_property_id');
                        if ($activePropertyId) {
                            $model->property_id = $activePropertyId;
                        }
                    } else {
                        $model->property_id = $user->property_id;
                    }
                }
            }
        });
    }

    // Relationship
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
