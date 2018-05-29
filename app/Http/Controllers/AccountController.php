<?php

namespace App\Http\Controllers;

use App\Account;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
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
     * Show the form for creating a new account.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created account in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO: make a transaction
        $result = false;
        DB::beginTransaction();

        try {
            $account = new Account();
            $account->company_name = strtoupper($request->company_name);
            $account->company_address = ucwords(strtolower($request->company_address));
            $account->company_phone = $request->company_phone;
            $account->save();

            $id = $account->id;
            //dd($account);
            $account->account = "SOFA: " . $id;
            $account->save();

            $user = new User();
            $user->name = $request->name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->password = Hash::make($request->password);

            $user->save();

            DB::commit();
            $result = true;

        } catch (\Exception $e) {
            $result = false;
            DB::rollback();

            return array("type" => "error", "message" => "Account creation failed!");
        }


        if ($result) {

            //TODO: Send an email and sms notification


            return array("status" => $result, "type" => "error", "message" => "Account creation failed!",
                "data" => array("account" => $account->account, "username" => $user->name)
            );
        }


    }

    /**
     * Display the specified account.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::find($id);

        return ($account) ? $account : array("type" => "info", "message" => "Account not found!");
    }

    /**
     * Show the form for editing the specified account.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified account in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified account from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
