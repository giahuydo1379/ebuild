<?php

namespace App\Http\Controllers;

require_once app_path('Helpers/Spout/src/Box/Spout/Autoloader/autoload.php');

use App\Models\Cscart\Product;
use App\Models\Features;
use App\Models\FeaturesDescriptions;
use App\Models\FilterRanges;
use App\Models\Filters;
use DeepCopy\Filter\Filter;
use Illuminate\Http\Request;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;

class ToolsController extends Controller
{
    protected $data = []; // the information we send to the view

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->data['controllerName'] = 'tools';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filePath = public_path("files/SKU-List-03-01-2018.XLSX");
        $newFilePath = public_path("files/SKU-List-error-03-01-2018.XLSX");
        $file_script = public_path("files/script-update.sql");


        // we need a reader to read the existing file...
        $reader = ReaderFactory::create(Type::XLSX); // for XLSX files
        //$reader = ReaderFactory::create(Type::CSV); // for CSV files
        //$reader = ReaderFactory::create(Type::ODS); // for ODS files
        $reader->open($filePath);

        // ... and a writer to create the new file
        $writer = WriterFactory::create(Type::XLSX);
        $writer->openToFile($newFilePath);
        $writer->addRow(['Article', 'Article Number','Old article no.']);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                // do stuff with the row
                if ($index==1) continue;

                if (empty(trim($row[2]))) {
                    $writer->addRow($row);
                    continue;
                }

                $p = Product::where('product_code', $row[2])->first();
                if ($p) {
                    $txt = "UPDATE `thienhoa_data`.`cscart_products` SET `product_code`='".$row[0]."' WHERE  `product_code`='".$row[2]."';";
                    file_put_contents($file_script, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
                } else {
                    $writer->addRow($row);
                }
            }
        }

        $writer->close();
        $reader->close();
        die('success');
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
    public function update(Request $request, $id)
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
    }

    public function sync_product_filter_ranges(Request $request) {
        $filters = Filters::where('feature_id', 0)
            ->where('status', 'A')
            ->get()->toArray();

        foreach ($filters as $filter) {
            $feature = Features::create([
                'categories_path' => $filter['categories_path'],
                'is_range' => 1,
                'categories_path' => $filter['categories_path'],
            ]);

            FeaturesDescriptions::create([
                'feature_id' => $feature->feature_id,
                'description' => 'Mức giá',
                'lang_code' => 'vi',
            ]);

            Filters::where('filter_id', $filter['filter_id'])->update(['feature_id' => $feature->feature_id]);
            FilterRanges::where('filter_id', $filter['filter_id'])->update(['feature_id' => $feature->feature_id]);
        }

        return response()->json([
            'rs' => 1,
            'msg' => 'Thành công'
        ]);
    }
}
