<?php

namespace App\Http\Controllers;

use App\Models\Old\Product;
use App\Models\ProductFiles;
use Illuminate\Http\Request;

class ConvertController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'point';
    }

    public function update_sku(Request $request)
    {
        $params = $request->all();
        $this->data['params'] = $params;

        $objects = \App\Models\Products::whereRaw("product_code IS NULL OR product_code=''")->get();

        foreach ($objects as $item) {
            $item->product_code = 'SKU'.$item->product_id;
            $item->save();
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Thành công'
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product_files(Request $request)
    {
        $params = $request->all();
        $this->data['params'] = $params;

        $objects = Product::where('status', 1)->get();

        foreach ($objects as $item) {
            $images = json_decode($item->image_album);
            foreach ($images as $image) {
                ProductFiles::create([
                    'pid' => $item['id'],
                    'file_name' => $image,
                    'image_url' => 'http://www.ebuild.vn'
                ]);
            }
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Thành công'
        ]);
    }
}
