<?php

namespace App\Http\Controllers;

use App\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderStatusController extends Controller
{

    public function __construct() { $this->middleware('jwt.auth'); }

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
        return array(
            'status'=>1,
            'message'=>"Not allowed!",
            'data'=>array()
        );

 /*       $validator = Validator::make($request->all(), [
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
                    'message'=>"Order Status successfully created",
                    'data'=>array()
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to create Order Status!',
                'data' => $validator->getMessageBag()->toArray()
            );
        }*/
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
                    'message'=>"Order Status successfully updated",
                    'data'=>array()
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
        return array(
            'status'=>1,
            'message'=>"Not allowed!",
            'data'=>array()
        );
        /*if(OrderStatus::destroy($id)){
            return array(
                'status'=>1,
                'message'=>"Order status successfully removed",
                'data'=>array()
            );
        }else{
            return array(
                'status'=>0,
                'message'=>"Failed to remove order status!",
                'data'=>array()
            );
        }*/
    }

    /**
     * Get all order statuses
     * @param Request $request
     */
    public function getAllOrderStatuses(Request $request)
    {
        $orderStatuses = OrderStatus::select(['id','order_status'])->orderBy('id','ASC')->get();

        if($orderStatuses->count() > 0){
            return array(
                'status' => 1,
                'message' => "",
                "data" => $orderStatuses
            );
        }else{
            return array(
                'status'=>1,
                'message'=>"No Order Statuses created yet!",
                "data"=>array()
            );
        }
    }
}
