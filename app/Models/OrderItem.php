<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://www.defenzelite.com>
 */


namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;
class OrderItem extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'order_items';
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#ORITM".sprintf("%06d", $this->id);
    }

    public function order()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class , 'item_id');
    }


}
