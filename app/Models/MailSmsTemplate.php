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
use App\Traits\HasFormattedTimestamps;
use App\Traits\LogsActivity;

class MailSmsTemplate extends Model
{
    use HasFactory,SoftDeletes;
    use HasFormattedTimestamps;
    use LogsActivity;

    protected $table = 'mail_sms_templates';
    protected $guarded = ['id'];
    protected $casts = [
        'variables' => 'array'
    ];

    public const TYPE_MAIL = 1;
    public const TYPE_SMS =2;
    public const TYPE_WHATSAPP = 3;
    public const TYPE_PROMPT = 4;

    public const TYPES = [
        self::TYPE_MAIL => ['label' => 'Mail','name' =>'Mail (Rich Text editor)'],
        self::TYPE_SMS => ['label' => 'SMS','name' =>'SMS (Plain Text editor)'],
        self::TYPE_WHATSAPP => ['label' => 'Whatsapp','name' =>'Whatsapp (Plain Texteditor)'],
        self::TYPE_PROMPT => ['label' => 'Prompt','name' =>'Prompt (Plain Texteditor)'],
    ];
    public function getPrefix()
    {
        if($this->type == 1){
            return "#TMAIL" . str_replace('_1', '', '_' . (100000 + $this->id));
        }elseif($this->type == 2){
            return "#TSMS" . str_replace('_1', '', '_' . (100000 + $this->id));
        }elseif($this->type == 3){
            return "#TWTSP" . str_replace('_1', '', '_' . (100000 + $this->id));
        }else{
            return "#TPRMT" . str_replace('_1', '', '_' . (100000 + $this->id));
        }
    }
}
