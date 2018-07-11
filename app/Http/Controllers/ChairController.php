<?php

namespace App\Http\Controllers;

use App\Chair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChairController extends Controller
{

    public function __construct() { $this->middleware('jwt.auth'); }

    /**
     * Display a listing of the chair.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new chair.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created chair in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'chair' => 'required',
            'chair_type_id' => 'required|int',
            'account_id' => 'required|int',
            'created_by'=>'required|int'
        ]);

        if ($validator->passes()) {

            //$image = $request->file('chair_image');
            $image = $_FILES['image_url']['tmp_name'];
            $imagePath = public_path('uploads/chairs')."/temp.jpeg";

            $uploadSuccess = move_uploaded_file($_FILES['image_url']['tmp_name'], $imagePath);
            if($uploadSuccess){
                $type =
                $base64 = 'data:image/jpeg;base64,' .base64_encode(file_get_contents($imagePath));
                $chair = new Chair();
                $chair->chair = ucwords(strtolower($request->chair));
                //TODO: Upload and possibly resize image

                $chair->image_url = $base64;
                $chair->chair_type_id = $request->chair_type_id;
                $chair->account_id = $request->account_id;
                $chair->created_by = $request->created_by;

                $result = $chair->save();

                if ($result) {
                    return array(
                        'status' => 1,
                        'message' => "Chair successfully created",
                        'data' => array()
                    );
                }
            }else{
                return array(
                    'status' => 0,
                    'message' => "Failed to upload file!",
                    'data' => array()
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to create Chair! '.json_encode(array("chair_type_id"=>$request->chair_type_id,"account_id"=>$request->account_id,"user_id"=>$request->created_by)),
                'data' => $validator->getMessageBag()->toArray()
            );
        }
    }

    /**
     * Display the specified chair.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chair = Chair::find($id)
            ->select(['chairs.id','chair','status','chair_type_id','chair_type'])
            ->leftJoin('chair_types','chair_types.id','=','chairs.chair_type_id')->get();

        return $chair;
    }

    /**
     * Show the form for editing the specified chair.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified chair in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'chair' => 'required',
            'chair_type_id' => 'required|int',
            'status' => 'required|int',
            'account_id' => 'required|int',
            'updated_by'=>'required|int'
        ]);

        if ($validator->passes()) {
            $chair = Chair::findOrFail($id);

            $chair->chair = ucwords(strtolower($request->chair));
            $chair->image_url = "/chairs/sofa.png";
            $chair->chair_type_id = $request->chair_type_id;
            $chair->status = $request->status;
            $chair->updated_by = $request->updated_by;

            $result = $chair->save();

            if($result){
                return array(
                    'status'=>1,
                    'message'=>"Chair successfully updated",
                    'data'=>array()
                );
            }

        } else {
            return array(
                'status' => 0,
                'message' => 'Failed to update Chair!',
                'data' => $validator->getMessageBag()->toArray()
            );
        }
    }

    /**
     * Remove the specified chair from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Chair::destroy($id)){
            return array(
                'status'=>1,
                'message'=>"Chair successfully removed",
                'data'=>array()
            );
        }else{
            return array(
                'status'=>0,
                'message'=>"Failed to remove chair!".$id,
                'data'=>array()
            );
        }
    }

    /**
     * Get all chairs
     * @param Request $request
     */
    public function getAllChairs(Request $request)
    {
        $accountId = $request->account_id;
        $chairs = Chair::select(['chairs.id','chair','chair_type',
            'image_url',
            'chair_type','chair_type_id'])
            ->leftJoin('chair_types','chair_types.id','=','chairs.chair_type_id')
            ->where('chairs.account_id','=',$accountId)
            ->where('chairs.status','=',ACTIVE)->get();

        if($chairs->count() > 0){
            return array(
                'status' => 1,
                'message' => "",
                "data" => $chairs
            );
        }else{
            return array(
                'status'=>1,
                'message'=>"No Chairs created yet!",
                "data"=>array()
            );
        }
    }
}
