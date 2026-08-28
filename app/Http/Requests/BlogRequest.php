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
use App\Http\Requests;

class BlogRequest extends FormRequest
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
                    'short_description' => [
                        'min:' .validation('common_short_description',1)['pattern']['minlength'],
                        'max:' .validation('common_short_description',1)['pattern']['maxlength'],
                    ],
                    'meta[meta_title]' => [
                        'nullable ',
                        'min:' .validation('common_meta_title',1)['pattern']['minlength'],
                        'max:' .validation('common_meta_title',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],

                    'meta[meta_keyword]' => [
                        'nullable ',
                        'max:' .validation('common_meta_keywords',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],

                    'meta[meta_description]' => [
                        'nullable ',
                        'min:' .validation('common_meta_description',1)['pattern']['minlength'],
                        'max:' .validation('common_meta_description',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],

                    'slug' => 'unique:blogs,slug|regex:/^[a-zA-Z]+.*$/',
                    'identifier' => 'nullable',
                    'description' => 'required',
                    'category_id' => 'required',
                    'is_published' => 'nullable',
                    // 'type' => 'required',
                    //   'blog_banner_image' => 'required',
                ];
                break;
            case 'update':
                $rules = [
                    'title' => [

                        'min:' .validation('common_title',1)['pattern']['minlength'],
                        'max:' .validation('common_title',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],

                    'short_description' => [

                        'min:' .validation('common_short_description',1)['pattern']['minlength'],
                        'max:' .validation('common_short_description',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],

                    'meta[meta_title]' => [
                        'nullable ',
                        'min:' .validation('common_meta_title',1)['pattern']['minlength'],
                        'max:' .validation('common_meta_title',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],

                    'meta[meta_keyword]' => [
                        'nullable ',
                        'max:' .validation('common_meta_keywords',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],

                    'meta[meta_description]' => [
                        'nullable ',
                        'min:' .validation('common_meta_description',1)['pattern']['minlength'],
                        'max:' .validation('common_meta_description',1)['pattern']['maxlength'],
                        'regex:'.regex('text',1)['pattern'],
                    ],
                    //unknown code
                    // 'slug' => [
                    //     'required',
                    //     'regex:/^[a-zA-Z]+.*$/',
                    //     Rule::unique('blogs', 'slug')->ignore($this->id),
                    // ],
                    // 'type' => 'required',
                    // 'blog_banner_image' => 'required',
                    'category_id' => 'required',
                    'is_published' => 'nullable',
                    'description' => 'required',
                    'identifier' => 'nullable',
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
                    'description.required' => __('validation.required', ['attribute' =>__('ui.description')]),
                ];
                break;
            case 'update':
                $messages = [
                    'title.required' => __('validation.required', ['attribute' => __('ui.title')]),
                    'description.required' => __('validation.required', ['attribute' =>__('ui.description')]),
                ];
                break;
            default:
                $messages = [];
                break;
        }
        return $messages;
    }
}
