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
class Setting extends Model
{
    use LogsActivity;
    use HasFactory,HasFormattedTimestamps;
    use SoftDeletes;
    protected $table = 'settings';
    protected $guarded = ['id'];

   
    public const AUTHENTICATION_ENABLE = 0;
    public const AUTHENTICATION_DISABLE = 1;

    public const AUTHENTICATIONS = [
        self::AUTHENTICATION_ENABLE => ['label' =>'Enable'],
        self::AUTHENTICATION_DISABLE => ['label' =>'Disable'],
    ];

    public const DATE_FORMATS = [
        ['label'=>'2023-04-19 17:30:00', 'format'=>'Y-m-d H:i:s'],
        ['label'=>'2023-04-19 5:30PM', 'format'=>'Y-m-d g:iA'],
    ];

    public const MAIL_DRIVER_SMTP        = 0;
    public const MAIL_DRIVER_SENDMAIL    = 1;
    public const MAIL_DRIVER_MAILGUN     = 2;
    public const MAIL_DRIVER_SPARKPOST   = 3;
    public const MAIL_DRIVER_AMAZON_SES  = 4;
    public const MAIL_DRIVER_BREVO       = 5;

    public const MAIL_DRIVER = [
        self::MAIL_DRIVER_SMTP => [
            'label' => 'SMTP',
            'color' => 'secondary',
            'icon'  => 'fas fa-envelope'
        ],
        self::MAIL_DRIVER_SENDMAIL => [
            'label' => 'Sendmail',
            'color' => 'info',
            'icon'  => 'fas fa-paper-plane'
        ],
        self::MAIL_DRIVER_MAILGUN => [
            'label' => 'Mailgun',
            'color' => 'primary',
            'icon'  => 'fas fa-bullseye'
        ],
        self::MAIL_DRIVER_SPARKPOST => [
            'label' => 'SparkPost',
            'color' => 'danger',
            'icon'  => 'fas fa-fire'
        ],
        self::MAIL_DRIVER_AMAZON_SES => [
            'label' => 'Amazon SES',
            'color' => 'success',
            'icon'  => 'fab fa-aws'
        ],
        self::MAIL_DRIVER_BREVO => [
            'label' => 'Brevo',
            'color' => 'purple',
            'icon'  => 'fas fa-bolt'
        ],
    ];

    public const MAIL_PORT_587 = 587;
    public const MAIL_PORT_465 = 465;
    public const MAIL_PORT = [
        "587" => ['label' => '587', 'color' => 'warning'],
        "465" => ['label' => '465', 'color' => 'warning'],
    ];
    public const MAIL_ENCRYPTION_TLS = 'tls';
    public const MAIL_ENCRYPTION_SSL = 'ssl';

    public const MAIL_ENCRYPTION = [
        'tls' => ['label' => 'TLS', 'color' => 'warning'],
        'ssl' => ['label' => 'SSL', 'color' => 'warning'],
    ];

    public const NO_OF_DECIMAL_1234 = '0';
    public const NO_OF_DECIMAL_123_4 = '1'; // Replaced '.' with '_'
    public const NO_OF_DECIMAL_12_34 = '2'; // Replaced '.' with '_'
    public const NO_OF_DECIMAL_1_234 = '3'; // Replaced '.' with '_'

    public const NO_OF_DECIMAL = [
        '0' => ['label' => '1234', 'color' => 'warning'],
        '1' => ['label' => '123.4', 'color' => 'warning'],
        '2' => ['label' => '12.34', 'color' => 'warning'],
        '3' => ['label' => '1.234', 'color' => 'warning'],
    ];
    public const DOCUMENT_TYPE_PAN_CARD = '0';
    public const DOCUMENT_TYPE_AADHARCARD = '1'; // Replaced '.' with '_'

    public const DOCUMENT_TYPE = [
        '0' => ['label' => '1234', 'color' => 'warning'],
        '1' => ['label' => '123.4', 'color' => 'warning'],
    ];
    // Defining the boot method
    protected static function boot()
    {
        parent::boot();

        // Handle both creates and updates with the saved event.
        static::saved(function ($setting) {
            // This clears the application cache whenever a Setting is created or updated.
           \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        });

        // Setup event listener for the deleted event.
        static::deleted(function ($setting) {
            // This clears the application cache when a Setting is deleted.
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        });
    }
    public function fullPhone()
    {
        return "+" . $this->country_code.''.$this->phone;
    }

    public function getPrefix()
    {
        return "#SET".str_replace('_1', '', '_'.(100000 +$this->id));
    }
}
