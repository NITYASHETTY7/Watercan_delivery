<?php
/**
 * Class ModelSession
 *
 * @category ZStarter
 *
 * @ref     zCURD
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
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class ModelSession extends Model implements HasMedia
{
    use HasFactory,HasFormattedTimestamps;
    use InteractsWithMedia;
    use LogsActivity;

    protected $guarded = ['id'];
    protected $keyType = 'string';
    protected $table = 'sessions';

    protected $casts = [
        'meta' => 'array',
        ];
    public function getPrefix()
    {
        return "#MSES".str_replace('_1', '', '_'.(100000 +$this->id));
    }
    public function user()
    {
        return  $this->belongsTo(User::class, 'user_id');
    }
}
