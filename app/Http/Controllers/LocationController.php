<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
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
    public function get_provinces(Request $request)
    {
        $objects = \App\Models\Province::select('province_id', \DB::raw("CONCAT_WS(' ', type, name) as province_name"))
                                        ->where('is_deleted', 0)
                                        ->orderBy('ordering', 'asc')
                                        ->pluck('province_name', 'province_id')->toArray();

        return response()->json([
            'rs' => 1,
            'data' => $objects
        ]);
    }

    public function get_districts(Request $request)
    {
        $province_id = $request->input('province_id', '');

        $objects = \App\Models\District::select('district_id', \DB::raw("CONCAT_WS(' ', type, name) as district_name"))
            ->where('province_id', $province_id)
            ->where('is_deleted', 0)
            ->pluck('district_name', 'district_id');

        return response()->json([
            'rs' => 1,
            'data' => $objects
        ]);
    }

    public function get_wards(Request $request)
    {
        $district_id = $request->input('district_id', '');

        $objects = \App\Models\Ward::select('ward_id', \DB::raw("CONCAT_WS(' ', type, name) as ward_name"))
            ->where('district_id', $district_id)
            ->where('is_deleted', 0)
            ->pluck('ward_name', 'ward_id');

        return response()->json([
            'rs' => 1,
            'data' => $objects
        ]);
    }
}
