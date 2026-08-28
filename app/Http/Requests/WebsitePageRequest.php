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
use Illuminate\Validation\Rule;

class WebsitePageRequest extends FormRequest
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
                'title' => [
                    'min:' .validation('common_title',1)['pattern']['minlength'],
                    'max:' .validation('common_title',1)['pattern']['maxlength'],
                    'regex:'.regex('text',1)['pattern'],
                ],
                'content' => 'required',
                'slug' => 'required|unique:website_pages' . ($this->id ? ",{$this->id}" : ''),

                'page_meta_title' => [
                    'min:' .validation('common_meta_title',1)['pattern']['minlength'],
                    'max:' .validation('common_meta_title',1)['pattern']['maxlength'],
                    'regex:' .regex('text',1)['pattern'],
                ],
                // 'page_keywords' => [
                //     'required',
                //     'max:' .validation('common_meta_keywords',1)['pattern']['maxlength'],
                //     'regex:'.regex('meta',1)['pattern'],
                // ],
                // 'page_meta_description' => [
                //     'required',
                //     'min:' .validation('common_meta_description',1)['pattern']['minlength'],
                //     'max:' .validation('common_meta_description',1)['pattern']['maxlength'],
                //     'regex:'.regex('short_description',1)['pattern'],
                // ],


            ];
            break;
        case 'update':
            $rules = [
                'title' => [
                    'min:' .validation('common_title',1)['pattern']['minlength'],
                    'max:' .validation('common_title',1)['pattern']['maxlength'],
                    'regex:'.regex('text',1)['pattern'],
                ],
                'content' => 'required',
                'slug' => [
                    Rule::unique('website_pages', 'slug')->ignore($this->id),
                ],

                'page_meta_title' => [
                    'min:' .validation('common_meta_title',1)['pattern']['minlength'],
                    'max:' .validation('common_meta_title',1)['pattern']['maxlength'],
                    'regex:'.regex('text',1)['pattern'],
                ],
                // 'page_keywords' => [
                //     'max:' .validation('common_meta_keywords',1)['pattern']['maxlength'],
                //     'regex:'.regex('meta',1)['pattern'],
                // ],
                // 'page_meta_description' => [
                //     'min:' .validation('common_meta_description',1)['pattern']['minlength'],
                //     'max:' .validation('common_meta_description',1)['pattern']['maxlength'],
                //     'regex:'.regex('short_description',1)['pattern'],
                // ],

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
                'title.required' => __('validation.required', ['attribute' => __('ui.title')]),
                'content.required' => __('validation.required', ['attribute' => __('ui.content')]),
                'slug.required' => __('validation.required', ['attribute' => __('ui.slug')]),
                'slug.unique' => __('validation.unique', ['attribute' => __('ui.slug')])

            ];
            break;
        case 'update':
            $messages = [
                'title.required' => __('validation.required', ['attribute' => __('ui.title')]),
                'content.required' => __('validation.required', ['attribute' => __('ui.content')]),
                'slug.required' => __('validation.required', ['attribute' => __('ui.slug')]),
                'slug.unique' => __('validation.unique', ['attribute' => __('ui.slug')])


            ];
            break;
        default:
            $messages = [];
            break;
        }
        return $messages;
    }
}
