<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarrantyController extends Controller
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
        $this->data['title'] = 'Bảo hành, đổi trả'; // set the page title
        $keys = [
            'warranty_banner',
            'warranty_description'
        ];
        $settings = \App\Models\Setting::getObjectsByKeys($keys);
        $this->data['settings'] = $settings;

        return view('warranty.index', $this->data);
    }
}
