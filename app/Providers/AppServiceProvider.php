<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $activeProperty = null;

            if (Auth::check()) {
                $user = Auth::user();
                if ($user->isSuperAdmin()) {
                    $activeId = session('active_property_id');
                    if ($activeId) {
                        $activeProperty = Property::find($activeId);
                    }
                } else {
                    $activeProperty = $user->property;
                }
            }

            $view->with('activeProperty', $activeProperty);
        });
    }
}
