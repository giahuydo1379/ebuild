<?php

namespace App\Http\Controllers;

require_once app_path() . '/Helpers/Spout/src/Box/Spout/Autoloader/autoload.php';

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class ExportController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'exports';

        ini_set('max_execution_time', 0);
        ini_set("memory_limit","256M");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Export sản phẩm';
        $params = $request->all();
        $this->data['params'] = $params;

        $message = $request->session()->get('message', '');
        $this->data['message'] = json_decode($message, 1);

        $CategoriesController = new \App\Http\Controllers\CategoriesController();
        $list_categories    = $CategoriesController->get_list_category(true);
        $category_options = array_pluck($list_categories['options'], 'category', 'category_id');
        $this->data['category_options']     = $category_options;
        if(isset($params['category_id']) && isset($list_categories['parent'][$params['category_id']])) {
            $params['category_ids'] = $list_categories['parent'][$params['category_id']];
            $params['category_ids'][] = $params['category_id'];
        }

        if (isset($params['is_export'])) {
            try {
                $objects = Products::getData($params);

                if ($objects && $objects->count() > 4000) {
                    $msg = "Số lượng cho phép xuất file là 4000 dòng, tìm thấy: ".$objects->count();
                } elseif ($objects) {
                    $header = ['ID', 'Mã sản phẩm', 'Tên sản phẩm', 'Số lượng', 'Giá bán', 'Giá thị trường'];
                    $fields = ['product_id', 'product_code', 'product', 'amount', 'price', 'list_price'];

                    \App\Helpers\Cexport::export('products', 'Danh sách sản phẩm', $header, $fields, $objects);
                }
            }
            catch (\Exception $e) {
                $msg = $e->getMessage();
            }

            $request->session()->flash('message', json_encode([
                'title' => 'Thông báo',
                'text' => isset($msg) ? $msg : 'Xuất excel không thành công',
                'type' => 'warring',
            ]));

            return redirect()->route('exports.index');
        }

        return view("{$this->data['controllerName']}.index", $this->data);
    }
}
