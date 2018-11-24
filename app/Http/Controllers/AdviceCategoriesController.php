<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class AdviceCategoriesController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'advice-categories';
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // set the page title
        $this->data['title'] = 'Danh mục tư vấn';

        $limit = $request->input('limit', env('LIMIT_LIST', 10));
        $page = $request->input('page', 1);

        $objects = \App\Models\AdviceCategories::select('advice_categories.*')
            ->orderBy('ordering', 'asc')
            ->paginate($limit)->toArray();
        $this->data['objects'] = $objects;

        $category_ids = [];
        foreach ($objects['data'] as $item) {
            $category_ids[] = $item['id'];
        }

        if ($category_ids) {
            $counts = \App\Models\Advices::select('category_id', \DB::raw('COUNT(advices.id) as count_news'))
                ->whereIn('category_id', $category_ids)
                ->groupBy('category_id')
                ->pluck('count_news', 'category_id')->toArray();
        } else {
            $counts = [];
        }

        $this->data['counts'] = $counts;

        $brand_options = \App\Models\Brands::getBrandOptions();
        $this->data['brand_options'] = $brand_options;

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

        $data['alias'] = str_slug($data['name']);

        $rules = [
            'name' => 'required|max:255',
            'alias' => 'required|unique:advice_categories',
            'ordering' => 'required',
        ];

        if ($id) {
            $rules['alias'] = 'required|unique:advice_categories,alias,'.$id;
        }

        $valid = Validator::make($data, $rules);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $data
            ]);
        }
        if(empty($data['brand_id']))
            $data['brand_id'] = 0;
        $data['user_modified'] = $this->get_user_id();
        if (isset($data['id']) && $data['id']) {

            $data = \App\Helpers\General::get_data_fillable(new \App\Models\AdviceCategories(), $data);

            $rs = \App\Models\AdviceCategories::where('id', $id)
                            ->update($data);

            if ($rs) {
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Cập nhật danh mục thành công',
                ]);
            }

            return response()->json([
                'rs' => 0,
                'msg' => 'Cập nhật danh mục không thành công'
            ]);

        }

        $data['user_created'] = $data['user_modified'];
        $rs = \App\Models\AdviceCategories::create($data);

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Thêm danh mục thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Thêm danh mục không thành công'
        ]);
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
    public function update(UpdateRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id', 0);

        if ($id) {
            \App\Models\AdviceCategories::find($id)->delete();

            return response()->json([
                'rs' => 1,
                'msg' => 'Xóa danh mục thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Xóa danh mục không thành công'
        ]);
    }
}
