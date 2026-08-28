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


namespace App\Models;

use Laratrust\Models\LaratrustPermission;
use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends LaratrustPermission
{
    use HasFactory, HasFormattedTimestamps;
    public $guarded = [];
    protected $table = 'permissions';

    // Defining the boot method
    protected static function boot()
    {
        parent::boot();

        // Handle both creates and updates with the saved event.
        static::saved(function ($permission) {
            // This clears the application cache whenever a Setting is saved.
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        });

        // Setup event listener for the deleted event.
        static::deleted(function ($permission) {
            // This clears the application cache when a Setting is deleted.
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        });
    }

    public function getPrefix()
    {
        return "#PRMS" . str_replace('_1', '', '_' . (100000 + $this->id));
    }
}
