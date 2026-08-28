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


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupportTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        switch ($this->request_with) {
        case 'create':
            $rules = [
                'subject' => [

                    'min:' .validation('common_name')['pattern']['minlength'],
                    'max:1' .validation('common_name')['pattern']['maxlength'],
                    'regex:'. regex('text', 1)['pattern'],
                ],


                'message' => [

                    'min:' .validation('common_description',1)['pattern']['minlength'],
                    'max:' .validation('common_description',1)['pattern']['maxlength'],
                    'regex:' .regex('text',1)['pattern'],

                ],

                'user_id' => 'required',
                'ticket_type_id' => 'required',
            ];
            break;
        case 'update':
            $rules = [
                'subject' => [

                    'min:' .validation('common_name')['pattern']['minlength'],
                    'max:1' .validation('common_name')['pattern']['maxlength'],
                    'regex:'. regex('text', 1)['pattern'],
                ],


                'message' => [

                    'min:' .validation('common_description',1)['pattern']['minlength'],
                    'max:' .validation('common_description',1)['pattern']['maxlength'],
                    'regex:' .regex('text',1)['pattern'],

                ],
                'user_id' => 'required',
                'ticket_type_id' => 'required',
            ];
            break;
        case 'reply':
            $rules = [
            'reply' => 'required|regex:/^[a-zA-Z]+.*$/',
            ];
            break;
        case 'media':
            $rules = [
            'file_name' => 'required',
            ];
            break;
        default:
            $rules = [];
            break;
        }
        return $rules;
    }
    public function messages()
    {
        switch ($this->request_with) {
        case 'create':
            $messages = [
            'subject.required' => __('validation.required', ['attribute' => __('ui.subject')]),
            'message.required' => __('validation.required', ['attribute' => __('ui.message')]),
            'user_id.required' => __('validation.required', ['attribute' => __('ui.user_name')]),
            'ticket_type_id.required' => __('validation.required', ['attribute' => __('ui.category')]),
            ];
            break;
        case 'reply':
            $messages = [
            'reply.required' => __('validation.required', ['attribute' => __('ui.reply')]),
            ];
            break;
        case 'media':
            $messages = [
            'file_name.required' => __('validation.required', ['attribute' => __('ui.file')]),
            ];
            break;
        default:
            $messages = [];
            break;
        }
        return $messages;
    }
}
