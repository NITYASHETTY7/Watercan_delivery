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

class ProfileRequest extends FormRequest
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
        case 'profile':
            $rules = [
                'first_name' => [                       
                    'min:' .validation('common_name',1)['pattern']['minlength'],
                    'max:' .validation('common_name',1)['pattern']['maxlength'],
                    'regex:'.regex('name',1)['pattern'],
                ],

                'email' => [
                    'min:' .validation('common_email',1)['pattern']['minlength'],
                    'max:' .validation('common_email',1)['pattern']['maxlength'],
                    'regex:'.regex('email',1)['pattern'],
                ],

                'phone' => [
                    'required',
                    'min:' .validation('phone_number',1)['pattern']['minlength'],
                    'max:' .validation('phone_number',1)['pattern']['maxlength'],
                    'regex:'.regex('tel_no',1)['pattern'],
                ],
            ];
            break;
        case 'password':
            $rules = [
                'password' => [
                    'sometimes',
                    'min:' .validation('common_password',1)['pattern']['minlength'],
                    'max:' .validation('common_password',1)['pattern']['maxlength'],
                    'regex:'.regex('password',1)['pattern'],
                ],
            ];
            break;
        case 'profile_img':
            $rules = [
                'avatar' => 'image|nullable|mimes:jpg,png,jpeg,gif,svg|max:2048',
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
        return [
            'first_name.required' => __('validation.required', ['attribute' => __('ui.first_name')]),
            'email.required' => __('validation.required', ['attribute' => __('ui.email')]),
        ];
    }
}
