<?php

namespace App\Http\Controllers;

use App\Models\BookingStatus;
use Illuminate\Http\Request;
use Validator;

class BookingStatusController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'booking-status';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Quản lý trạng thái đơn hàng';
        $params = $request->all();
        $this->data['params'] = $params;

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\BookingStatus::select('*');

        $objects->where('lang_code','vi');
        $objects->where('is_deleted',0);

        $objects->orderBy('position','asc');
        $objects->orderBy('booking_status_name','asc');

        $objects = $objects->get()->toArray();

        $this->data['objects'] = $objects;

        return view("{$this->data['controllerName']}.index", $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();        

        $id = $request->input('id', 0);

        $rules = [
            'id' => 'required',
            'booking_status_name' => 'required',
        ];

        $messages = [
            'id.required' => 'Nhập mã trạng thái đơn hàng',
            'booking_status_name.required' => 'Nhập tên trạng thái đơn hàng',
        ];

        $valid = Validator::make($data, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $data
            ]);
        }

        if (isset($data['action']) && $data['action']=='update') {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\BookingStatus(), $data);

            $rs = \App\Models\BookingStatus::where('id', $id)->first();

            if ($rs) {
                $rs->update($data);

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật trạng thái đơn hàng thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật trạng thái đơn hàng không thành công'
            ]);

        }

        $data['lang_code'] = 'vi';
        $rs = \App\Models\BookingStatus::create($data);

        if ($rs) {
            $rs->booking_status_id = $rs->id;
            $rs->save();

            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm trạng thái đơn hàng thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm trạng thái đơn hàng không thành công'
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check_id(Request $request)
    {
        $id = $request->input('id', null);
        $object = BookingStatus::where('status', $id)->first();

        if ($object) return response()->json(false);

        return response()->json(true);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_status(Request $request)
    {
       
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id', '');

        if ($id) {
            $object = \App\Models\BookingStatus::where('id', $id)->first();

            if ($object) {
                $object->is_deleted = 1;
                $object->save();

                return response()->json([
                    'rs' => 1,
                    'msg' => 'Xóa trạng thái đơn hàng thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Không tìm thấy trạng thái đơn hàng',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa trạng thái đơn hàng không thành công'
        ]);
    }
}
