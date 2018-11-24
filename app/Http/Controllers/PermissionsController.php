<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permissions;

use App\Http\Requests\PermissionsRequest;

use App\Helpers\General as Helper;

class PermissionsController extends Controller
{
    private $_data = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->_data['title'] = 'Permissions';
        $this->_data['controllerName'] = 'permissions';
    }

    public function getShowAll() {
        return view("{$this->_data['controllerName']}.show-all", $this->_data);
    }

    /**
     * List staffs request
     * @return JSON
     * @author HaLV
     */
    public function getAjaxData(Request $request)
    {
        $filter = [
            'offset'    => $request->input('offset', 0),
            'limit'     => $request->input('limit', PAGE_LIST_COUNT),
            'sort'      => $request->input('sort', 'id'),
            'search'    => $request->input('search', ''),
        ];

        $data = Permissions::getAllShow($filter);

        return response()->json([
            'total' => $data['total'],
            'rows'  => $data['data'],
        ]);
    }

    public function getAdd() {
        $parentPermissions = Permissions::where('is_deleted', 0)->where('parent_id', 0)->pluck('name', 'id')->toArray();
        $this->_data['parentPermissions'] = array('' => '----- Chọn Parent -----') + $parentPermissions;
        return view("{$this->_data['controllerName']}.add", $this->_data);
    }

    public function postAdd(PermissionsRequest $request) {

        $data = $request->all();

        unset($data['_token']);

        $rs = Permissions::create($data);

        if ($rs) {
            return redirect("/{$this->_data['controllerName']}/show-all");
        }
        return redirect("/{$this->_data['controllerName']}/add");
    }

    public function getEdit($id) {
        $object = Permissions::where('id', $id)
            ->select('*')
            ->first();

        $this->_data['object'] = $object;

        $parentPermissions = Permissions::where('is_deleted', 0)->where('parent_id', 0)->pluck('name', 'id')->toArray();
        $this->_data['parentPermissions'] = array('' => '----- Chọn Parent -----') + $parentPermissions;

        return view("{$this->_data['controllerName']}.edit", $this->_data);
    }

    public function postEdit(PermissionsRequest $request, $id) {

        $data = $request->all();

        unset($data['_token']);

        $rs = Permissions::where('id', $id)->update($data);

        if ($rs) {
            return redirect("/{$this->_data['controllerName']}/show-all");
        }
        return redirect("/{$this->_data['controllerName']}/edit");
    }
}
