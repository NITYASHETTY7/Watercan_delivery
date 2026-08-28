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
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    use InteractsWithMedia;
    use LogsActivity;

    protected $table = 'blogs';
    protected $guarded = [];
    protected $casts = ['meta'=>'array'];
    protected $appends = [
        'blog_banner_image'
    ];

    public const TYPES_BLOG = 1;
    public const TYPES_CASE_STUDY =2;
    public const TYPES_CAREER = 3;

    public const TYPES = [
        self::TYPES_BLOG => ['label' =>'Blogs','linkedCode'=>'BlogCategories'],
        self::TYPES_CASE_STUDY => ['label' =>'Case Studies','linkedCode'=>'CaseStudyCategories'],
        self::TYPES_CAREER => ['label' =>'Career','linkedCode'=>'CareerCategory'],
    ];

    public const STATUS_UNPUBLISHED = 1;
    public const STATUS_PUBLISHED = 2;
    public const STATUSES = [
        self::STATUS_UNPUBLISHED => ['name' =>'Unpublished'],
        self::STATUS_PUBLISHED => ['name' =>'Published'],
    ];
    
    public function getPrefix()
    {
        if($this->type == 1){
            return "#ABLG" . str_replace('_1', '', '_' . (100000 + $this->id));
        }elseif($this->type == 2){
            return "#ACDY" . str_replace('_1', '', '_' . (100000 + $this->id));
        }else{
            return "#ACAR" . str_replace('_1', '', '_' . (100000 + $this->id));
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function getBlogBannerImageAttribute($value)
    {
        $blogBanner = $value !== null ? ('storage/site/blog/' . $value) : asset('storage/backend/img/placeholder.jpg');
        if (\Str::contains(request()->url(), '/api')) {
            return asset($blogBanner);
        }
        return $blogBanner;
    }
}
