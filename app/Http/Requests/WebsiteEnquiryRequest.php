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
use App\Validation\CustomValidator;

class WebsiteEnquiryRequest extends FormRequest
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
                    'name' => [
                        'required',
                        'min:' . (validation('common_name')['pattern']['minlength'] ?? 1),
                        'max:255' . (validation('common_name')['pattern']['maxlength'] ?? 255),
                        'regex:' . regex('name', 1)['pattern'],
                    ],

                    'subject' => [
                        'required',
                        'min:' . validation('common_name')['pattern']['minlength'],
                        'max:255' . validation('common_name')['pattern']['maxlength'],
                        'regex:' . regex('text', 1)['pattern'],
                    ],

                    // 'description' => [
                    //     'min:' . (validation('common_description', 1)['pattern']['minlength'] ?? 5),
                    //     'max:255' . validation('common_description')['pattern']['maxlength'],
                    //     'regex:'. regex('text', 1)['pattern'],
                    // ],
                    'phone' => [
                        'required',
                        'min:' . (validation('phone_number', 1)['pattern']['minlength'] ?? 5),
                        'max:255' . validation('phone_number')['pattern']['maxlength'],
                        'regex:' . regex('tel_no', 1)['pattern'],
                    ],

                ];



                break;
            case 'update':
                $rules = [
                    'name' => [

                        'min:' . validation('common_name')['pattern']['minlength'],
                        'max:255' . validation('common_name')['pattern']['maxlength'],
                        'regex:' . regex('name', 1)['pattern'],
                    ],

                    'subject' => [

                        'min:' . validation('common_name')['pattern']['minlength'],
                        'max:1' . validation('common_name')['pattern']['maxlength'],
                        'regex:' . regex('text', 1)['pattern'],
                    ],

                    'description' => [

                        'min:' . (validation('common_description', 1)['pattern']['minlength'] ?? 5),
                        'max:' . (validation('common_description')['pattern']['maxlength'] ?? 1),
                        'regex:' . regex('text', 1)['pattern'],
                    ],

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
                    'subject.required' => __('validation.required', ['attribute' => 'Subject']),
                ];
                break;
            case 'update':
                $messages = [
                    'name.required' => __('validation.required', ['attribute' => __('ui.full_name')]),
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
