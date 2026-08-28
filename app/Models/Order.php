<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFormattedTimestamps;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Order extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia ,HasFormattedTimestamps;
    protected $guarded = ['id'];
    protected $casts = ['schedule_value' => 'array'];

    public const STATUS_PENDING = 1;
    public const STATUS_ASSIGNED = 2;
    public const STATUS_INROUTE = 3;
    public const STATUS_DELIVERED = 4;
    public const STATUS_CANCELLED = 5;
    public const STATUS_CANCELLED_BY_ADMIN = 6;

    public const STATUSES = [
        self::STATUS_PENDING => ['bg_color' => "bg-yellow-500" ,'order_heading' => "Pending Confirmation" , 'label' => 'Pending', 'color' => 'yellow', 'boot_color' => 'warning','icon' => 'fas fa-hourglass-start'],
        self::STATUS_ASSIGNED => ['bg_color' => "bg-blue-500" ,'order_heading' => "Driver Assigned" , 'label' => 'Assigned', 'color' => 'blue', 'boot_color' => 'secondary','icon' => 'fas fa-box-open'],
        self::STATUS_INROUTE => ['bg_color' => "bg-orange-400" ,'order_heading' => "Out for Delivery" , 'label' => 'InRoute', 'color' => 'orange', 'boot_color' => 'primary','icon' => 'fas fa-truck'],
        self::STATUS_DELIVERED => ['bg_color' => "bg-green-500" ,'order_heading' => "Delivered" , 'label' => 'Delivered', 'color' => 'green', 'boot_color' => 'success','icon' => 'fas fa-check-circle'],
        self::STATUS_CANCELLED => ['bg_color' => "bg-red-500" ,'order_heading' => "Cancelled" , 'label' => 'Cancelled', 'color' => 'red', 'boot_color' => 'danger','icon' => 'fas fa-times-circle'],
        self::STATUS_CANCELLED_BY_ADMIN => ['bg_color' => "bg-red-500" ,'order_heading' => "Cancelled" , 'label' => 'Cancelled', 'color' => 'red', 'boot_color' => 'danger','icon' => 'fas fa-times-circle'],
    ];

    public const PAYMENT_STATUS_UNPAID = 1;
    public const PAYMENT_STATUS_PAID = 2;

    public const PAYMENT_STATUSES = [
        self::PAYMENT_STATUS_UNPAID => ['label' => 'Unpaid', 'color' => 'danger'],
        self::PAYMENT_STATUS_PAID => ['label' => 'Paid', 'color' => 'success'],
    ];

    public const TYPE_EXPRESS = 1;
    public const TYPE_SUBSCRIPTION = 2;

    public const TYPES = [
        self::TYPE_EXPRESS => ['label' => 'Express', 'color' => 'danger'],
        self::TYPE_SUBSCRIPTION => ['label' => 'Subscription', 'color' => 'success'],
    ];

    public const SCHEDULE_TYPE_DAILY = 1;
    public const SCHEDULE_TYPE_WEEKLY = 2;
    public const SCHEDULE_TYPE_MONTHLY = 3;
    public const SCHEDULE_TYPES = [
        self::SCHEDULE_TYPE_DAILY => ['label' => 'Daily', 'color' => 'danger'],
        self::SCHEDULE_TYPE_WEEKLY => ['label' => 'Weekly', 'color' => 'success'],
        self::SCHEDULE_TYPE_MONTHLY => ['label' => 'Monthly', 'color' => 'success'],
    ];

    public function getPrefix()
    {
        $prefix = $this->type == self::TYPE_SUBSCRIPTION ? '#SUB' : '#ORD';
        return $prefix . str_replace('_1', '', '_' . (100000 + $this->id));
    }

    public function branch() {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function zone() {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function zonePincode() {
        return $this->belongsTo(ZonePincode::class, 'zone_pincode_id');
    }

    // public function product() {
    //     return $this->belongsTo(Product::class, 'product_id');
    // }

    public function assignTo() {
        return $this->belongsTo(User::class, 'assign_to');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class, 'order_id');
    }


    public function address() {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }


    protected function statusParsed(): Attribute
    {
        return Attribute::make(
            get: fn($value) =>  (object)self::STATUSES[$this->status],
        );
    }

    public function latestStatusUpdateUserLog() {
        return $this->hasOne(UserLog::class, 'activity_id')->where('activity', 'Order Status Updated')->latest();
    }
}
