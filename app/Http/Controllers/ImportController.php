<?php

namespace App\Http\Controllers;

require_once app_path() . '/Helpers/Spout/src/Box/Spout/Autoloader/autoload.php';

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

class ImportController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'imports';

        ini_set('max_execution_time', 3000);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->data['title'] = 'Import sản phẩm';
        $params = $request->all();
        $this->data['params'] = $params;

        return view("{$this->data['controllerName']}.index", $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request)
    {
        $data = $request->all();

        $rules = [
            'newfile' => 'required|file|mimes:xlsx',
        ];

        $messages = [
            'newfile.required' => 'Chọn file danh sách sản phẩm mới',
        ];

        $valid = Validator::make($data, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $data
            ]);
        }

        $file = $request->file('newfile');
        $filename = time().'-'.$file->getClientOriginalName();
        $filename = $file->storeAs('imports', $filename);
        $filename = storage_path('app') ."/". $filename;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($filename);

        $rs = 0;
        $lang_code = 'vi';

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                // do stuff with the row
                if ($index===1) continue;

                $data = [
                    'product' => $row[0],
                    'product_code' => $row[1],
                    'amount' => $row[2],
                    'weight' => $row[3],
                    'list_price' => $row[4],
                    'price' => $row[5],
                    'brand_id' => $row[6],
                    'category_id' => $row[7]
                ];

                $product = \App\Models\Products::create($data);

                if($product){

                    $product_id = $product['product_id'];
                    $data['product_id']     = $product_id;
                    $data['lang_code']      = $lang_code;

                    \App\Models\ProductDescriptions::create($data);

                    $data['link_type'] = 'M';
                    \App\Models\ProductsCategories::create($data);

                    $rs++;
                }
            }
        }

        $reader->close();

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Import danh sách sản phẩm mới thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Import danh sách sản phẩm mới không thành công'
        ]);
    }

    public function products_count(Request $request)
    {
        $data = $request->all();

        $rules = [
            'numberfile' => 'required|file|mimes:xlsx',
        ];

        $messages = [
            'numberfile.required' => 'Chọn file danh sách số lượng sản phẩm',
        ];

        $valid = Validator::make($data, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $data
            ]);
        }

        $file = $request->file('numberfile');
        $filename = time().'-'.$file->getClientOriginalName();
        $filename = $file->storeAs('imports', $filename);
        $filename = storage_path('app') ."/". $filename;

        $rs = 0;

        try {
            $reader = ReaderFactory::create(Type::XLSX);

            $reader->open($filename);

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $index => $row) {
                    // do stuff with the row
                    if ($index===1 || !trim($row[0])) continue;

                    $product = Products::whereRaw("TRIM(product_code) = '".trim($row[0])."'");
                    if ($product) {
                        $product->update([
                            'product_code' => trim($row[0]),
                            'amount' => $row[1]
                        ]);

                        $rs++;
                    }
                }
            }

            $reader->close();

        } catch (\Exception $e) {
            var_dump('Caught exception: ',  $e->getMessage(), "\n");
            die;
        }

        if ($rs) {
            return response()->json([
                'rs' => 1,
                'msg' => 'Import danh sách số lượng sản phẩm thành công',
            ]);
        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Import danh sách số lượng sản phẩm không thành công'
        ]);
    }

    public function products_price(Request $request)
    {
        $data = $request->all();

        $rules = [
            'pricefile' => 'required|file|mimes:xlsx',
        ];

        $messages = [
            'pricefile.required' => 'Chọn file danh sách giá sản phẩm',
        ];

        $valid = Validator::make($data, $rules, $messages);

        if ($valid->fails())
        {
            return response()->json([
                'rs' => 0,
                'msg' => 'Dữ liệu nhập không hợp lệ',
                'errors' => $valid->errors()->messages(),
                'data' => $data
            ]);
        }

        $file = $request->file('pricefile');
        $filename = time().'-'.$file->getClientOriginalName();
        $filename = $file->storeAs('imports', $filename);
        $filename = storage_path('app') ."/". $filename;

        $reader = ReaderFactory::create(Type::XLSX);

        $reader->open($filename);

        $rs = 0;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                // do stuff with the row
                if ($index===1 || !trim($row[0])) continue;
                $product = Products::whereRaw("TRIM(product_code) = '".trim($row[0])."'");
                if ($product) {
                    $product->update([
                        'product_code' => trim($row[0]),
                        'price' => $row[1]
                    ]);

                    $rs++;
                }
            }
        }

        $reader->close();

        if ($rs) {
            if($request->ajax()){
                return response()->json([
                    'rs' => 1,
                    'msg' => 'Import danh sách giá sản phẩm thành công',
                ]);
            }


        }

        return response()->json([
            'rs' => 0,
            'msg' => 'Import danh sách giá sản phẩm không thành công'
        ]);
    }
}
