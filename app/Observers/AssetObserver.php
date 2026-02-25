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
    }

    /**
     * Handle the Asset "deleted" event.
     */
    public function deleted(Asset $asset): void
    {
        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'original' => $asset->getAttributes(),
        ]);
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
        AssetHistory::create([
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'action' => 'force_deleted',
            'original' => $asset->getAttributes(),
        ]);
    }
}
