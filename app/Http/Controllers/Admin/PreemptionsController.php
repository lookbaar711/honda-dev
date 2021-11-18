<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CheckInCheckOut;
use DOMDocument;
use Response;
use Sentinel;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Log;
use Redirect;
use Session;
use App\Models\Dealer\Dealers;
use App\Models\Dealer\Dealer_details;
use App\Models\Preemptions\Preemptions_details;
use App\Models\Preemption\Preemptions;
use App\Models\Event\Events;
use App\Models\Dealer\Sale_dealers;

class PreemptionsController extends Controller
{
    public function index()
    {
        return 'Preemptions';
    }

    public function preemptionIndex($id)
    {
        Session::put('event_id', $id);

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$id)
            ->first();
        $preemption_type = Preemptions_details::select('preemption_type')->where('event_id','=',$id)->groupBy('preemption_type')->get()->toArray();
        $dealer_zone = Dealers::select('dealer_zone')->where('event_id','=',$id)->groupBy('dealer_zone')->get()->toArray();
        $dealer_area = Dealers::select('dealer_area')->where('event_id','=',$id)->groupBy('dealer_area')->get()->toArray();
        $dealer_dlr = Dealers::select('dealer_dlr')->where('event_id','=',$id)->groupBy('dealer_dlr')->get()->toArray();
        // $dealer_type = Dealers::select('dealer_zone')->where('event_id','=',$id)->groupBy('dealer_zone')->get()->toArray();
        return view('admin.preemptions.index',[
            'event' => $event,
            'preemption_type' => $preemption_type,
            'dealer_zone' => $dealer_zone,
            'dealer_area' => $dealer_area,
            'dealer_dlr' => $dealer_dlr
        ]);
    }

    public function getSettingPreemption($id)
    {
      $event = DB::table('events')
          ->select('id','event_name')
          ->where('id','=',$id)
          ->first();

      return view('admin.preemptions.setting.index',[
          'event' => $event
      ]);
    }

    public function getPreAll(Request $request)
    {
      $pree = Preemptions_details::select(
                                          'preemptions_details.id',
                                          DB::raw('IF(preemptions_details.preemption_type="TB",CONCAT("TB",preemptions_details.preemption_no),preemptions_details.preemption_no) AS preemption_no'),
                                          'preemptions_details.preemption_type',
                                          'dealers.dealer_dlr',
                                          'dealers.dealer_zone',
                                          'dealers.dealer_area',
                                          'sale_dealers.sale_dealer_code',
                                          'sale_dealers.sale_dealer_name',
                                          'preemptions_details.preemption_status',
                                          'preemptions_details.request_at',
                                          'preemptions_details.response_at',
                                          'preemptions_details.updated_at',
                                          'model_cars.model_car_name',
                                          'sub_model_cars.sub_model_car_name'
                                          )
                                    ->leftJoin('sale_dealers','preemptions_details.sale_dealer_id','=','sale_dealers.id')
                                    ->leftJoin('dealers','sale_dealers.dealer_id','=','dealers.id')
                                    ->leftJoin('model_cars','preemptions_details.model_car_id','=','model_cars.id')
                                    ->leftJoin('sub_model_cars','preemptions_details.sub_model_car_id','=','sub_model_cars.id');
        switch ($request->preemption_status) {
          case 0:
              $pree = $pree->where('preemptions_details.preemption_status','<>',4);
            break;
          case 1:
                  $pree = $pree->where('preemptions_details.preemption_status','=',$request->preemption_status)->whereRaw("request_at LIKE CONCAT(CURRENT_DATE,'%')");
          break;
          case 2:
                $pree = $pree->whereIn('preemptions_details.preemption_status',[2,3])->whereRaw("response_at LIKE CONCAT(CURRENT_DATE,'%')");
          break;

          default:
              $pree = $pree->where('preemptions_details.preemption_status','=',$request->preemption_status);
            break;
        }
        $pree = $pree->where('preemptions_details.event_id','=',$request->event_id)->get();
      return datatables($pree)->toJson();
    }

    public function getSale(Request $request)
    {
      $data = DB::table('checkin_checkout')
                                  ->select(
                                        'sale_dealers.id AS sale_dealers_id',
                                        'dealers.id AS dealers_id',
                                        'sale_dealers.sale_dealer_code',
                                        'sale_dealers.sale_dealer_name',
                                        'sale_dealers.sale_dealer_nickname',
                                        'sale_dealers.sale_dealer_tel'
                                      )
                                ->leftJoin('sale_dealers','checkin_checkout.sale_dealer_id','=','sale_dealers.id')
                                ->leftJoin('dealers','sale_dealers.dealer_id','=','dealers.id')
                                ->where('checkin_checkout.event_date' ,'=', date("Y-m-d") )
                                ->where('sale_dealers.sale_dealer_code' ,'=', $request->sale_code)
                                ->where('checkin_checkout.event_id' ,'=', $request->event_id)
                                ->get()->toArray();

      if (count($data) != 0) {
        $sale = array(
          'sale_dealers_id' => $data[0]->sale_dealers_id,
          'dealers_id' => $data[0]->dealers_id,
          'sale_dealer_code' => $data[0]->sale_dealer_code,
          'sale_dealer_name' => $data[0]->sale_dealer_name,
          'sale_dealer_nickname' => $data[0]->sale_dealer_nickname,
          'sale_dealer_tel' => $data[0]->sale_dealer_tel,
        );
        return Response()->json(array('status' => 1,'message' => '','data' => $sale));
      }else {
         return Response()->json(array('status' => 0,'message' => 'กรุณาตรวจสอบข้อมูล','data' => $data));
      }
    }

    public function getSaleAndPre(Request $request)
    {
      $prede = Preemptions_details::select(
                                    'preemptions_details.id',
                                    'sale_dealers.id AS sale_dealers_id',
                                    DB::raw('IF(preemptions_details.preemption_type="TB",CONCAT("TB",preemptions_details.preemption_no),preemptions_details.preemption_no) AS preemption_no'),
                                    'sale_dealers.sale_dealer_code',
                                    'sale_dealers.sale_dealer_name',
                                    'sale_dealers.sale_dealer_nickname',
                                    'sale_dealers.sale_dealer_tel',
                                    'preemptions_details.preemption_status',
                                    DB::raw('IF(preemptions_details.preemption_type="TB","Turbo",preemptions_details.preemption_type) AS preemption_type'),
                                    'preemptions_details.request_at'
                                  )
                                    ->leftJoin('sale_dealers','preemptions_details.sale_dealer_id','=','sale_dealers.id')
                                    ->where('preemptions_details.event_id','=',$request->event_id)
                                    ->where('sale_dealers.sale_dealer_code','=',$request->sale_code)
                                    ->where('preemptions_details.preemption_status','=',1)
                                    ->get()->toArray();
      if (count($prede) != 0) {
        return Response()->json(array('status' => 1,'message' => '','data' => $prede));
      }else {
        return Response()->json(array('status' => 0,'message' => 'กรุณาตรวจสอบข้อมูล ID Sale','data' => $prede));
      }
        // return datatables($prede)->toJson();

    }

    public function UpdatePreemptionData(Request $request)
    {
      //return Response()->json(array('status' => 'error','messages' => $request->running[1].' -- '.$request->model_car[1].' -- '.$request->sub_model_car[1]));


      $number_normal = array();
      $number_turbo = array();
      $preemption_select = array();

      $turbo_data = array();
      $normal_data = array();

      $date = date('Y-m-d H:i:s');
      switch ($request->preemption_status) {
        case 1: // เบิกใขจอง

        $i = 0;
        foreach ($request->running as $value) {
          $data = array();
          $set = explode("TB",$value);
          // print_r($set[0]);
          if (count($set) != 2) {
            array_push($number_normal,$set[0]);
            array_push($normal_data,[$request->model_car[$i], $request->sub_model_car[$i]]);

          }else {
            array_push($number_turbo,$set[1]);
            array_push($turbo_data,[$request->model_car[$i], $request->sub_model_car[$i]]);
          }
          $i++;
        }

        $prede_t = Preemptions_details::select('id')
                                        ->whereIn('preemption_no',$number_turbo)
                                        ->where('preemption_type','=','TB')
                                        ->where('event_id','=',$request->event_id)
                                        ->where('preemption_status','=',0)
                                        ->get()
                                        ->toArray();
        $prede_n = Preemptions_details::select('id')
                                        ->whereIn('preemption_no',$number_normal)
                                        ->where('preemption_type','=','NORMAL')
                                        ->where('event_id','=',$request->event_id)
                                        ->where('preemption_status','=',0)
                                        ->get()
                                        ->toArray();

          $sale = $request->sale;
          if (count($number_normal) == count($prede_n) && count($number_turbo) == count($prede_t) ) {

            $x = 0;
            $y = 0;
            foreach ($prede_n as $value) {
              $set_data = array(
                                  'sale_dealer_id' => $sale['sale_dealers_id'],
                                  'request_at' => $date,
                                  'preemption_status' => $request->preemption_status,
                                  'model_car_id' => $normal_data[$x][0],
                                  'sub_model_car_id' => $normal_data[$x][1]
                                );
              Preemptions_details::where('id','=',$value['id'])->update($set_data);
              $x++;
            }

            foreach ($prede_t as $value) {
              $set_data = array(
                                  'sale_dealer_id' => $sale['sale_dealers_id'],
                                  'request_at' => $date,
                                  'preemption_status' => $request->preemption_status,
                                  'model_car_id' => $turbo_data[$y][0],
                                  'sub_model_car_id' => $turbo_data[$y][1]
                                );
              Preemptions_details::where('id','=',$value['id'])->update($set_data);
              $y++;
            }
          }else {
            return Response()->json(array('status' => 'error','messages' => 'กรุณาตรวจสอบข้อมูลเลขที่ใบจอง' ));
          }

          break;
        case 2:
            foreach ($request->data as $value) {
              if ($value['preemption_status'] != 1) {
                $set_data = array(
                                    'preemption_status' => $value['preemption_status']
                                  );
                if ($value['preemption_status']  == 2 || $value['preemption_status']  == 3 ) {
                  $set_data['response_at']=$date;
                }
                Preemptions_details::where('id','=',$value['id'])->update($set_data);
              }
            }
          break;

        default:
              if ($request->set_preemption_status == 0) {
                $set_data = array(
                                    'preemption_status' => $request->set_preemption_status,
                                    'sale_dealer_id'=> null,
                                    'request_at'=> null,
                                    'response_at'=> null,
                                  );
              }else {
                $set_data = array(
                                    'preemption_status' => $request->set_preemption_status
                                  );
                if ($request->set_preemption_status  == 2 || $request->set_preemption_status  == 3 ) {
                  $set_data['response_at']=$date;
                }else {
                  $set_data['response_at']=null;
                }

              }


                Preemptions_details::where('id','=',$request->id)->update($set_data);
          break;
      }

        return Response()->json(array('status' => 'success','messages' => 'บันทึกข้อมูลสำเร็จ' ));
    }

    // public function getSettingPreemptionData($value='')
    // {
    //   // code...
    // }

    public function getSettingPreemptionData($event_id)
    {
      $data = array();
      $detail_turbo = array();
      $detail_normal = array();
      $detail = array('start' => "",'end' => "");
      array_push($detail_turbo,$detail);
      array_push($detail_normal,$detail);

      $preemptions = Preemptions::where('event_id','=',$event_id)->where('status','=',1)->get()->toArray();
      foreach ($preemptions as $value) {
        $details = array('start' => $value['running_start'],'end' => $value['running_end']);
        if ($value['type'] == 'TB') {
          array_push($detail_turbo,$details);
        }else {
          array_push($detail_normal,$details);
        }
      }

      $data['Turbo']=$detail_turbo;
      $data['Normal']=$detail_normal;
      return Response()->json(array('data' => $data ));
    }

    public function InsertSettingPreemption(Request $request)
    {
      $data_turbo = array();
      $data_normal = array();
      $Turbo_detail = array();
      $Normal_detail = array();

      $Normal_detail_s = array();
      $Turbo_detail_s = array();

      foreach ($request->Turbo_detail as $value) {
        if ($value['start'] != "" && $value['end'] != "") {
            for ($i=($value['start']*1); $i <= ($value['end']*1) ; $i++) {
              array_push($Turbo_detail_s,sprintf('%08d',$i));
            }
        }
      }

      foreach ($request->Normal_detail as $value) {
        if ($value['start'] != "" && $value['end'] != "") {
            for ($i=($value['start']*1); $i <= ($value['end']*1) ; $i++) {
              array_push($Normal_detail_s,sprintf('%08d',$i));
            }
        }
      }

      foreach ($request->Turbo_detail as $value) {
        if ($value['start'] != "" && $value['end'] != "") {
          $set = array(
                        'event_id' => $request->event_id,
                        'type' => "TB",
                        'running_start' => $value['start'],
                        'running_end' => $value['end'],
                        'status' => 1,
                      );
          array_push($Turbo_detail,$set);
        }
      }

      foreach ($request->Normal_detail as $value) {
        if ($value['start'] != "" && $value['end'] != "") {
          $set = array(
                        'event_id' => $request->event_id,
                        'type' => "NORMAL",
                        'running_start' => $value['start'],
                        'running_end' => $value['end'],
                        'status' => 1,
                      );
          array_push($Normal_detail,$set);
        }
      }

      foreach ($Turbo_detail_s as $value) {
        $set = array(
          'event_id' => $request->event_id,
          'preemption_no' => $value,
          'preemption_type' => "TB",
          'preemption_status' => 0
         );
         array_push($data_turbo,$set);
      }

      foreach ($Normal_detail_s as $value) {
        $set = array(
          'event_id' => $request->event_id,
          'preemption_no' => $value,
          'preemption_type' => "NORMAL",
          'preemption_status' =>0
         );
         array_push($data_normal,$set);
      }

      $prees = Preemptions::where('event_id','=',$request->event_id)->get()->toArray();
      if (count($prees) == 0) {
        Preemptions::Insert($Turbo_detail);
        Preemptions::Insert($Normal_detail);
      }else {
        Preemptions::where('event_id','=',$request->event_id)->update(array('status' => 0 ));
        //Turbo
        foreach ($Turbo_detail as $Turbo_detail_value) {
          $arr = array();
          foreach ($prees as $prees_value) {
            if ($prees_value['running_start'] == $Turbo_detail_value['running_start'] && $prees_value['running_end'] == $Turbo_detail_value['running_end'] && $prees_value['type'] == $Turbo_detail_value['type']) {
              array_push($arr,$Turbo_detail_value);
            }
          }

          if (count($arr) == 0) {
            Preemptions::Insert($Turbo_detail_value);
          }else {
            Preemptions::where('event_id','=',$request->event_id)->where('running_start','=',$arr[0]['running_start'])->where('running_end','=',$arr[0]['running_end'])->where('type','=',$arr[0]['type'])->update($arr[0]);
          }

        }

        //Normal
        foreach ($Normal_detail as $Normal_detail_value) {
          $arr = array();
          foreach ($prees as $prees_value) {
            if ($prees_value['running_start'] == $Normal_detail_value['running_start'] && $prees_value['running_end'] == $Normal_detail_value['running_end'] && $prees_value['type'] == $Normal_detail_value['type']) {
              array_push($arr,$Normal_detail_value);
            }
          }

          if (count($arr) == 0) {
            Preemptions::Insert($Normal_detail_value);
          }else {
            Preemptions::where('event_id','=',$request->event_id)->where('running_start','=',$arr[0]['running_start'])->where('running_end','=',$arr[0]['running_end'])->where('type','=',$arr[0]['type'])->update($arr[0]);
          }

        }
      }


      // $preess = Preemptions::where('event_id','=',$request->event_id)->where('status' ,'=', 1)->get()->toArray();
      $prede = Preemptions_details::where('event_id','=',$request->event_id)->get()->toArray();
      // print_r($prede);die;
      Preemptions_details::where('event_id','=',$request->event_id)->update( array('preemption_status' =>  4));
      $No = array();
      if (count($prede) == 0) {
        $data_turbo_ = array_chunk($data_turbo, 500);
        $data_normal_ = array_chunk($data_normal, 500);
        foreach ($data_turbo_ as $key => $value) {
          Preemptions_details::Insert($value);
        }

        foreach ($data_normal_ as $key => $value) {
          Preemptions_details::Insert($value);
        }
        // Preemptions_details::Insert($data_turbo);
        // Preemptions_details::Insert($data_normal);
      }else {

        foreach ($data_turbo as $data_turboV) {
          $arr = array();
          foreach ($prede as $prede_value) {
            if ($prede_value['preemption_no'] == $data_turboV['preemption_no'] && $prede_value['preemption_type'] == $data_turboV['preemption_type']) {
              array_push($arr,$data_turboV);
            }
          }
          if (count($arr) == 0) {
            Preemptions_details::Insert($data_turboV);
          }else {
            Preemptions_details::where('event_id','=',$request->event_id)->where('preemption_no','=',$arr[0]['preemption_no'])->where('preemption_type','=',$arr[0]['preemption_type'])->update($arr[0]);
          }
        }


        foreach ($data_normal as $data_normalV) {
          $arr = array();
          foreach ($prede as $prede_value) {
            if ($prede_value['preemption_no'] == $data_normalV['preemption_no'] && $prede_value['preemption_type'] == $data_normalV['preemption_type']) {
              array_push($arr,$data_normalV);
            }
          }
          if (count($arr) == 0) {
            Preemptions_details::Insert($data_normalV);
          }else {
            Preemptions_details::where('event_id','=',$request->event_id)->where('preemption_no','=',$arr[0]['preemption_no'])->where('preemption_type','=',$arr[0]['preemption_type'])->update($arr[0]);
          }
        }



      }

      return Response()->json(array('status' => 1));

    }

    public function getExposePreemption($event_id)
    {
      $event = DB::table('events')
          ->select('id','event_name')
          ->where('id','=',$event_id)
          ->first();

      $model_cars = DB::table('model_cars')
          ->select('id','model_car_name')
          ->where('model_car_status','=',1)
          ->get();

      return view('admin.preemptions.expose.index',[
          'event' => $event,
          'model_cars' => $model_cars
      ]);
    }

    public function getReturnPreemption($event_id)
    {
      $event = DB::table('events')
          ->select('id','event_name')
          ->where('id','=',$event_id)
          ->first();

      return view('admin.preemptions.return.index',[
          'event' => $event
      ]);
    }

    public function getSubModel(Request $request)
    {
        $sub_model_cars = DB::table('sub_model_cars')
            ->select('id','sub_model_car_name')
            ->where('model_car_id','=',$request->model_car_id)
            ->where('sub_model_car_status','=',1)
            ->get();

        $output = '<option value="">- Type Name -</option>';

        foreach ($sub_model_cars as $sub_model_car){
            $output.= '<option value="'.$sub_model_car->id.'">'.$sub_model_car->sub_model_car_name.'</option>';
        }

        echo $output;
    }
}
