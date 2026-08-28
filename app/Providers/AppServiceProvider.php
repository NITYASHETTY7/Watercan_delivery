<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend(
            'numeric_phone_length', function ($attribute, $value, $parameters, $validator) {
                $numericValue = preg_replace('/[^0-9]/', '', $value); // Remove non-numeric characters
                $length = strlen($numericValue);
                return $length >= $parameters[0] && $length <= $parameters[1];
            }
        );
        Validator::replacer(
            'numeric_phone_length', function ($message, $attribute, $rule, $parameters) {
                return str_replace([':min', ':max'], $parameters, $message);
            }
        );
        Blade::component('components.badge', 'badge');
          $role = 'admin';
          View::share('root_directory', "panel/$role/");
          View::share('root_directory_path', "panel.$role");
    }
}
