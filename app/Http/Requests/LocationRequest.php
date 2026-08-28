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

class LocationRequest extends FormRequest
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
        case 'country-create':
            $rules = [
            'name' => 'required|regex:/^[a-zA-Z]+.*$/',
            'iso3' => 'required|min:3',
            'currency' => 'required|min:3|regex:/^[A-Z]{3}$/',
            'phonecode' => 'required|min:3|numeric',
            'region' => 'required',
          
            'capital' => 'required',
            ];
            break;
        case 'country-update':
            $rules = [
            'name' => 'required|regex:/^[a-zA-Z]+.*$/',
            'iso3' => 'required|min:3',
            'currency' => 'required|min:3|regex:/^[A-Z]{3}$/',
            'phonecode' => 'required|min:3|numeric',
                'region' => 'required',
              
                'capital' => 'required',
            ];
            break;
        case 'state-create':
            $rules = [
            'name'     => 'required|regex:/^[a-zA-Z]+.*$/',
            ];
            break;
        case 'state-update':
            $rules = [
            'name'     => 'required|regex:/^[a-zA-Z]+.*$/',
            ];
            break;
        case 'city-create':
            $rules = [
            'name'     => 'required|regex:/^[a-zA-Z]+.*$/',
            // 'latitude' => 'required|regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            // 'longitude' => 'required|regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ];
            break;
        case 'city-update':
            $rules = [
            'name'     => 'required|regex:/^[a-zA-Z]+.*$/',
            // 'latitude' => 'required|regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/',
            // 'longitude' => 'required|regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
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
        case 'country-create':
            $messages = [
            'name.required' =>  __('validation.required', ['attribute' => __('ui.country_name')]),
            'iso3.required'     => __('validation.required', ['attribute' => __('ui.country_code')]),
            ];
            break;
        case 'country-update':
            $messages = [
            'name.required' =>  __('validation.required', ['attribute' => __('ui.country_name')]),
            'iso3.required'     => __('validation.required', ['attribute' => __('ui.country_code')]),
            ];
            break;
        case 'state-create':
            $messages = [
            'name.required' =>  __('validation.required', ['attribute' => __('ui.state_name')]),
            ];
            break;
        case 'state-update':
            $messages = [
            'name.required' =>  __('validation.required', ['attribute' => __('ui.state_name')]),
            ];
            break;
        case 'city-create':
            $messages = [
            'name.required' =>  __('validation.required', ['attribute' => __('ui.city_name')]),
            'latitude' =>  __('validation.required', ['attribute' => __('ui.latitude')]),
            'longitude' =>  __('validation.required', ['attribute' => __('ui.longitude')]),
            ];
            break;
        case 'city-update':
            $messages = [
            'name.required' =>  __('validation.required', ['attribute' => __('ui.city_name')]),
            'latitude' =>  __('validation.required', ['attribute' => __('ui.latitude')]),
            'longitude' =>  __('validation.required', ['attribute' => __('ui.longitude')]),
            ];
            break;
        default:
            $messages = [];
            break;
        }
        return $messages;
    }
}
