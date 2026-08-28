<?php

/**
 * Class Review
 *
 * @category zStarter
 *
 * @ref zCURD
 * @author  Book My Water <info@watercane.come>
 * @license https://watercane-dev.dze-labs.in Book My Water
 * @version <zStarter: 1.1.0>
 * @link    https://watercane-dev.dze-labs.in
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;




class Review extends Model
{
    use HasFactory, HasFormattedTimestamps;
    use LogsActivity;
    use SoftDeletes;
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#RVW" . str_replace('_1', '', '_' . (100000 + $this->id));
    }

    public function  user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'type_id');
    }
}
