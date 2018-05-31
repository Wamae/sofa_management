<?php

namespace App\Http\Controllers;

use App\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the order status.
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
     * Store a newly created order status in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_status' => 'required',
            'account_id' => 'required|int',
            'created_by'=>'required|int'
        ]);

        if ($validator->passes()) {

            $orderStatus = new OrderStatus();
            $orderStatus->order_status = ucwords(strtolower($request->order_status));
            $orderStatus->account_id = $request->account_id;
            $orderStatus->created_by = $request->created_by;

            $result = $orderStatus->save();

            if($result){
                return array(
                    'status'=>1,
                    'message'=>"Order Status successfully created"
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to create Order Status!',
                'data' => $validator->getMessageBag()->toArray()
            );
        }
    }

    /**
     * Display the specified order status.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderStatus = OrderStatus::find($id)->select(['id','order_status'])->get();

        return $orderStatus;
    }

    /**
     * Show the form for editing the specified order status.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified order status in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'order_status' => 'required',
            'account_id' => 'required|int',
            'updated_by'=>'required|int'
        ]);

        if ($validator->passes()) {
            $orderStatus = OrderStatus::findOrFail($id);

            $orderStatus->order_status = ucwords(strtolower($request->order_status));
            $orderStatus->updated_by = $request->updated_by;

            $result = $orderStatus->save();

            if($result){
                return array(
                    'status'=>1,
                    'message'=>"Order Status successfully updated"
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to update Order Status!',
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

    }

    /**
     * Get all order statuses
     * @param Request $request
     */
    public function getAllOrderStatuses(Request $request)
    {
        $accountId = $request->account_id;
        $orderStatuses = OrderStatus::select(['id','order_status'])
            ->where('account_id','=',$accountId)->get();

        if($orderStatuses->count() > 0){
            return $orderStatuses;
        }else{
            return array(
                'status'=>1,
                'message'=>"No Order Statuses created yet!",
                "data"=>null
            );
        }
    }
}
