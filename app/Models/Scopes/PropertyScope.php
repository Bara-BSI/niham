<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PropertyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Super admins can see all properties
            if ($user->isSuperAdmin()) {
                // If they have an active property selected, filter by it
                $activePropertyId = session('active_property_id');
                if ($activePropertyId) {
                    $builder->where($model->getTable() . '.property_id', $activePropertyId);
                }
                return;
            }

            // Normal users MUST have a property assigned. 
            // If they are somehow NULL, force an impossible query to prevent data leakage.
            if ($user->property_id) {
                $builder->where($model->getTable() . '.property_id', $user->property_id);
            } else {
                $builder->whereNull($model->getTable() . '.property_id')->whereNotNull($model->getTable() . '.property_id'); // Returns 0 records safely
            }
        }
    }
}
