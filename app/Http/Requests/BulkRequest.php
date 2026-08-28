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

class BulkRequest extends FormRequest
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
            case 'upload':
                return array_merge([
                    'file' => 'required|mimes:xlsx,xls|max:2048', 
                ]);
            default:
            return array_merge([
                'file' => 'required|mimes:xlsx,xls|max:2048', 
            ]);
        }
    }

    public function messages()
    {
        switch ($this->request_with) {
            case 'upload':
                return [
                    'file.required' => __('ui.file_required'),
                    'file.mimes' => __('ui.only_excel_allowed'),
                    'file.max' => __('ui.file_too_large'),
                ];
            default:
                return [];
        }
    }


}
