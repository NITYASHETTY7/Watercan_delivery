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

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;

class BlackList extends Model
{
    use HasFactory, HasFormattedTimestamps;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'blacklists'; // Correcting the table name
    protected $guarded = ['id']; // If you want to guard the ID from mass assignment

    // You can define any additional functionality related to the Blacklist model here
    public function getPrefix()
    {
        return "#BL" . str_replace('_1', '', '_' . (100000 + $this->id));
    }
}
