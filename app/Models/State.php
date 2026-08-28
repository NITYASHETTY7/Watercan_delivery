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

// use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;
class State extends Model
{
    use LogsActivity;
    use HasFactory,HasFormattedTimestamps;
    // use SoftDeletes;
    protected $table = 'states';
    protected $guarded = ['id'];
    public function getPrefix()
    {
        return "#LSTA".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
