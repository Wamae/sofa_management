<?php

namespace App\Http\Controllers;

use App\Account;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $result = false;
        DB::beginTransaction();

        $account = null;
        $user = null;

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
            $user->name = ucwords(strtolower($request->name));
            $user->first_name = ucwords(strtolower($request->first_name));
            $user->last_name = ucwords(strtolower($request->last_name));
            $user->email = strtolower($request->email);
            $user->phone = $request->phone;
            $user->address = ucwords(strtolower($request->address));
            $user->password = Hash::make($request->password);
            $user->account_id = $account->id;

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
            $token = $user->createToken("MyApp")->accessToken;

            return array("status" => $result, "type" => "success", "message" => "Account creation Successful!",
                "data" => array("account" => $account->account, "username" => $user->name,"account_id"=>$account->id,
                    "user_id"=>$user->id,"token"=>$token)
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

    /**
     * Login api
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        if (Auth::attempt(["email" => request("user_name"), "password" => request("password")]) || Auth::attempt(["name" => request("user_name"), "password" => request("password")])) {
            $user = Auth::user();
            $token = $user->createToken("MyApp")->accessToken;
            return response()->json(["status"=>1,"message"=>"Logged in successfully!","data" => array("user_id"=>$user->id,"account_id"=>$user->account_id,"token"=>$token)], 200);
        } else {
            return response()->json(["status" => 0,"message"=>"Unauthorized","data"=>null],401);
        }
    }

}
