<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        
        $return = [
            'name' => 'required|min:5|max:255',            
        ];
        if($this->id){
            $return += [
                'phone' => 'required|unique:customers,phone,'.$this->id,
                'email' => 'required|email|unique:customers,email,'.$this->id
            ];
            if($this->password){
                $return['password'] = 'confirmed|min:6';
            }
            
        }else{
            $return += [
                'phone'     => 'required|unique:customers,phone',
                'email'     => 'required|email|unique:customers,email',
                'password'  => 'required|confirmed|min:6',
            ];
        }

        return $return;
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
            'phone.unique'          => 'Số điện thoại đã được sử dụng',
            'email.required'        => 'Nhập email',
            'email.email'           => 'Email sai định dạng',
            'email.unique'          => 'Email đã được sử dụng',
            'password.min'          => 'Mật khẩu tối thiểu 6 ký tự',
            'password.required'     => 'Nhập mật khẩu',
            'password.confirmed'    => 'Mật khẩu nhập lại không đúng',
        ];
    }
}
