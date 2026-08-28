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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;

class SupportTicket extends Model implements HasMedia
{
    use LogsActivity;
    use SoftDeletes, InteractsWithMedia, HasFactory, HasFormattedTimestamps;
    protected $table = 'support_tickets';
    protected $guarded = ['id'];

    public const STATUS_UNDER_WORKING = 0;
    public const STATUS_RESOLVED = 1;

    public const PRIORITY_LOW = 0;
    public const PRIORITY_MEDIUM = 1;
    public const PRIORITY_HIGH = 2;

    public const SUBJECTS = [
        "General Support" => ['name' => 'General Support'],
        "Facing problems using their system" => ['name' => 'Facing problems using their system'],
    ];

    public const STATUSES = [
        self::STATUS_UNDER_WORKING => ['label' => 'Under Working', 'custom_color' => 'gray','color' => 'secondary',  'icon' => 'fa fa-cogs'],
        self::STATUS_RESOLVED => ['label' => 'Resolved', 'custom_color' => 'green','color' => 'success',  'icon' => 'fa fa-check-circle'],
    ];


    public const PRIORITIES = [
        self::PRIORITY_LOW => ['label' => 'Low', 'custom_color' => 'green', 'color' => 'success'],
        self::PRIORITY_MEDIUM => ['label' => 'Medium', 'custom_color' => 'yellow', 'color' => 'warning'],
        self::PRIORITY_HIGH => ['label' => 'High', 'custom_color' => 'red', 'color' => 'danger']
    ];

    protected static function booted()
    {
        static::deleting(function ($supportTicket) {
            // Delete all related conversations (triggers deleting events on each Conversation)
            // $supportTicket->conversations->each->delete();
    
            // Clear associated media in the 'ticket_file' collection
            if ($supportTicket->hasMedia('ticket_file')) {
                $supportTicket->clearMediaCollection('ticket_file');
            }
        });
    }

    public function getPrefix()
    {
        return "#SUTK" . str_replace('_1', '', '_' . (100000 + $this->id));
    }
    protected function SupportTicketStatusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn($value) =>  (object)self::STATUSES[$this->status],
        );
    }
    protected function statusParsed(): Attribute
    {
        return  Attribute::make(
            get: fn($value) =>  (object)self::STATUSES[$this->status],
        );
    }
    protected function priorityParsed(): Attribute
    {
        return  Attribute::make(
            get: fn($value) =>  @(object)self::PRIORITIES[$this->priority],
        );
    }
    // public function conversations()
    // {
    //     return $this->hasMany(Conversation::class, 'type_id')->orderBy('id', 'asc');
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function medias()
    {
        return $this->hasMany(Media::class, 'modal_id', 'id')->where('model_type', 'App\Models\SupportTicket');
    }
    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
}
