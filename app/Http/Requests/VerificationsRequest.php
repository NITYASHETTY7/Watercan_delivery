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
use App\Models\UserKyc;

use Illuminate\Foundation\Http\FormRequest;

class VerificationsRequest extends FormRequest
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
        $rules = [];
        switch ($this->request_with) {
            case 'create':
                $rules = [
                    'name' => [
                        'min:' . validation('common_name')['pattern']['minlength'],
                        'max:' . validation('common_name')['pattern']['maxlength'],
                        'regex:' . regex('name', 1)['pattern'],
                    ],
                    'document_number' => array_merge(
                        ['required'],
                        $this->document_type == UserKyc::TYPE_PAN_CARD
                            ? ['regex:' . regex('pan', 1)['pattern']]
                            : ['regex:' . regex('aadhaar_number', 1)['pattern']]
                    ),
                    'document_attachment' => [
                        'required',
                    ],
                ];
                break;

            case 'update':
                // Assuming validation helpers or constants for this case
                $rules = [
                    'name' => [
                        'min:' . validation('common_name')['pattern']['minlength'],
                        'max:' . validation('common_name')['pattern']['maxlength'],
                        'regex:' . regex('name', 1)['pattern'],
                    ],
                    'document_number' => [
                        'required',
                        'document_number' => array_merge(
                            ['required'],
                            $this->document_type == UserKyc::TYPE_PAN_CARD
                                ? ['regex:' . regex('pan', 1)['pattern']]
                                : ['regex:' . regex('aadhaar_number', 1)['pattern']]
                        ),
                    ],

                    'document_attachment' => [
                        'required',
                    ],

                ];
                break;

            default:
                $rules = [];
                break;
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        $messages = [];

        switch ($this->request_with) {
            case 'create':
                $messages = [
                    'name.required' => __('validation.required', ['attribute' => __('ui.full_name')]),
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
