<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ServicesFreezerUnitsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'freezer_id'            => 'required',
            'freezer_capacity_id'   => 'required',
            'freezer_number'        => 'required',
            'price'                 => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
        'freezer_id.required'            => 'Phải chọn loại máy',
        'freezer_capacity_id.required'   => 'Phải chọn công suất',
        'freezer_number.required'        => 'Phải nhập số lượng',
        'price.required'                 => 'Phải nhập giá',
        ];
    }
}
