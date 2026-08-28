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

class UserAddressRequest extends FormRequest
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
                    'phone' => [
                        'min:' . validation('common_phone_number', 1)['pattern']['minlength'],
                        'regex:' . regex('tel_no', 1)['pattern'],
                    ],

                    'address_1' => [
                        'min:' . validation('common_address', 1)['pattern']['minlength'],
                        'regex:' . regex('address', 1)['pattern'],
                    ],  

                    'pincode_id' => [
                        'min:' . validation('common_code', 1)['pattern']['minlength'],
                        'max:' . validation('common_code', 1)['pattern']['maxlength'],
                        'regex:' . regex('pin_code', 1)['pattern'],
                    ],
                    'name' => [
                        'regex:' . regex('name', 1)['pattern'],
                    ],

                ];
                break;
            case 'update':
                $rules = [
                    'phone' => [
                        'required',
                        'min:' . validation('common_phone_number', 1)['pattern']['minlength'],
                        'max:' . validation('common_phone_number', 1)['pattern']['maxlength'],
                        'regex:/' . regex('tel_no', 1)['pattern'] . '/',
                    ],

                    'address_1' => [
                        'min:' . validation('common_address', 1)['pattern']['minlength'],
                        'regex:' . regex('address', 1)['pattern'],
                    ],

                    'pincode_id' => [
                        'min:' . validation('common_code', 1)['pattern']['minlength'],
                        'max:' . validation('common_code', 1)['pattern']['maxlength'],
                        'regex:' . regex('pin_code', 1)['pattern'],
                    ],
                    'name' => [
                        'required',
                        'regex:/' . regex('name', 1)['pattern'] . '/',
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
                    'user_id.required' => __('validation.required', ['attribute' => __('ui.user_id')]),
                    'type.required' => __('validation.required', ['attribute' => __('ui.type')]),
                    'name.required' => __('validation.required', ['attribute' => __('ui.name')]),
                    'name.alpha' =>  __('validation.aplha', ['attribute' => __('ui.name')]),
                    'phone.required' => __('validation.required', ['attribute' => __('ui.phone_number')]),
                    'phone.regex' =>  __('validation.regex', ['attribute' => __('ui.phone_number')]),
                    'address_1.required' => __('validation.required', ['attribute' => __('ui.address')]),
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
