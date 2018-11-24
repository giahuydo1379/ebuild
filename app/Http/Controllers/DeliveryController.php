<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryController extends Controller
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
        $this->data['title'] = 'Giao hàng, lắp đặt'; // set the page title
        $keys = [
            'delivery_banner',
            'delivery_description'
        ];
        $settings = \App\Models\Setting::getObjectsByKeys($keys);
        $this->data['settings'] = $settings;

        return view('delivery.index', $this->data);
    }
}
