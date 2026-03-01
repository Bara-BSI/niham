<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\AssetHistory;
use Illuminate\Support\Facades\Auth;

class AssetObserver
{
    /**
     * Handle the Asset "created" event.
     */
    public function created(Asset $asset): void
    {
        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'changes' => $asset->getAttributes(),
        ]);
        $this->dispatchNotification($asset, 'created');
    }

    /**
     * Handle the Asset "updated" event.
     */
    public function updated(Asset $asset): void
    {
        if (!$asset->wasChanged()) return;

        $changes = $asset->getChanges();
        $original = array_intersect_key($asset->getOriginal(), $changes);

        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'original' => $original,
            'changes' => $changes,
        ]);
        $this->dispatchNotification($asset, 'updated');
    }

    /**
     * Handle the Asset "deleted" event.
     */
    public function deleted(Asset $asset): void
    {
        $this->dispatchNotification($asset, 'deleted');

        // ABORT: Do not attempt to log history for a permanently deleted model
        // because the ON DELETE CASCADE constraint will cause a SQL 1452 error.
        return;
    }

    /**
     * Handle the Asset "restored" event.
     */
    public function restored(Asset $asset): void
    {
        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'action' => 'restored',
            'changes' => $asset->getAttributes(),
        ]);
    }

    /**
     * Handle the Asset "force deleted" event.
     */
    public function forceDeleted(Asset $asset): void
    {
        // Do not log history during force deletes.
        // The asset row is permanently removed from the database, 
        // so inserting into asset_histories will trigger a 1452 Foreign Key Constraint Violation.
    }

    protected function dispatchNotification(Asset $asset, string $action): void
    {
        $users = \App\Models\User::where('property_id', $asset->property_id)
            ->where('is_super_admin', false)
            ->get();

        foreach ($users as $user) {
            if ($user->notify_all_properties) {
                // User wants notifications for ALL assets in the property
                $user->notify(new \App\Notifications\AssetChangedNotification($asset, $action));
            } elseif ($user->notify_department && $user->department_id === $asset->department_id) {
                // User only wants notifications for assets strictly in their department
                $user->notify(new \App\Notifications\AssetChangedNotification($asset, $action));
            }
        }
    }
}
