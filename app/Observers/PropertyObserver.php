<?php

namespace App\Observers;

use App\Models\Property;

class PropertyObserver
{
    /**
     * Handle the Property "created" event.
     */
    public function created(Property $property): void
    {
        $this->dispatchNotification($property, 'created');
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        $this->dispatchNotification($property, 'updated', $property->getChanges());
    }

    /**
     * Handle the Property "deleted" event.
     */
    public function deleted(Property $property): void
    {
        $this->dispatchNotification($property, 'deleted');
    }

    /**
     * Handle the Property "restored" event.
     */
    public function restored(Property $property): void
    {
        $this->dispatchNotification($property, 'restored');
    }

    /**
     * Handle the Property "force deleted" event.
     */
    public function forceDeleted(Property $property): void
    {
        $this->dispatchNotification($property, 'force deleted');
    }

    protected function dispatchNotification(Property $property, string $action, array $changes = []): void
    {
        $superadmins = \App\Models\User::where('is_super_admin', true)->get();
        foreach ($superadmins as $admin) {
            $admin->notify(new \App\Notifications\PropertyChangedNotification($property, $action, $changes));
        }
    }
}
