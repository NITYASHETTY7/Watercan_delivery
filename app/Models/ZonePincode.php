<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;  

class ZonePincode extends Model
{
    use HasFactory, HasFormattedTimestamps;
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#ZP".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function zonePincodeUsers() {
        return $this->hasMany(ZonePincodeUser::class, 'zone_pincode_id');
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function zone() {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
