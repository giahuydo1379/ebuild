<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Validator;

class CacheController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
//        $this->middleware('admin');
        $this->data['controllerName'] = 'cache';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_all(Request $request)
    {
        Cache::flush();

        return response()->json([
            'rs' => 1,
            'ver_css' => \App\Helpers\General::get_version_css(true),
            'ver_js' => \App\Helpers\General::get_version_js(true),
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_css(Request $request)
    {
        $version = \App\Helpers\General::get_version_css(true);
        
        return response()->json([
            'rs' => 1,
            'ver' => $version
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_js(Request $request)
    {
        $version = \App\Helpers\General::get_version_js(true);
        
        return response()->json([
            'rs' => 1,
            'ver' => $version
        ]);
    }
}

