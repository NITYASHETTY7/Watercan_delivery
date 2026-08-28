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
use App\Traits\HasFormattedTimestamps;

class Country extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use LogsActivity;

    protected $table = 'countries';
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#LCNT".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}
