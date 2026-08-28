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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use DB;

class User extends Authenticatable
{
    use LogsActivity;
    use SoftDeletes;
    use LaratrustUserTrait, HasFormattedTimestamps;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $guarded = ['id'];
    public const SETTING_PAYLOAD_STRUCTURE = [
        'sms_notification_alert' => 1,
        'email_notification_alert' => 1,
        'onsite_notification_alert' => 1,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
        'preferences' => 'array',
        'setting_payload' => 'array',
        'vehicle_details' => 'array',
        'geo_coordinates' => 'array',
        'business_payload' => 'array',
    ];
    public const BULK_ACTIVATION = 1;
    public const PREFIX = "USR";

    /**
     * This method is called when the model is being initialized.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->sso_token = $user->sso_token ?? Str::uuid()->toString();
        });
    }


    protected static function booted()
    {
        static::deleting(function ($user) {
            // Delete pivot table entries for roles assigned to the user
            DB::table('role_user')->where('user_id', $user->id)->delete();

            // Delete pivot table entries for permissions assigned to the user
            DB::table('permission_user')->where('user_id', $user->id)->delete();
        });
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
    public function getAvatarAttribute($value)
    {
        // $avatar = !is_null($value) && $value !== '' ? asset('storage/backend/users/' . $value) :
        //     'https://ui-avatars.com/api/?name=' . $this->first_name . '&background=3b82f6&color=ffffff&v=19B5FE';
            
            $avatar = !is_null($value) && $value !== '' ? asset('storage/backend/users/' . $value) :
            'https://ui-avatars.com/api/?name=' . $this->first_name . '&background=EEEEEE&color=000000&v=19B5FE';

        if (\Str::contains(request()->url(), '/api/vi')) {
            return asset($avatar);
        }
        return $avatar;
    }

    public const ACCOUNT_TYPE_INDIVIDUAL = 1;
    public const ACCOUNT_TYPE_BUSINESS = 2;

    public const ACCOUNT_TYPES = [
        self::ACCOUNT_TYPE_INDIVIDUAL => ['label' => 'Individual', 'color' => 'primary'],
        self::ACCOUNT_TYPE_BUSINESS => ['label' => 'Business', 'color' => 'success'],
    ];

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUSES = [
        self::STATUS_INACTIVE => ['label' => 'Inactive', 'color' => 'danger'],
        self::STATUS_ACTIVE => ['label' => 'Active', 'color' => 'success'],
    ];

    public const USER_STATUSES_ACTIVE = 1;
    public const USER_STATUSES_INACTIVE = 0;
    public const USER_STATUSES = [
        self::USER_STATUSES_ACTIVE => ['label' => 'Active', 'color' => 'success'],
        self::USER_STATUSES_INACTIVE => ['label' => 'Inactive', 'color' => 'danger'],
    ];

    public const BANK_NAMES_AXIS_BANK = 0;
    public const BANK_NAMES_ICICI_BANK = 1;
    public const BANK_NAMES_SBI_BANK = 2;
    public const BANK_NAMES_UNION_BANK = 3;
    public const BANK_NAMES_HDFC_BANK = 4;
    public const BANK_NAMES_PNB_BANK = 5;
    public const BANK_NAMES_BOB_BANK = 6;
    public const BANK_NAMES_CANARA_BANK = 7;
    public const BANK_NAMES_BOI_BANK = 8;
    public const BANK_NAMES_INDUSIND_BANK = 9;
    public const BANK_NAMES_KOTAK_MAHINDRA_BANK = 10;
    public const BANK_NAMES_IDBI_BANK = 11;
    public const BANK_NAMES_CENTERAL_BANK_OF_INDIA = 12;
    public const BANK_NAMES_YES_BANK = 13;
    public const BANK_NAMES_INDIAN_BANK = 14;
    public const BANK_NAMES_FEDERAL_BANK = 15;
    public const BANK_NAMES_PUNJAB_SIND_BANK = 16;
    public const BANK_NAMES = [
        self::BANK_NAMES_AXIS_BANK => ['label' => 'AXIS Bank'],
        self::BANK_NAMES_ICICI_BANK => ['label' => 'ICICI Bank'],
        self::BANK_NAMES_SBI_BANK => ['label' => 'SBI Bank'],
        self::BANK_NAMES_UNION_BANK => ['label' => 'UNION Bank'],
        self::BANK_NAMES_HDFC_BANK => ['label' => 'HDFC Bank'],
        self::BANK_NAMES_PNB_BANK => ['label' => 'PNB '],
        self::BANK_NAMES_BOB_BANK => ['label' => 'Bank of Baroda (BOB)'],
        self::BANK_NAMES_CANARA_BANK => ['label' => 'Canara Bank'],
        self::BANK_NAMES_BOI_BANK => ['label' => 'Bank of India(BOI)'],
        self::BANK_NAMES_INDUSIND_BANK => ['label' => 'IndusInd Bank'],
        self::BANK_NAMES_KOTAK_MAHINDRA_BANK => ['label' => 'Kotak Mahindra Bank'],
        self::BANK_NAMES_IDBI_BANK => ['label' => 'IDBI Bank'],
        self::BANK_NAMES_CENTERAL_BANK_OF_INDIA => ['label' => 'Central Bank of India'],
        self::BANK_NAMES_YES_BANK => ['label' => 'Yes Bank'],
        self::BANK_NAMES_INDIAN_BANK => ['label' => 'Indian  Bank'],
        self::BANK_NAMES_FEDERAL_BANK => ['label' => 'Federal Bank'],
        self::BANK_NAMES_PUNJAB_SIND_BANK => ['label' => 'Punjab & Sind Bank'],
    ];

    public const ACTIVITY_LOGIN = 1;
    public const ACTIVITY_LOGOUT = 2;
    public const ACTIVITY_VIEW = 3;
    public const ACTIVITY_CREATE = 4;
    public const ACTIVITY_UPDATE = 5;
    public const ACTIVITY_DELETE = 6;
    public const ACTIVITY_STATUS_CHANGE = 7;
    public const ACTIVITES = [
        self::ACTIVITY_LOGIN => ['label' => 'Login', 'color' => 'danger'],
        self::ACTIVITY_LOGOUT => ['label' => 'Logout', 'color' => 'success'],
        self::ACTIVITY_VIEW => ['label' => 'View', 'color' => 'success'],
        self::ACTIVITY_CREATE => ['label' => 'Create', 'color' => 'success'],
        self::ACTIVITY_UPDATE => ['label' => 'Update', 'color' => 'success'],
        self::ACTIVITY_DELETE => ['label' => 'Delete', 'color' => 'success'],
        self::ACTIVITY_STATUS_CHANGE => ['label' => 'Status Change', 'color' => 'success'],
    ];

    protected $appends = [
        'full_name',
        'name'
    ];

    public const ADMIN_MEMBER_DESIGNATION_NAME_SALES = 1;
    public const ADMIN_MEMBER_DESIGNATION_NAME_CUSTOMER_SUPPORT = 2;
    public const ADMIN_MEMBER_DESIGNATION_NAME_DATA_ENTRY = 3;

    public const ADMIN_MEMBER_DESIGNATION_NAME = [
        self::ADMIN_MEMBER_DESIGNATION_NAME_SALES => ['label' => 'Sales', 'color' => 'success'],
        self::ADMIN_MEMBER_DESIGNATION_NAME_CUSTOMER_SUPPORT => ['label' => 'Customer Support', 'color' => 'success'],
        self::ADMIN_MEMBER_DESIGNATION_NAME_DATA_ENTRY => ['label' => 'Data Entry', 'color' => 'success'],
    ];

    public const ADMIN_MEMBER_PERMISSION = [
        User::ADMIN_MEMBER_DESIGNATION_NAME_SALES =>
        ['name' => 'Sales', 'permissions' => [
            "order_view_up",
            "order_add_up",
            "order_edit_up",
            "order_delete_up",
            "payout_view_up",
            "payout_create_up",
            "payout_edit_up",
            "payout_delete_up",
            "item_view_up",
            "item_create_up",
            "item_edit_up",
            "item_delete_up",
            "item_bulk_upload_up",
        ]],

        User::ADMIN_MEMBER_DESIGNATION_NAME_CUSTOMER_SUPPORT => ['name' => 'Customer Support', 'permissions' => [
            "ticket_view_up",
            "ticket_create_up",
            "ticket_edit_up",
            "ticket_show_up",
            "ticket_delete_up",
        ]],

        User::ADMIN_MEMBER_DESIGNATION_NAME_DATA_ENTRY => ['name' => 'Data Entry', 'permissions' => [
            "enquiry_view_up",
            "enquiry_create_up",
            "enquiry_show_up",
            "enquiry_edit_up",
            "enquiry_delete_up",
            "enquiry_bulk_upload_up",
            "newsletter_view_up",
            "newsletter_create_up",
            "newsletter_show_up",
            "newsletter_edit_up",
            "newsletter_delete_up",
            "newsletter_bulk_upload_up",
            "lead_view_up",
            "lead_create_up",
            "lead_show_up",
            "lead_edit_up",
            "lead_delete_up",
            "lead_bulk_upload_up",
            "category_type_view_up",
            "category_type_create_up",
            "category_type_edit_up",
            "category_type_sync_up",
            "category_view_up",
            "category_create_up",
            "category_edit_up",
            "slider_type_view_up",
            "slider_type_create_up",
            "slider_type_edit_up",
            "slider_type_sync_up",
            "slider_view_up",
            "slider_create_up",
            "slider_edit_up",
            "slider_delete_up",
            "slider_bulk_upload_up",
            "paragraph_contents_view_up",
            "paragraph_content_create_up",
            "paragraph_content_edit_up",
            "paragraph_content_delete_up",
            "paragraph_content_bulk_upload_up",
            "faq_view_up",
            "faq_create_up",
            "faq_edit_up",
            "faq_delete_up",
            "faq_bulk_upload_up",
            "location_view_up",
            "location_create_up",
            "location_edit_up",
            "state_view_up",
            "state_add_up",
            "state_edit_up",
            "city_view_up",
            "city_add_up",
            "city_edit_up",
            "seo_tag_view_up",
            "seo_tag_create_up",
            "seo_tag_edit_up",
            "seo_tag_delete_up",
            "page_view_up",
            "page_create_up",
            "page_edit_up",
            "mail_templates_view_up",
            "mail_templates_create_up",
            "mail_templates_show_up",
            "mail_templates_edit_up",
            "mail_templates_delete_up",
            "resource_view_up",
            "resource_create_up",
            "resource_edit_up",
            "resource_delete_up",
            "blog_view_up",
            "blog_create_up",
            "blog_edit_up",
            "blog_show_up",
            "blog_delete_up",
        ]],
    ];

    public const LANGUAGE_ENGLISH = 'en';
    public const LANGUAGE_HINDI = 'hi';
    public const LANGUAGE_FRENCH = 'fr';

    public const LANGUAGE = [
        'en' => ['label' => 'English', 'language' => 'en'],
        'hi' => ['label' => 'Hindi', 'language' => 'hi'],
        'fr' => ['label' => 'French', 'language' => 'fr']
    ];

    protected function statusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn($value) =>  (object)self::STATUSES[$this->status],
        );
    }
    public function ekyStatus()
    {
        return $this->belongsTo(UserKyc::class, 'status');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function userOrderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
    // public function getRoleNameAttribute()
    // {
    //     if (!empty($this->roles)) {
    //         return $this->roles[0]->display_name;
    //     } else {
    //         return "No Role";
    //     }
    // }

    public function getRoleNameAttribute()
    {
        $role = $this->roles->first();
        return $role ? $role->display_name : 'No Role';
    }

    public function getFullNameAttribute()
    {
        return ucwords(trim($this->first_name) . ' ' . trim($this->last_name));
    }
    public function getNameAttribute()
    {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    // public function getPrefix()
    // {
    //     if ($this->roles[0]->name == 'admin') {
    //         return "#UAD" . str_replace('_1', '', '_' . (100000 + $this->id));
    //     } elseif ($this->roles[0]->name == 'user') {
    //         return "#UUR" . str_replace('_1', '', '_' . (100000 + $this->id));
    //     } else {
    //         return "#DVR" . str_replace('_1', '', '_' . (100000 + $this->id));
    //     }
    // }

    public function getPrefix()
    {
        $role = $this->roles->first();

        if (!$role) {
            return "#USR" . str_replace('_1', '', '_' . (100000 + $this->id));
        }

        if ($role->name === 'admin') {
            return "#UAD" . str_replace('_1', '', '_' . (100000 + $this->id));
        } elseif ($role->name === 'user') {
            return "#UUR" . str_replace('_1', '', '_' . (100000 + $this->id));
        } else {
            return "#DVR" . str_replace('_1', '', '_' . (100000 + $this->id));
        }
    }
    

    public function scopeWhereRoleIsNot($query, $role = '', $team = null)
    {
        return $query->whereHas(
            'roles',
            function ($roleQuery) use ($role, $team) {
                $roleQuery->whereNotIn('name', $role);
                if (!is_null($team)) {
                    $roleQuery->whereNotIn('team_id', $team->id);
                }
            }
        );
    }
    /**
     * Ecrypt the user's google_2fa secret.
     *
     * @param  string $value
     * @return string
     */
    public function setGoogle2faSecretAttribute($value)
    {
        $this->attributes['google2fa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string $value
     * @return string
     */
    public function getGoogle2faSecretAttribute($value)
    {
        if ($value == null) {
            return null;
        }
        return decrypt($value);
    }

    public function fullPhone()
    {
        return "+" . $this->country_code . '' . $this->phone;
    }

    public function zonePincodeUsers()
    {
        return $this->hasMany(ZonePincodeUser::class, 'user_id');
    }

    public function kyc()
    {
        return $this->hasOne(UserKyc::class, 'user_id');
    }
}
