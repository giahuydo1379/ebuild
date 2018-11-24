<?php

namespace App\Http\Controllers\Admin;
use \App\Models\Booking;
use \App\Models\BookingDetail;
use \App\Models\ServicesUnits;
use \App\Models\ServicesExtra;
use \App\Models\ServicesFreezerUnits;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\BookingRequest as StoreRequest;
use App\Http\Requests\BookingRequest as UpdateRequest;

class BookingCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Booking');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/booking');
        $this->crud->setEntityNameStrings('đơn vị dịch vụ', 'đơn vị dịch vụ');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

//        $this->crud->setFromDb();

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        $this->crud->addField([
            'label' => 'Loại máy lạnh',
            'type' => 'select2',
            'name' => 'freezer_id',
            'entity' => 'Freezer',
            'attribute' => 'name',
            'model' => "App\Models\Freezer",
        ]);    
        $this->crud->addField([
            'label' => 'Công suất',
            'type' => 'select2',
            'name' => 'freezer_capacity_id',
            'entity' => 'FreezerCapacity',
            'attribute' => 'name',
            'model' => "App\Models\FreezerCapacity",
        ]);  
        $this->crud->addField([
            'name' => 'freezer_number',
            'label' => 'Số lượng',
            'type' => 'number'
        ]); 
        $this->crud->addField([
            'name' => 'price',
            'label' => 'Giá',
            'type' => 'number',
            'prefix' => "$",
        ]);      
        $this->crud->addField([
            'name'        => 'status', // the name of the db column
            'label'       => 'Trạng thái', // the input label
            'type'        => 'radio',
            'options'     => [ // the key will be stored in the db, the value will be shown as label;
                0 => "Bỏ kích hoạt",
                1 => "Kích hoạt"
            ],
            // optional
            'inline'      => true, // show the radios all on the same line?
        ]);
        // $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
       
        $this->crud->addColumn([    // SELECT            
            'label' => 'Khách hàng',
            'type' => 'select',
            'name' => 'customer_id',
            'entity' => 'customer',
            'attribute' => 'name',
            'model' => "App\Models\Booking",
        ]);
        $this->crud->addColumn([    // SELECT            
            'label' => 'Dịch vụ',
            'type' => 'select',
            'name' => 'service_id',
            'entity' => 'service',
            'attribute' => 'name',
            'model' => "App\Models\Booking",
        ]);
        $this->crud->addColumn([
            'name' => 'total_amount', // The db column name
            'label' => "Tổng tiền", // Table column heading
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name'        => 'status',
            'label'       => 'Trạng thái',
            'type'        => 'radio',
            'html'        => 1,
            'options'     => \App\Helpers\Common::get_columns_status()
        ]);
        // $this->crud->addColumns(); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        $this->crud->enableDetailsRow();
        //NOTE: you also need to do allow access to the right users: 
        $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        $this->crud->orderBy('id', 'desc');
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function showDetailsRow($id){
        $data = Booking::with(['customer', 'service','contact','bookingDetail','bookingFreezerDetail'])->find($id)->toArray();

        if(!empty($data['booking_detail'])){
            foreach($data['booking_detail'] as &$item){
                $item['service_unit'] = ServicesUnits::find($item['service_unit_id'])->toArray();
                if(!empty($item['service_extra_ids'])){
                    $item['service_extra_ids'] = json_decode($item['service_extra_ids'],1);
                    $service_extra_ids = array_pluck($item['service_extra_ids'], 'id');
                    $service_extra = ServicesExtra::whereIn('id',$service_extra_ids)->get()->toArray();
                    $item['service_extra'] = array_pluck($service_extra, 'name','id');
                }
            }        
        }

        if(!empty($data['booking_freezer_detail'])){
            $data['booking_freezer_detail']['services_freezer_units'] = ServicesFreezerUnits::with(['Freezer','FreezerCapacity'])->find($data['booking_freezer_detail']['services_freezer_units_id'])->toArray();

            if(!empty($data['booking_freezer_detail']['services_extra'])){

                $data['booking_freezer_detail']['service_extra_ids'] = json_decode($data['booking_freezer_detail']['services_extra'],1);

                $service_extra_ids = array_pluck($data['booking_freezer_detail']['service_extra_ids'], 'id');
                $service_extra = ServicesExtra::whereIn('id',$service_extra_ids)->get()->toArray();
                $data['booking_freezer_detail']['service_extra'] = array_pluck($service_extra, 'name','id');
            }
        }
        //dd($data);
        return view('booking.detail',$data);
    }
}
