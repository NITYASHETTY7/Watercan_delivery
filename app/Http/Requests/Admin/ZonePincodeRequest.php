<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ZonePincodeRequest extends FormRequest
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
                    'pincode' => 'required|string|min:4|max:6|regex:/^[0-9]+$/',
                    'zone_id' => 'required',
                ];
                break;

            case 'update':
                $rules = [
                    'pincode' => 'required|string|min:4|max:6|regex:/^[0-9]+$/',
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
            'pincode.required' => 'Pincode is required.',
        ];
    }
}

