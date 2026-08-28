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

class UserRequest extends FormRequest
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
                        'min:' . validation('common_name', 1)['pattern']['minlength'],
                        'max:' . validation('common_name', 1)['pattern']['maxlength'],
                        'regex:' . regex('name', 1)['pattern'],
                    ],

                    'last_name' => [
                        'min:' . validation('common_name', 1)['pattern']['minlength'],
                        'max:' . validation('common_name', 1)['pattern']['maxlength'],
                        'regex:' . regex('name', 1)['pattern'],
                    ],

                    'email' => [
                        'min:' . validation('common_email', 1)['pattern']['minlength'],
                        'max:' . validation('common_email', 1)['pattern']['maxlength'],
                        'regex:' . regex('email', 1)['pattern'],
                        'unique:users,email',
                    ],
                    'phone' => [
                        validation('phone_number', 1)['pattern']['mandatory'],
                        'min:' . validation('phone_number', 1)['pattern']['minlength'],
                        'max:' . validation('phone_number', 1)['pattern']['maxlength'],
                        'regex:' . regex('phone_number', 1)['pattern'],
                        'unique:users,phone',
                    ],




                    'dob' => [
                        'nullable',

                        'regex:' . regex('dob', 1)['pattern'],
                    ],

                    'password' => [
                        'sometimes',
                        'min:' . validation('common_password', 1)['pattern']['minlength'],
                        'max:' . validation('common_password', 1)['pattern']['maxlength'],
                        'regex:' . regex('password', 1)['pattern'],
                    ],

                    'role'     => 'required',
                ];
                if ($this->account_type == 2) {
                    $rules['gst_number'] = [
                        'required',
                        'min:' . validation('gst_number', 1)['pattern']['minlength'],
                        'max:' . validation('gst_number', 1)['pattern']['maxlength'],
                        'regex:' . regex('gst_number', 1)['pattern'],
                    ];

                    $rules['company_name'] = ['required', 'string'];
                }

                break;
            case 'update':
                $rules = [
                    'first_name' => [

                        'min:' . validation('common_name', 1)['pattern']['minlength'],
                        'max:' . validation('common_name', 1)['pattern']['maxlength'],
                        'regex:' . regex('name', 1)['pattern'],
                    ],

                    'last_name' => [

                        'min:' . validation('common_name', 1)['pattern']['minlength'],
                        'max:' . validation('common_name', 1)['pattern']['maxlength'],
                        'regex:' . regex('name', 1)['pattern'],
                    ],

                    'email' => [

                        'min:' . validation('common_email', 1)['pattern']['minlength'],
                        'max:' . validation('common_email', 1)['pattern']['maxlength'],
                        'regex:' . regex('email', 1)['pattern'],
                        'unique:users,email,' . $this->id,
                    ],


                    'phone' => [
                        validation('phone_number', 1)['pattern']['mandatory'],
                        'min:' . validation('phone_number', 1)['pattern']['minlength'],
                        'max:' . validation('phone_number', 1)['pattern']['maxlength'],
                        'regex:' . regex('phone_number', 1)['pattern'],
                        'unique:users,phone,' . $this->id,
                    ],



                    'dob' => [

                        'regex:' . regex('dob', 1)['pattern'],
                    ],

                    'is_verified' => 'nullable',
                ];
                if ($this->account_type == 2) {
                    $rules['company_name'] = ['required', 'string'];

                    $rules['gst_number'] = [
                        'required',
                        'min:' . validation('gst_number', 1)['pattern']['minlength'],
                        'max:' . validation('gst_number', 1)['pattern']['maxlength'],
                        'regex:' . regex('gst_number', 1)['pattern'],
                    ];
                }

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
                    // 'email.nullable' => __('validation.required', ['attribute' => __('ui.email')]),
                    'password.required' =>  __('validation.required', ['attribute' => __('ui.password')]),
                    'role.required'     => __('validation.required', ['attribute' => __('ui.role')]),

                ];
                break;
            case 'update':
                $messages = [
                    'first_name.required' => __('validation.required', ['attribute' => __('ui.first_name')]),
                    'last_name.required' => __('validation.required', ['attribute' => __('ui.last_name')]),
                    // 'email.nullable' => __('validation.required', ['attribute' => __('ui.email')]),


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
        $clean = [];

        foreach ($this->all() as $key => $value) {

            // Clean string values
            if (!is_array($value) && is_string($value)) {

                // Remove spaces from all inputs (especially phone)
                $value = str_replace(' ', '', $value);

                // Decrypt if needed
                if (strpos($value, 'zDecrypt-') === 0) {
                    $value = base64_decode(substr($value, strlen('zDecrypt-')));
                }
            }

            $clean[$key] = $value;
        }

        // Apply cleaned values
        $this->merge($clean);
    }
}
