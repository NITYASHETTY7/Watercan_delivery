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

use Laratrust\Models\LaratrustRole;
use App\Traits\LogsActivity;
use App\Traits\HasFormattedTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends LaratrustRole
{
    use LogsActivity;
    use HasFactory, HasFormattedTimestamps;
    public $guarded = [];
    protected $table = 'roles';

    public function getPrefix()
    {
        return "#ROLE" . str_replace('_1', '', '_' . (100000 + (int)$this->id));
    }
}
