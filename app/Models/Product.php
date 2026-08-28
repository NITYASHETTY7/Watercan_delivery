<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;

class Product extends Model
{
    use HasFactory, HasFormattedTimestamps;
    protected $guarded = ['id'];

    public const STATUS_UNPUBLISHED = 0;
    public const STATUS_PUBLISHED = 1;

    public const IS_PUBLISHED = [
        self::STATUS_UNPUBLISHED => ['label' => 'Unpublished', 'color' => 'danger'],
        self::STATUS_PUBLISHED => ['label' => 'Published', 'color' => 'success'],
    ];

    

    public function getPrefix()
    {
        return "#PRO".str_replace('_1', '', '_'.(100000 +$this->id));
    }

}
