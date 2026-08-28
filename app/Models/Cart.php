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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    use LogsActivity;

    protected $guarded = ['id'];

    protected $casts = [
        'details'=>'array',
    ];
    public function getPrefix()
    {
        return "#C".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function item()
    {
        return  $this->belongsTo(Item::class, 'type_id', 'id');
    }

    public function product()
    {
        return  $this->belongsTo(Product::class, 'type_id', 'id');
    }


    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
}
