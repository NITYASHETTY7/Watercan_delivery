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

class PermissionRequest extends FormRequest
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
            'permission' => [
              
                'min:' . validation('permission_name', 1)['pattern']['minlength'] ?? 1,
                'max:' . validation('permission_name', 1)['pattern']['maxlength'] ?? 255,
                'regex:' . regex('name', 1)['pattern'],

            ],
            'group' => [
                'min:' . validation('group_name', 1)['pattern']['minlength'] ?? 1,
                'max:' . validation('group_name', 1)['pattern']['maxlength'] ?? 255,
                'regex:' .regex('name',1)['pattern'],
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
            'permission.required' => __('validation.required', ['attribute' => __('ui.permission')]),
            ];
            break;
        default:
            $messages = [];
            break;
        }
        return $messages;
    }
}
