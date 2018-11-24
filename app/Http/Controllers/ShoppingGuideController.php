<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShoppingGuideController extends Controller
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
        $this->data['title'] = 'Hướng dẫn mua hàng'; // set the page title
        $keys = [
            'shopping_guide_banner',
            'shopping_guide_banner_2nd',
            'shopping_guide_description'
        ];
        $settings = \App\Models\Setting::getObjectsByKeys($keys);
        $this->data['settings'] = $settings;

        return view('shopping-guide.index', $this->data);
    }
}
