<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;

class Zone extends Model    
{
    use HasFactory, HasFormattedTimestamps;
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#Z".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function zonePincodes() {
        return $this->hasMany(ZonePincode::class, 'zone_id');
    }
}
