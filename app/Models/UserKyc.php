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
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;


class UserKyc extends Model implements HasMedia
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    use InteractsWithMedia;
    use HasFormattedTimestamps;
    use LogsActivity;

    protected $table = 'user_kycs';
    protected $guarded = ['id'];
    protected $casts = [
        'details' => 'array'
    ];

    public const STATUS_UNDER_APPROVAL = 0;
    public const STATUS_VERIFIED = 1;
    public const STATUS_REJECTED = 2;

    public const STATUSES = [
        self::STATUS_UNDER_APPROVAL => ['label' => 'Under Approval', 'color' => 'warning', 'icon' => 'fas fa-eye'],
        self::STATUS_VERIFIED => ['label' => 'Verified', 'color' => 'success', 'icon' => 'fas fa-check-circle'],
        self::STATUS_REJECTED => ['label' => 'Rejected', 'color' => 'danger', 'icon' => 'fas fa-times-circle'],
    ];

    public function getPrefix()
    {
        return "#URKYC".str_replace('_1', '', '_'.(100000 + $this->id));
    }

    public function getFrontImageAttribute($value)
    {
        $frontImage = $value !== null ? asset($value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($frontImage);
        if (\Str::contains(request()->url(), '/api')) {
            return asset($frontImage);
        }
        return $frontImage;
    }
    public function getbackImageAttribute($value)
    {
        $backImage = $value !== null ? asset($value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($backImage);
        if (\Str::contains(request()->url(), '/api')) {
            return asset($backImage);
        }
        return $backImage;
    }
    public function getFaceWithDocAttribute($value)
    {
        $FaceWithDoc = $value !== null ? asset($value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($FaceWithDoc);
        if (\Str::contains(request()->url(), '/api')) {
            return asset($FaceWithDoc);
        }
        return $FaceWithDoc;
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
