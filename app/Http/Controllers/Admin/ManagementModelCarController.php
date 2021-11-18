<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ModelCar\Model_cars;
use App\Models\ModelCar\Sub_model_cars;
use App\Models\Dealer\Dealers;
use DB;

class ManagementModelCarController extends Controller
{
  public function index()
  {
    return view('admin.modeltype.index');
  }

  public function ShowModelType()
  {
    $sql = Model_cars::select('model_cars.id',DB::raw("sub_model_cars.id AS id"),'model_car_name','sub_model_car_name',DB::raw("CONCAT(model_cars.id,'_',model_car_name) AS set_name"))
                      ->leftJoin('sub_model_cars','model_cars.id','=','sub_model_cars.model_car_id')
                      ->where('model_cars.model_car_status','=',1)
                      ->where('sub_model_cars.sub_model_car_status','=',1)
                      ->get();
    return datatables($sql)->toJson();
  }

  public function GetModelType(Request $request)
  {
    $sql = Model_cars::select('model_cars.id',DB::raw("sub_model_cars.id AS sub_model_cars_id"),'model_car_name','sub_model_car_name',DB::raw("CONCAT(model_cars.id,'_',model_car_name) AS set_name"))
                      ->leftJoin('sub_model_cars','model_cars.id','=','sub_model_cars.model_car_id')
                      ->where('model_cars.model_car_status','=',1)
                      ->where('sub_model_cars.sub_model_car_status','=',1);
                      // print_r($sql);die;.
    if ($request->type == 'model') {
      $sql = $sql->where('model_cars.id','=',$request->id);
    }else { //sub
      $sql = $sql->where('sub_model_cars.id','=',$request->id);
    }
    // print_r($sql->orderBy('model_car_name','ASC')->get()->toArray());die;
    // $res = $sql->orderBy('model_car_name','ASC')->get()->toArray;
    return Response()->json(array('status' => 'success','data' => $sql->orderBy('model_car_name','ASC')->get()->toArray()));
  }

  public function UpdateModelType(Request $request)
  {
    if ($request->type == 'model') {
      if ($request->type_process == 'update') {

        Model_cars::where('id','=',$request->model_id)->update(array('model_car_name' => $request->model_car_name ));
        Sub_model_cars::where('model_car_id','=',$request->model_id)->update(array('sub_model_car_status' => 0 ));
        foreach ($request->data as $value) {
          $data = array(
                        'sub_model_car_name' => $value['sub_model_car_name'],
                        'model_car_id' => $value['model_id'],
                        'sub_model_car_status' => 1
                      );

          if ($value['type'] == 'new') {
            Sub_model_cars::Insert($data);
          }else {
            Sub_model_cars::where('id','=',$value['sub_model_id'])->update($data);
          }
          // print_r($data);

        }
        // print_r($request->all());die;
      }else {//delete
        Model_cars::where('id','=',$request->model_id)->update(array('model_car_status' => 0 ));
        Sub_model_cars::where('model_car_id','=',$request->model_id)->update(array('sub_model_car_status' => 0 ));
      }
    }else {//sub
      if ($request->type_process == 'update') {
         // print_r($request->all());die;
         Sub_model_cars::where('id','=',$request->sub_model_id)->update(array('sub_model_car_name' =>  $request->sub_model_car_name));
        // code...
      }else {//delete
        Sub_model_cars::where('id','=',$request->sub_model_id)->update(array('sub_model_car_status' => 0 ));
      }
    }
    return  Response()->json(array('status' => 'success','message' => ''));
  }

  public function InsertModelType(Request $request)
  {
    $Model_cars = Model_cars::where('model_car_name','=',$request->modelName)->get()->toArray();
    if (count($Model_cars) == 0) {

      $id = Model_cars::insertGetId(['model_car_name' => $request->modelName]);
      $sub = array();
      foreach ($request->typeName as $value) {
        $set = array(
                      'model_car_id' => $id,
                      'sub_model_car_name' => $value
                    );
        array_push($sub,$set);
      }
      Sub_model_cars::Insert($sub);
      return Response()->json(array('status' => 'success','message' => ''));

    }else {
       return Response()->json(array('status' => 'error','message' => 'กรุณาตรวจสอบข้อมูล Model Name เนื่องจากมีข้อมูลในระบบอยู่แล้ว'));
    }


  }
}
