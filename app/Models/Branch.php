<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;

class Branch extends Model
{
    use HasFactory, HasFormattedTimestamps;
    protected $guarded = ['id'];

    public function getPrefix()
    {
        return "#B".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function zones() {
        return $this->hasMany(Zone::class, 'branch_id');
    }
}
