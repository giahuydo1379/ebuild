<?php

namespace App\Http\Controllers;

use App\Models\Setting;

use Illuminate\Http\Request;

class AmortizationController extends Controller
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
        $this->data['title'] = 'Trả góp'; // set the page title

        $keys = [
            'amortization_banner',
            'amortization_info','amortization_process_buy'
        ];

        $settings = \App\Models\Setting::getObjectsByKeys($keys);

        $this->data['settings'] = $settings;

        $types = ['amortization_bank','amortization_partner','amortization_line'];
        $page_business_lines = \App\Models\PageBusinessLine::getObjectsByKeys($types);
        $this->data['page_business_lines'] = $page_business_lines;

        return view('amortization.index', $this->data);
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_bank_partner(Request $request)
    {
        $data = $request->all();

        $types = ['amortization_bank','amortization_partner'];
        $objects = \App\Models\PageBusinessLine::whereIn('type', $types);
        if (isset($data['ids']) && $data['ids']) {
            $objects->whereNotIn('id', $data['ids']);
        }
        $objects->delete();

        if (isset($data['banks'])) {
            foreach ($data['banks'] as $line) {
                $line['type'] = 'amortization_bank';
                \App\Models\PageBusinessLine::create($line);
            }
        }

        if (isset($data['partners'])) {
            foreach ($data['partners'] as $line) {
                $line['type'] = 'amortization_partner';
                \App\Models\PageBusinessLine::create($line);
            }
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Cập nhật thông tin thành công'
        ]);
    }
}
