<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class IndexController extends Controller
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
    public function dashboard()
    {
        $this->data['title'] = trans('backpack::base.dashboard'); // set the page title

        $this->data['support_requests'] = [];//\App\Models\SupportRequest::getTopRequests(6);
        $this->data['product_comments'] = \App\Models\ProductComment::getTopComments(6);

        $order_status = \App\Models\OrderStatus::select('orders_status.order_status_name', 'orders_status.status as id')
                            ->where('is_active', 1)
                            ->where('is_deleted', 0)
                            ->orderBy('order_status_name', 'asc')
                            ->get()->toArray();
        $this->data['order_status'] = $order_status;

        $modal_order = new \App\Models\Order();
        
        $this->data['day']      = $modal_order->getReportOrderInDayGroupByStatus();
        $this->data['yesterday']      = $modal_order->getReportOrderInYesterdayGroupByStatus();

        $this->data['week']     = $modal_order->getReportOrderInWeekGroupByStatus();
        $this->data['lastweek']     = $modal_order->getReportOrderInLastWeekGroupByStatus();

        $this->data['month']        = $modal_order->getReportOrderInMonthGroupByStatus();
        $this->data['lastmonth']    = $modal_order->getReportOrderInLastMonthGroupByStatus();

        $this->data['year']     = $modal_order->getReportOrderInYearGroupByStatus();
        $this->data['lastyear'] = $modal_order->getReportOrderLastYearGroupByStatus();

        $admin_count = \App\User::count();
        $this->data['admin_count'] = $admin_count;

        $user_count = 0;//\App\Models\Users::count();
        $this->data['user_count'] = $user_count;

        $categories = \App\Models\Categories::select()
                    ->select('status', \DB::raw('count(category_id) as count'))
                    ->groupBy('status')
                    ->pluck('count', 'status');
        $this->data['categories'] = $categories;
        $this->data['categories_status'] = \App\Models\Categories::get_status_options();

        // $categories = \App\Models\Category::select()
        //             ->select('active', \DB::raw('count(id) as count'))
        //             ->groupBy('active')
        //             ->pluck('count', 'active');

        // $this->data['categories'] = $categories;
        // $this->data['categories_status'] = \App\Models\Category::get_status_options();

        $products = \App\Models\Products::select()
                    ->select('status', \DB::raw('count(product_id) as count'))
                    ->groupBy('status')
                    ->pluck('count', 'status');
        $this->data['products'] = $products;
        $this->data['products_status'] = \App\Helpers\General::get_status_product_options();

        return view('index.dashboard', $this->data);
    }

    public function total_revenue(Request $request)
    {
        $params = $request->all();

        if ($params['type']=='hour') {
            $query = Order::select(\DB::raw('DATE_FORMAT(FROM_UNIXTIME(timestamp),"%H \Giờ") as group_date'),
                \DB::raw('SUM(subtotal) as revenue'));
        } elseif ($params['type']=='day') {
            $query = Order::select(\DB::raw('DATE_FORMAT(FROM_UNIXTIME(timestamp),"%d-%m-%Y") as group_date'),
                \DB::raw('SUM(subtotal) as revenue'));
        } else {
            $query = Order::select(\DB::raw('DATE_FORMAT(FROM_UNIXTIME(timestamp), "\Tháng %m") as group_date'),
                \DB::raw('SUM(subtotal) as revenue'));
        }

        $tmp = \DateTime::createFromFormat('d-m-Y', $params['from'])->format('Y-m-d 00:00:00');
        $query->where('timestamp','>=', strtotime($tmp));

        $tmp = \DateTime::createFromFormat('d-m-Y', $params['to'])->format('Y-m-d 23:59:59');
        $query->where('timestamp','<=',strtotime($tmp));

        $query->where('status','=', 'C');

        $query->groupBy('group_date');

//        $query->limit(7);

        $data = $query->get()->toArray();

        return response()->json([
            'rs' => 0,
            'msg' => 'Thành công',
            'params' => $params,
            'group_date' => array_column($data, 'group_date'),
            'revenue' => array_column($data, 'revenue'),
        ]);
    }

    /**
     * Redirect to the dashboard.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function redirect()
    {
        // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
        return redirect(config('backpack.base.route_prefix').'/dashboard');
    }
}
