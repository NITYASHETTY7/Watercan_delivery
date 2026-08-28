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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;

class SeoTag extends Model
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'seo_tags';
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#SEOT" . str_replace('_1', '', '_' . (100000 + $this->id));
    }
}
