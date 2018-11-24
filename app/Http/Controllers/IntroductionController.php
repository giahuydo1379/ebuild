<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IntroductionController extends Controller
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
    public function index(Request $request)
    {
//        $slug = 'gioi-thieu';
        $slug = $request->input('slug', 'gioi-thieu');

        $object = \App\Models\Pages::findBySlug($slug);

        $this->data['title'] = $object['title'];//'Giới thiệu công ty'; // set the page title

        $this->data['object'] = $object ? $object->toArray() : [];
        $this->data['slug'] = $slug;

        return view('introduction.index', $this->data);
    }

    public function update_image(Request $request)
    {
        $data = $request->all();

        $object = \App\Models\Pages::find($data['id']);

        $rs = false;
        if($object)
        {
            $rs = $object->update([
                'image_location' => $data['image_location'],
                'image_url' => isset($data['image_url']) ? $data['image_url'] : config('app.url_outside'),
                'image_link' => $data['image_link'],
            ]);
        }

        if($rs)
        {
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

    public function update_content(Request $request)
    {
        $data = $request->all();

        $object = \App\Models\Pages::find($data['id']);

        $rs = false;
        if($object)
        {
            $data_update = \App\Helpers\General::get_data_fillable(new \App\Models\Pages(), $data);

            if (isset($data_update['extras']) && is_array($data_update['extras'])) {
                $data_update['extras'] = json_encode($data_update['extras']);
            }

            $rs = $object->update($data_update);
        }

        if($rs)
        {
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
}
