<?php

namespace App\Http\Controllers;

use App\Models\Setting;

use Illuminate\Http\Request;

class SaleB2BController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'amortization';
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = 'Bán hàng B2B'; // set the page title

        $keys = [
            'sale_b2b_banner', 'sale_b2b_description'
        ];

        $settings = \App\Models\Setting::getObjectsByKeys($keys);

        $this->data['settings'] = $settings;

        $objects = \App\Models\PageBusinessLine::select('id', 'name', 'link', 'type')
            ->where('type', 'sale_b2b')
            ->where('is_deleted', 0)
            ->get()->toArray();
        $this->data['page_business_lines'] = $objects;

        return view('sale-b2b.index', $this->data);
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_logo(Request $request)
    {
        $data = $request->all();

        $type = $request->get('type', 'sale_b2b');
        $objects = \App\Models\PageBusinessLine::where('type', $type);
        if (isset($data['ids']) && $data['ids']) {
            $objects->whereNotIn('id', $data['ids']);
        }
        $objects->delete();

        if (isset($data['logos'])) {
            foreach ($data['logos'] as $line) {
                $line['type'] = $type;
                \App\Models\PageBusinessLine::create($line);
            }
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Cập nhật thông tin thành công'
        ]);
    }
}
