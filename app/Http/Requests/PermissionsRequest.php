<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PermissionsRequest extends Request
{
    public function __construct()
    {

    }

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
     * @return array
     */
    public function rules()
    {
        $data = array();
        $data['name_label'] = 'required';
        $data['route'] = 'required';
        return $data;
    }

    public function messages()
    {
        $data = array();
        $data["name_label.required"] = 'Bạn phải nhập tên chức năng!';
        $data["route.required"] = 'Bạn phải nhập Route!';
        return $data;
    }
}