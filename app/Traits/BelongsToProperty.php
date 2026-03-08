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
        static::addGlobalScope(new \App\Models\Scopes\PropertyScope);

        // ── Auto-assign property_id on create ──────────────────────
        static::creating(function ($model) {
            if (! $model->property_id && auth()->check()) {
                $user = auth()->user();
                if ($user->isSuperAdmin()) {
                    $activePropertyId = session('active_property_id');
                    if ($activePropertyId) {
                        $model->property_id = $activePropertyId;
                    }
                } else {
                    $model->property_id = $user->property_id;
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
