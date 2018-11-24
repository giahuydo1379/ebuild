<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name'          => 'required|min:5|max:255',
            'phone'         => 'required',
            'email'         => 'required|email',
            'customer_id'   => 'required',
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
            'name.required'         => 'Nhập tên',
            'name.min'              => 'Tối thiểu 5 ký tự',
            'name.max'              => 'Tối đa 255 ký tự',
            'phone.required'        => 'Nhập số điện thoại',
            'email.required'        => 'Nhập email',
            'email.email'           => 'Email sai định dạng',
            'customer_id.required'  => 'Chọn khách hàng',
        ];
    }
}
