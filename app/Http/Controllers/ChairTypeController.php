<?php

namespace App\Http\Controllers;

use App\ChairType;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChairTypeController extends Controller
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
            'chair_type' => 'required',
            'account_id' => 'required|int',
            'created_by'=>'required|int'
        ]);

        if ($validator->passes()) {

            $chairType = new ChairType();
            $chairType->chair_type = ucwords(strtolower($request->chair_type));
            $chairType->account_id = $request->account_id;
            $chairType->created_by = $request->created_by;

            $result = $chairType->save();

            if($result){
                return array(
                    'status'=>1,
                    'message'=>"Chair Type successfully created"
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to create Chair Type!',
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
        $chairType = ChairType::find($id)->select(['id','chair_type','status'])->get();

        return $chairType;
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
            'chair_type' => 'required',
            'status' => 'required|int',
            'account_id' => 'required|int',
            'updated_by'=>'required|int'
        ]);

        if ($validator->passes()) {
            $chairType = ChairType::findOrFail($id);

            $chairType->chair_type = ucwords(strtolower($request->chair_type));
            $chairType->status = $request->status;
            $chairType->updated_by = $request->updated_by;

            $result = $chairType->save();

            if($result){
                return array(
                    'status'=>1,
                    'message'=>"Chair Type successfully updated"
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to update Chair Type!',
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
     * Get all chair types
     * @param Request $request
     */
    public function getAllChairTypes(Request $request)
    {
        $accountId = $request->account_id;
        $chairTypes = ChairType::select(['id','chair_type'])
            ->where('account_id','=',$accountId)
            ->where('status','=',ACTIVE)->get();

        if($chairTypes->count() > 0){
            return array(
                'status' => 1,
                'message' => "",
                "data" => $chairTypes
            );
        }else{
            return array(
                'status'=>1,
                'message'=>"No Chair Types created yet!",
                "data"=>null
            );
        }
    }
}
