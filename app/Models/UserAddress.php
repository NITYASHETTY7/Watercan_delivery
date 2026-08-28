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
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;
class UserAddress extends Model
{
    use LogsActivity;

    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    protected $table = 'user_addresses';
    protected $guarded = ['id'];
    protected $casts = [
        'details' => 'json',
        'geo_coordinates' => 'array',
    ];

    protected $defaultAddress = [
        "full_name"=>null,
        "number"=>null,
        "address"=>null,
        "address2"=>null,
        "country"=>null,
        "state"=>null,
        "city"=>null,
        "pincode"=>null,
        "type"=>null
    ];

    public function getPrefix()
    {
        return "#URADR".str_replace('_1', '', '_'.(100000 +$this->id));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getAvatarAttribute($value)
    {
        $avatar = !is_null($value) ? asset('storage/backend/users/'.$value) :
        'https://ui-avatars.com/api/?name='.$this->first_name.'&background=19B5FE&color=ffffff&v=19B5FE';
        // dd($avatar);
        if (\Str::contains(request()->url(), '/api/vi')) {
            return asset($avatar);
        }
        return $avatar;
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    

    public function getCountryIdAttribute()
    {
        return $this->details['country_id'] ?? null;
    }

    public function getStateIdAttribute()
    {
        return $this->details['state_id'] ?? null;
    }

    public function getCityIdAttribute()
    {
        return $this->details['city'] ?? null;
    }
    public function fullPhone()
    {
        return "+" . $this->country_code.''.$this->phone;
    }
}
