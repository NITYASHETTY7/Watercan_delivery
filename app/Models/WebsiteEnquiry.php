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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;

class WebsiteEnquiry extends Model
{
    use HasFactory, HasFormattedTimestamps;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'website_enquiries';
    protected $guarded = ['id'];

    public const BULK_ACTIVATION = 1;
    public const STATUS_NEW = 0;
    public const STATUS_CONTACTED = 1;
    public const STATUS_CLOSED = 2;


    public const STATUSES = [
        self::STATUS_NEW => ['label' => 'New', 'color' => 'info', 'icon' => 'fa fa-envelope'],
        self::STATUS_CONTACTED => ['label' => 'Contacted', 'color' => 'success', 'icon' => 'fa fa-phone'],
        self::STATUS_CLOSED => ['label' => 'Closed', 'color' => 'danger', 'icon' => 'fa fa-check'],
    ];

    public function getPrefix()
    {
        return "#WENQ" . str_replace('_1', '', '_' . (100000 + $this->id));
    }
    protected function StatusParsed(): Attribute
    {
        return Attribute::make(
            get: fn() => (self::STATUSES[$this->status] ?? null) ? (object)self::STATUSES[$this->status] : null,
        );
    }
    public function fullPhone()
    {
        return "+" . $this->country_code . $this->phone;
    }
}
