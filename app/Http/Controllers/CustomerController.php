<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'account_id' => 'required|int',
            'created_by' => 'required|int'
        ]);

        if ($validator->passes()) {
            $pin = rand(1000, 9999);

            $customer = new Customer();
            $customer->first_name = ucwords(strtolower($request->first_name));
            $customer->last_name = ucwords(strtolower($request->last_name));
            $customer->phone = $request->phone;
            $email = $request->email;
            if(strlen(trim($email)) > 0){
                $customer->email = strtolower($email);
            }

            $customer->address = ucwords(strtolower($request->address));
            $customer->password = Hash::make($pin);
            $customer->account_id = $request->account_id;
            $customer->created_by = $request->created_by;

            $result = $customer->save();

            if ($result) {
                //TODO::Send SMS notification
                return array(
                    'status' => 1,
                    'message' => "Customer successfully created",
                    'data'=>null
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to create customer!',
                'data' => $validator->getMessageBag()->toArray()
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id)->select(['id', 'first_name', 'last_name', 'phone', 'email', 'address'])->get();

        return $customer;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'account_id' => 'required|int',
            'updated_by' => 'required|int'
        ]);

        if ($validator->passes()) {
            $customer = Customer::findOrFail($id);

            $customer->first_name = ucwords(strtolower($request->first_name));
            $customer->last_name = ucwords(strtolower($request->last_name));
            $customer->phone = $request->phone;
            $customer->email = strtolower($request->email);
            $customer->address = ucwords(strtolower($request->address));
            $customer->updated_by = $request->updated_by;

            $result = $customer->save();

            if ($result) {
                return array(
                    'status' => 1,
                    'message' => "Customer successfully updated",
                    'data'=>null
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to update customer!',
                'data' => $validator->getMessageBag()->toArray()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get all customers
     * @param Request $request
     */
    public function getAllCustomers(Request $request)
    {
        $accountId = $request->account_id;
        $customers = Customer::select(['id', 'first_name', 'last_name', 'phone', 'email', 'address'])->where('account_id', '=', $accountId)->get();

        if ($customers->count() > 0) {
            return array(
                'status' => 1,
                'message' => "",
                "data" => $customers
            );
        } else {
            return array(
                'status' => 1,
                'message' => "No customers created yet!",
                "data" => null
            );
        }
    }
}
