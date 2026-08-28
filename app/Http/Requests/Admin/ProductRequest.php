<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Must be true to avoid "This action is unauthorized."
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->request_with) {
            case 'create':
                $rules = [
                    'name' => 'required|string',
                    'price' => 'required|numeric|min:0',
                    'base_price' => 'required|numeric|min:0|gte:price',
                    'weight' => 'required|numeric|min:1',
                    'is_published' => 'nullable|integer|in:0,1',
                ];
                break;

            case 'update':
                $rules = [
                    // 'name' => 'required|string',
                    'price' => 'required|numeric|min:0',
                    'base_price' => 'required|numeric|min:0|gte:price',
                    // 'weight' => 'required|numeric|min:1',
                    // 'is_published' => 'nullable|integer|in:0,1',
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
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'price.required' => 'Price is required.',
            'weight.required' => 'Weight is required.',
        ];
    }
}

