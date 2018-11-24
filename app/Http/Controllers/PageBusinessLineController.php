<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PageBusinessLineController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'chain-store'; // set the page title
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', 0);

        $object = \App\Models\PageBusinessLine::find($id);

        if (!$object) {
            return response()->json([
                'rs' => 0,
                'msg' => 'Không tìm thấy ngành hàng'
            ]);
        }

        $object->is_deleted = 1;
        $object->save();

        return response()->json([
            'rs' => 1,
            'msg' => 'Xóa ngành hàng thành công',
        ]);
    }
}
