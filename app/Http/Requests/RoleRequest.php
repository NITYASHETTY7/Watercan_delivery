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

class RoleRequest extends FormRequest
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
                'role' => ['required',

                    'min:' .validation('common_name',1)['pattern']['minlength'],
                    'min:' .validation('common_name',1)['pattern']['maxlength'],
                    'regex:' .regex('text',1)['pattern'],

                ],

                'display_name' => ['required',

                    'min:' .validation('common_name',1)['pattern']['minlength'],
                    'max:' .validation('common_name',1)['pattern']['maxlength'],
                    'regex:' .regex('text',1)['pattern'],

                ],

                'description' => ['nullable',

                    'min:' .validation('common_description',1)['pattern']['minlength'],
                    'max:' .validation('common_description',1)['pattern']['maxlength'],
                    'regex:' .regex('name',1)['pattern'],

                ],

                'id' => 'required|integer'
            ];
            break;
                   case 'update':
                       $rules = [
                        'role' => [

                            'min:' .validation('common_name',1)['pattern']['minlength'],
                            'max:' .validation('common_name',1)['pattern']['maxlength'],
                            'regex:' .regex('text',1)['pattern'],

                        ],

                        'display_name' => [

                            'min:' .validation('common_description',1)['pattern']['minlength'],
                            'max:' .validation('common_description',1)['pattern']['maxlength'],
                            'regex:' .regex('text',1)['pattern'],

                        ],

                        'description' => [

                            'min:' .validation('common_description',1)['pattern']['minlength'],
                            'max:' .validation('common_description',1)['pattern']['maxlength'],
                            'regex:' .regex('name',1)['pattern'],

                        ],

                           'id' => 'required|integer'
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
                'role.required' => __('validation.required', ['attribute' => __('ui.role')]),
                'id.required' => __('validation.required', ['attribute' => __('ui.id')]),
            ];
            break;
        case 'update':
            $messages = [
                'role.required' => __('validation.required', ['attribute' => __('ui.role')]),
                'id.required' => __('validation.required', ['attribute' => __('ui.id')]),
            ];
            break;
        default:
            $messages = [];
            break;
        }
        return $messages;
    }
}
