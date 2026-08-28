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

class RegisterRequest extends FormRequest
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
                    'first_name' => [                       
                        'min:' .validation('common_name',1)['pattern']['minlength'],
                        'max:' .validation('common_name',1)['pattern']['maxlength'],
                        'regex:'.regex('name',1)['pattern'],
                    ],

                    'last_name' => [
                        'min:' .validation('common_name',1)['pattern']['minlength'],
                        'max:' .validation('common_name',1)['pattern']['maxlength'],
                        'regex:'.regex('name',1)['pattern'],
                    ],



                    'email' => [
                        'min:' .validation('common_email',1)['pattern']['minlength'],
                        'max:' .validation('common_email',1)['pattern']['maxlength'],
                        'regex:'.regex('email',1)['pattern'],
                    ],

                    'dob' => [
                      
                        'regex:'.regex('dob',1)['pattern'],
                    ],

                    'password' => [
                        'sometimes',
                        'min:' .validation('common_password',1)['pattern']['minlength'],
                        'max:' .validation('common_password',1)['pattern']['maxlength'],
                        'regex:'.regex('password',1)['pattern'],
                    ],

                    'gender' => 'nullable',
                ];
                break;
            case 'update':
                $rules = [
                    'first_name' => [
                      
                        'min:' .validation('common_name',1)['pattern']['minlength'],
                        'max:' .validation('common_name',1)['pattern']['maxlength'],
                        'regex:'.regex('name',1)['pattern'],
                    ],

                    'last_name' => [
                       
                        'min:' .validation('common_name',1)['pattern']['minlength'],
                        'max:' .validation('common_name',1)['pattern']['maxlength'],
                        'regex:'.regex('name',1)['pattern'],
                    ],
                    
                    'email' => [
                        
                        'min:' .validation('common_email',1)['pattern']['minlength'],
                        'max:' .validation('common_email',1)['pattern']['maxlength'],
                        'regex:'.regex('email',1)['pattern'],
                    ],

                    'dob' => [
                       
                        'regex:'.regex('dob',1)['pattern'],
                    ],

                    'gender' => 'nullable',
                    'is_verified' => 'nullable',
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
                'first_name.required' => __('validation.required', ['attribute' => __('ui.first_name')]),
                'last_name.required' => __('validation.required', ['attribute' => __('ui.last_name')]),
                'email.required' => __('validation.required', ['attribute' => __('ui.email')]),
                'password.required' =>  __('validation.required', ['attribute' => __('ui.password')]),
                'role.required'     => __('validation.required', ['attribute' => __('ui.role')]),
                'phone.required'   => __('validation.required', ['attribute' => __('ui.phone_number')]),
            ];
            break;
        case 'update':
            $messages = [
                'first_name.required' => __('validation.required', ['attribute' => __('ui.first_name')]),
                'last_name.required' => __('validation.required', ['attribute' => __('ui.last_name')]),
                'email.required' => __('validation.required', ['attribute' => __('ui.email')]),
                'phone.required'   => __('validation.required', ['attribute' => __('ui.phone_number')]),

            ];
            break;
        default:
            $messages = [];
        break;
        }
        return $messages;
    }
    protected function prepareForValidation()
    {
        foreach ($this->all() as $key => $value) {
            if ((!is_array($value)) && strpos($value, 'zDecrypt-') === 0) {
                $this->merge([
                    $key => base64_decode(substr($value, strlen('zDecrypt-'))),
                ]);
            }
        }
    }
}
