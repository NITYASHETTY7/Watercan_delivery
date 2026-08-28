<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * This method is called after all other service providers have been registered,
     * meaning you have access to all other services that have been registered by the framework.
     *
     * @return void
     */
    public function boot()
    {
        // Registers a view composer that applies to all views.
        View::composer('*', function ($view) {
            $auth_user_master = Auth::user(); // Get the currently authenticated user.
            $data = [];

            // Handle authenticated user data
            if ($auth_user_master) {
                $cacheKey = 'user_permissions_roles_' . $auth_user_master->id;

                $data = \Cache::remember($cacheKey, now()->addHours(24), function () use ($auth_user_master) {
                    $master_permissions = $auth_user_master->roles->isNotEmpty()
                        ? $auth_user_master->roles[0]['permissions']->pluck('name')
                        : collect();
                    $authRole = AuthRole(); // Assume this is a function you've defined
                    $master_setting = Setting::select('id', 'key', 'value')->get();

                    return compact('master_permissions', 'authRole', 'auth_user_master', 'master_setting');
                });
            }

            // Handle the root directory logic
            $authRole = $data['authRole'] ?? (AuthRole() ?? 'guest'); // Use `guest` for unauthenticated users
            $role = strtolower($authRole);

            $master_root_directory = "site/v1/"; // Default directory for unauthenticated users
            if ($auth_user_master && $role) {
                $master_root_directory = "panel/$role/";
            }

            // Add all data to the view
            $view->with(array_merge($data, compact('master_root_directory')));
        });
    }
}
