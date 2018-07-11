<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the order.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new order status.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'chair_id' => 'required|int',
            'customer_id' => 'required|int',
            'amount' => 'required|numeric',
            'due_date' => 'required',
            'account_id' => 'required|int',
            'created_by' => 'required|int'
        ]);

        if ($validator->passes()) {

            $order = new Order();

            $order->chair_id = $request->chair_id;
            $order->customer_id = $request->customer_id;
            $order->amount = $request->amount;
            $order->due_date = Carbon::parse($request->due_date)->format('Y-m-d');
            $order->order_status_id = 1;
            $order->account_id = $request->account_id;
            $order->created_by = $request->created_by;

            $result = $order->save();

            if ($result) {
                return array(
                    'status' => 1,
                    'message' => "Order successfully created",
                    'data'=>array()
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to create Order!'.$request->chair_id,
                'data' => $validator->getMessageBag()->toArray()
            );
        }
    }

    /**
     * Display the specified order.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id)->select(
            [
                'id',
                'chair',
                'chair_type',
                DB::raw('CONCAT(first_name," ",last_name) AS customer_name',
                    'amount',
                    DB::raw('DATE_FORMAT(orders.created_at,"%d-%m-%Y") AS due_date',
                        'order_status_id', 'order_status'))]
        )
            ->leftJoin('chairs', 'chairs.id', '=', 'orders.chair_id')
            ->leftJoin('chair_types', 'chair_types.id', '=', 'chairs.chair_type_id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
            ->get();

        return $order;
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'chair_id' => 'required|int',
            'customer_id' => 'required|int',
            'amount' => 'required|numeric',
            'due_date' => 'required',
            'order_status_id' => 'required|int',
            'updated_by' => 'required|int'
        ]);

        if ($validator->passes()) {
            $order = Order::findOrFail($id);

            $order->chair_id = $request->chair_id;
            $order->customer_id = $request->customer_id;
            $order->amount = $request->amount;
            $order->due_date = $request->due_date;
            $order->order_status_id = $request->order_status_id;
            $order->updated_by = $request->updated_by;

            $result = $order->save();

            if ($result) {
                return array(
                    'status' => 1,
                    'message' => "Order successfully updated",
                    'data'=>array()
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to update Order!',
                'data' => $validator->getMessageBag()->toArray()
            );
        }
    }

    /**
     * Remove the specified order status from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Order::destroy($id)){
            return array(
                'status'=>1,
                'message'=>"Order successfully removed",
                'data'=>array()
            );
        }else{
            return array(
                'status'=>0,
                'message'=>"Failed to remove order!".$id,
                'data'=>array()
            );
        }
    }

    /**
     * Get all orders
     * @param Request $request
     */
    public function getAllOrders(Request $request)
    {
        $accountId = $request->account_id;
        $orders = Order::where('orders.account_id', '=', $accountId)->select(
            [
                'orders.id',
                'chair',
                'chair_type',
                DB::raw('CONCAT(first_name," ",last_name) AS customer_name'),
                'amount',
                DB::raw('DATE_FORMAT(orders.created_at,"%d-%m-%Y") AS due_date'),
                'order_status_id', 'order_status']
        )
            ->leftJoin('chairs', 'chairs.id', '=', 'orders.chair_id')
            ->leftJoin('chair_types', 'chair_types.id', '=', 'chairs.chair_type_id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.customer_id')
            ->leftJoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
            //->whereNull('orders.')
            ->get();

        if ($orders->count() > 0) {
            return array(
                'status' => 1,
                'message' => "",
                "data" => $orders
            );
        } else {
            return array(
                'status' => 1,
                'message' => "No Orders created yet!",
                "data" => array()
            );
        }
    }
}
