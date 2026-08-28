<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;  

class ZonePincodeUser extends Model
{
    use HasFactory, HasFormattedTimestamps;
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#ZPUSR".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
