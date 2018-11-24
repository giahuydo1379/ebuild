<?php

namespace App\Http\Controllers;

use App\Models\Setting;

use Illuminate\Http\Request;

class ChainStoreController extends Controller
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

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = 'Hệ thống siêu thị'; // set the page title

        $this->data['chain_store_introduction'] = Setting::getObjectByKey('chain_store_introduction');

        $this->data['chain_store_banner'] = Setting::getObjectByKey('chain_store_banner');

        $chain_stores = \App\Models\ChainStore::select('chain_store.*',
            \DB::raw("CONCAT_WS(' ', provinces.type, provinces.name) as province_name"),
            \DB::raw("CONCAT_WS(' ', districts.type, districts.name) as district_name"),
            \DB::raw("CONCAT_WS(' ', wards.type, wards.name) as ward_name")
            )
            ->leftJoin('provinces', 'provinces.province_id', '=', 'chain_store.province_id')
            ->leftJoin('districts', 'districts.district_id', '=', 'chain_store.district_id')
            ->leftJoin('wards', 'wards.ward_id', '=', 'chain_store.ward_id')
                            ->where('chain_store.is_deleted', 0)
                            ->where('chain_store.brand_id', 0)
                            ->get();
        $tmp = [];
        foreach ($chain_stores as $item) {
            $tmp[$item['status']][$item['id']] = $item->toArray();
        }
        $this->data['chain_stores'] = $tmp;

        return view('chain-store.index', $this->data);
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function chainStoreSettingUpdate(Request $request)
    {
        $data = $request->all();

        unset($data['_token']);

        $obj = Setting::getObjectByKey($data['key']);

        if(!empty($obj))
        {
            $rs = Setting::where('key', $data['key'])->update(array('value' => $data['value'], 'field' => $data['field']));
        }
        else
        {
            $rs = Setting::insert($data);
        }

        if($rs)
        {
            $object = Setting::getObjectByKey($data['key']);

            return response()->json([
                'rs' => 1,
                'obj' => $object,
                'msg' => 'Cập nhật nội dung thành công'
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Cập nhật nội dung không thành công'
        ]);
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function settingUpdateBannerDescription(Request $request)
    {
        $data = $request->all();

        unset($data['_token']);

        $obj = Setting::getObjectByKey($data['banner_key']);

        if($obj)
        {
            $rs = Setting::where('key', $data['banner_key'])
                ->update(array('value' => $data['banner_value'], 'field' => $data['field']));
        }
        else
        {
            $dataInsert['key'] = $data['banner_key'];
            $dataInsert['value'] = $data['banner_value'];
            $dataInsert['field'] = $data['field'];
            $rs = Setting::insert($dataInsert);
        }

        $obj = Setting::getObjectByKey($data['description_key']);

        if(!empty($obj))
        {
            $rs = Setting::where('key', $data['description_key'])->update(array('value' => $data['description_value']));
        }
        else
        {
            $dataInsert['key'] = $data['description_key'];
            $dataInsert['value'] = $data['description_value'];
            $rs = Setting::insert($dataInsert);
        }

        if($rs)
        {
            return response()->json([
                'rs' => 1,
            ]);
        }

        return response()->json([
            'rs' => 0
        ]);
    }


    public function add(Request $request)
    {
        $data_post = $request->all();
        $id = $request->input('id', 0);

        if ($id) {
            $data = \App\Helpers\General::get_data_fillable(new \App\Models\ChainStore(), $data_post);
            $rs = \App\Models\ChainStore::where('id', $id)
                ->update($data);

            if ($rs) {

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật siêu thị thành công',
                    'data' => $rs
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật siêu thị không thành công'
            ]);
        }

        $object = \App\Models\ChainStore::create($data_post);

        if ($object) {

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm mới siêu thị thành công',
                'data' => $object
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm mới siêu thị không thành công'
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', 0);

        $object = \App\Models\ChainStore::find($id);

        if (!$object) {
            return response()->json([
                'rs' => 0,
                'msg' => 'Không tìm thấy siêu thị'
            ]);
        }

        $object->is_deleted = 1;
        $object->save();

        return response()->json([
            'rs' => 1,
            'msg' => 'Xóa siêu thị thành công',
        ]);
    }
}
