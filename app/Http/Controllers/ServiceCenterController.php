<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceCenterController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = 'Trung tâm bảo hành'; // set the page title
        $keys = [
            'service_center_banner',
            'service_center_description'
        ];
        $settings = \App\Models\Setting::getObjectsByKeys($keys);
        $this->data['settings'] = $settings;

        $service_centers = \App\Models\ServiceCenter::select('service_center.*',
            \DB::raw("CONCAT_WS(' ', provinces.type, provinces.name) as province_name"),
            \DB::raw("CONCAT_WS(' ', districts.type, districts.name) as district_name"),
            \DB::raw("CONCAT_WS(' ', wards.type, wards.name) as ward_name")
        )
            ->leftJoin('provinces', 'provinces.province_id', '=', 'service_center.province_id')
            ->leftJoin('districts', 'districts.district_id', '=', 'service_center.district_id')
            ->leftJoin('wards', 'wards.ward_id', '=', 'service_center.ward_id')
            ->where('service_center.is_deleted', 0)
            ->where('service_center.brand_id', 0)
            ->get();


        $tmp = array();

        foreach ($service_centers as $item) {
            $tmp[$item['id']] = $item->toArray();
        }
        $this->data['services'] = $tmp;

        return view('service-center.index', $this->data);
    }

    public function add(Request $request)
    {
        $data_post = $request->all();
        $id = $request->input('id', 0);

        if ($id) {
            $data = \App\Helpers\General::get_data_fillable(new \App\Models\ServiceCenter(), $data_post);
            $rs = \App\Models\ServiceCenter::where('id', $id)
                ->update($data);

            if ($rs) {

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật thành công',
                    'data' => $rs
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật không thành công'
            ]);
        }

        $object = \App\Models\ServiceCenter::create($data_post);

        if ($object) {

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới thành công',
                'data' => $object
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới không thành công'
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', 0);

        $object = \App\Models\ServiceCenter::find($id);

        if (!$object) {
            return response()->json([
                'rs' => 0,
                'msg' => 'Xóa không thành công'
            ]);
        }

        $object->is_deleted = 1;
        $object->save();

        return response()->json([
            'rs' => 1,
            'msg' => 'Xóa thành công',
        ]);
    }
}
