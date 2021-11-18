<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleDealerRequest;
use Illuminate\Http\Request;
use App\Event;
use App\SaleDealer;
use DOMDocument;
use Response;
use Sentinel;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Log;
use Redirect;
use Session;
use App\Http\Controllers\Admin\DealersController;
use Excel;
use App\Models\File_managers;
use App\Models\Dealer\Dealers;
use App\Models\Dealer\Dealer_details;
use App\Models\Dealer\Sale_dealers;
use App\Models\Event\Events;
use Illuminate\Support\Facades\File;

class SaleDealersController extends Controller
{
    public function index()
    {

        //return $id;
        Session::put('event_id', 1);

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$id)
            ->where('event_status','=',1)
            ->first();

        $dlr = DB::table('dealers')
            ->select('dealer_dlr as dlr_name')
            ->where('event_id','=',$id)
            ->where('dealer_status','=',1)
            ->get();

        return view('admin.sale_dealers.index',[
            'event' => $event,
            'dlr' => $dlr
        ]);
    }

    public function saleDealerIndex($id)
    {
        //return $id;
        Session::put('event_id', $id);

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$id)
            ->where('event_status','=',1)
            ->first();

        $dlr = DB::table('dealers')
            ->select('dealer_dlr as dlr_name')
            ->where('event_id','=',$id)
            ->where('dealer_status','=',1)
            ->get();

        $zones = DB::table('dealers')
            ->select('dealer_zone as zone_name')
            ->distinct('dealer_zone')
            ->where('event_id','=',$id)
            ->where('dealer_status','=',1)
            ->get();

        $areas = DB::table('dealers')
            ->select('dealer_area as area_name')
            ->distinct('dealer_area')
            ->where('event_id','=',$id)
            ->where('dealer_status','=',1)
            ->get();

            $dealer_legacy_code = DB::table('dealers')
                ->select(DB::raw('CONCAT(dealer_legacy_code," : ",dealer_name) AS name'),'id','dealer_legacy_code')
                ->where('event_id','=',$id)
                ->where('dealer_status','=',1)
                ->groupBy('dealer_legacy_code')
                ->get();

        return view('admin.sale_dealers.index',[
            'event' => $event,
            'dlr' => $dlr,
            'zones' => $zones,
            'areas' => $areas,
            'dealer_legacy_code' => $dealer_legacy_code
        ]);
    }

    public function InsertSaleDealer(Request $request)
    {
      // print_r($request->all());
      $check = Sale_dealers::where('sale_dealer_code','=',$request->add_sale_dealer_code)->get()->toArray();
      if (count($check) != 0) {
        return Response()->json(array('status' => 'error','messages' => 'Sale ID มีข้อมูลอยู่ในระบบอยู่แล้ว' ,'data' => $request->all() ));
      }else {
        $dealer = explode("_",$request->add_dealer_name);

        $detail = array(
          'dealer_id' => $dealer[0],
          'dealer_ids' => '',
          'dealer_legacy_code' => $dealer[1],
          'sale_dealer_code' => $request->add_sale_dealer_code,
          'sale_dealer_name' => $request->add_sale_dealer_name,
          'sale_dealer_nickname' => $request->add_sale_dealer_nickname,
          'sale_dealer_tel' => $request->add_sale_dealer_tel
        );

        Sale_dealers::Insert($detail);
      }

      return Response()->json(array('status' => 'success','messages' => '' ,'data' => $detail ));
      // print_r($detail);die;
    }

    //Table Data to index sale_dealer
    public function data(Request $request)
    {

        $query = DB::table('sale_dealers')
            ->join('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
            ->select(
                'sale_dealers.id as id',
                'sale_dealers.sale_dealer_code as code',
                'sale_dealers.sale_dealer_name as sale_dealer_name',
                'sale_dealers.sale_dealer_nickname as nickname',
                'sale_dealers.sale_dealer_tel as tel',
                'dealers.dealer_ids_code as ids',
                'dealers.dealer_legacy_code as legacy',
                'dealers.dealer_zone as zone',
                'dealers.dealer_area as area',
                'dealers.dealer_dlr as dlr',
                'dealers.dealer_name as dealer_name',
                'sale_dealers.sale_dealer_status as status',
                'dealers.event_id as event_id'
            )
            ->where('sale_dealers.sale_dealer_status','=',1);


        if(isset($request->event_id)){
            $event_id = $request->event_id;

            $query->where('dealers.event_id','=',$request->event_id);
        }


        if(isset($request->text_search) && ($request->text_search != '')){
            $query->where([
                ['sale_dealers.sale_dealer_code','like','%'.$request->text_search.'%','or'],
                ['sale_dealers.sale_dealer_name','like','%'.$request->text_search.'%','or'],
                ['sale_dealers.sale_dealer_nickname','like','%'.$request->text_search.'%','or'],
                ['sale_dealers.sale_dealer_tel','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_ids_code','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_legacy_code','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_zone','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_area','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_dlr','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_name','like','%'.$request->text_search.'%','or']
            ]);
        }
        if(isset($request->dlr_search)){
            $query->where('dealers.dealer_dlr','like',$request->dlr_search);
        }
        if(isset($request->zone_search)){
            $query->where('dealers.dealer_zone','like',$request->zone_search);
        }
        if(isset($request->area_search)){
            $query->where('dealers.dealer_area','like',$request->area_search);
        }

        $query->orderby('sale_dealers.created_at','desc');
        $sale_dealer = $query->get();

        return DataTables::of($sale_dealer)
            ->addColumn('actions', function ($data) {
                /*
                $actions = '<a href=' . route('admin.sale_dealers.edit', $data->id) . '><i class="livicon" data-name="pen" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="แก้ไข Sale Dealer"></i></a>';

                $actions = '<a href=' . route('admin.sale_dealers.edit', $data->id) . ' data-toggle="modal" data-target="#edit_sale_dealer"><i class="fa fa-cog" aria-hidden="true" style="color:#000;"></i></a>';
                */


                //$actions = '<a href="#" data-toggle="modal"><i class="livicon deleteSaleDealer" data-name="trash" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" data-saledealerid="'.$data->id.'" title="ลบ Sale Dealer"></i></a>';

                $actions = '<a href="#" data-toggle="modal"><i class="fa fa-cog editSaleDealer" aria-hidden="true" style="color:#000;" title="แก้ไข Sale Dealer"
                    data_id="'.$data->id.'"
                    data_code="'.$data->code.'"
                    data_sale_dealer_name="'.$data->sale_dealer_name.'"
                    data_nickname="'.$data->nickname.'"
                    data_tel="'.$data->tel.'"
                    data_ids="'.$data->ids.'"
                    data_legacy="'.$data->legacy.'"
                    data_zone="'.$data->zone.'"
                    data_area="'.$data->area.'"
                    data_dlr="'.$data->dlr.'"
                    data_dealer_name="'.$data->dealer_name.'" ></i></a>';

                /*
                $actions = '<a href=' . route('admin.sale_dealers.edit', $data->id) . '><i class="fa fa-cog" aria-hidden="true" style="color:#000;" title="แก้ไข Sale Dealer"></i></a>';
                */



                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function edit(SaleDealer $sale_dealer)
    {
        //return $sale_dealer;

        $sale_dealer_info = DB::table('sale_dealers')
            ->join('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
            ->select(
                'sale_dealers.id as id',
                'sale_dealers.sale_dealer_code as sale_dealer_code',
                'sale_dealers.sale_dealer_name as sale_dealer_name',
                'sale_dealers.sale_dealer_nickname as sale_dealer_nickname',
                'sale_dealers.sale_dealer_tel as sale_dealer_tel',
                'dealers.dealer_ids_code as dealer_ids_code',
                'dealers.dealer_legacy_code as dealer_legacy_code',
                'dealers.dealer_zone as dealer_zone',
                'dealers.dealer_area as dealer_area',
                'dealers.dealer_dlr as dealer_dlr',
                'dealers.dealer_name as dealer_name'
            )
            ->where('sale_dealers.id','=',$sale_dealer->dealer_id)
            ->first();

        return view('admin.sale_dealers.edit', [
            'event_id' => Session::get('event_id'),
            'sale_dealer' => $sale_dealer_info
        ]);
    }

    public function download()
    {
        return response()->download(storage_path("app/public/download_template/sale_dealer_template.xlsx"));
    }

    public function import()
    {
        return 'import';
    }


    //Update
    public function update(Request $request, SaleDealer $sale_dealer)
    {
        //return $sale_dealer;

        $event_id = Session::get('event_id');

        $sale_dealer->sale_dealer_name = $request->sale_dealer_name;
        $sale_dealer->sale_dealer_nickname = $request->sale_dealer_nickname;
        $sale_dealer->sale_dealer_tel = $request->sale_dealer_tel;
        $sale_dealer->updated_by = Sentinel::getUser()->id;

        if ($sale_dealer->update()) {
            $properties = array(
                'id' => $sale_dealer->id,
                'sale_dealer_name' => $request->sale_dealer_name,
                'sale_dealer_nickname' => $request->sale_dealer_nickname,
                'sale_dealer_tel' => $request->sale_dealer_tel,
            );

            $full_name = Sentinel::getUser()->full_name;

            //Activity log
            activity($full_name)
               ->performedOn($sale_dealer)
               ->causedBy($sale_dealer)
               ->withProperties($properties)
               ->log('Update Sale Dealer');
            //activity log ends

            return redirect('admin/events/'.$event_id.'/sale_dealers')->with('response_success', trans('sale_dealers/message.success.update'));
        }
        else{
            return redirect('admin/events/'.$event_id.'/sale_dealers')->withInput()->with('response_error', trans('sale_dealers/message.error.update'));
        }
    }

    public function dataExcel(Request $request)
    {
      $data = array();
      $dateList = array();
      $detailList = array();

      $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw","XLSX");
      $result = array($request->file('file')->getClientOriginalExtension());

      if (in_array($result[0],$extensions)) {
        $array = Excel::toArray(null,$request->file('file'));
        // print_r($array);die;
        $Dealers = new DealersController;

        $data = array();
      //
        for ($i=0; $i < count($array[0]); $i++) {
          if ($i != 0) {

            $detail = array(
              'dealer_ids' => $array[0][$i][0],
              'dealer_legacy_code' => $array[0][$i][1],
              'sale_dealer_code' => $array[0][$i][2],
              'sale_dealer_name' => $array[0][$i][3],
              'sale_dealer_nickname' => $array[0][$i][4],
              'sale_dealer_tel' => $array[0][$i][5]
            );
            $data[$array[0][$i][1]][] = $detail;
          }
        }
        // print_r($data);die;


        $data_dealer = array();
        $dealer_id = array();
        $Dealers = Dealers::where('event_id','=',$request->event_id)->where('dealer_status','=',1)->get()->toArray();


        foreach ($Dealers as $Dealers_value) {
          $de = array();
          array_push($dealer_id,$Dealers_value['id']);
          // print_r($Dealers_value);
          foreach ($data as $key => $value) {
            // print_r($Dealers_value['dealer_legacy_code'].'-'.$key);
            if ($Dealers_value['dealer_legacy_code'] == $key) {
              // print_r('Y');
              foreach ($value as $value_value) {
                $del = $value_value;
                $del['dealer_id'] = $Dealers_value['id'];
                array_push($de,$del);
              }
            }

          }
// print_r($data);die;
          //
          // if (count($de) == 0) {
          //   return Response()->json(array('status' => 'error','messages' => 'ไม่พบข้อมูล Dealer Legacy Code : '.$Dealers_value['dealer_legacy_code'] ));
          // }else
          if (count($de) != 0){
            array_push($data_dealer,$de);
          }

          // print_r($data_dealer);die;

        }

        if (count($data_dealer) == 0) {
          return Response()->json(array('status' => 'error','messages' => 'ไม่พบข้อมูล Dealer Legacy Code '));
        }

        $List = array();
        foreach ($data_dealer as $key => $value) {
          foreach ($value as $value_value) {
            array_push($List,$value_value);
          }
        }
        // Sale_dealers::whereIn('dealer_id',$dealer_id)->update(array('dealer_status' => 0 ));

// print_r($List);die;
        // array_push($data,$dateList);
        // array_push($data,$detailList);
        return Response()->json(array('status' => 'success','messages' => '' ,'data' => $List ));
      }else {
        return Response()->json(array('status' => 'error','messages' => 'กรุณาอัพโหลดไฟล์นามสกุล .xlsx' ));
      }
    }


    public function SentDataExcel(Request $request)
    {
      $data = array();
      $dateList = array();
      $detailList = array();

      $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw","XLSX");
      $result = array($request->file('file')->getClientOriginalExtension());

      if (in_array($result[0],$extensions)) {
        $array = Excel::toArray(null,$request->file('file'));
        $Dealers = new DealersController;

        $data = array();
      //
        for ($i=0; $i < count($array[0]); $i++) {
          if ($i != 0) {

            $detail = array(
              'dealer_ids' => $array[0][$i][0],
              'dealer_legacy_code' => $array[0][$i][1],
              'sale_dealer_code' => $array[0][$i][2],
              'sale_dealer_name' => $array[0][$i][3],
              'sale_dealer_nickname' => $array[0][$i][4],
              'sale_dealer_tel' => $array[0][$i][5],
              'sale_dealer_status' => 1
            );
            $data[$array[0][$i][1]][] = $detail;
          }
        }


        $data_dealer = array();
        $dealer_id = array();
        $Dealers = Dealers::where('event_id','=',$request->event_id)->where('dealer_status','=',1)->get()->toArray();


        foreach ($Dealers as $Dealers_value) {
          $de = array();
          array_push($dealer_id,$Dealers_value['id']);
          // print_r($Dealers_value);
          foreach ($data as $key => $value) {
            // print_r($Dealers_value['dealer_legacy_code'].'-'.$key);
            if ($Dealers_value['dealer_legacy_code'] == $key) {
              // print_r('Y');
              foreach ($value as $value_value) {
                $del = $value_value;
                $del['dealer_id'] = $Dealers_value['id'];
                array_push($de,$del);
              }
            }else {

            }

          }

          //
          // if (count($de) == 0) {
          //   return Response()->json(array('status' => 'error','messages' => 'ไม่พบข้อมูล Dealer Legacy Code : '.$Dealers_value['dealer_legacy_code'] ));
          // }else
          if (count($de) != 0){
            array_push($data_dealer,$de);
          }
        }

        if (count($data_dealer) == 0) {
          return Response()->json(array('status' => 'error','messages' => 'ไม่พบข้อมูล Dealer Legacy Code '));
        }

// print_r($data_dealer);die;
        $List = array();
        foreach ($data_dealer as $key => $value) {
          foreach ($value as $value_value) {
            array_push($List,$value_value);
          }
        }

        Sale_dealers::whereIn('dealer_id',$dealer_id)->update(array('sale_dealer_status' => 0 ));
        $sales = Sale_dealers::whereIn('dealer_id',$dealer_id)->get()->toArray();
        if (count($sales) == 0) {
          Sale_dealers::Insert($List);
        }else {

          foreach ($List as $List_value) {
            $a = array();
            foreach ($sales as $sales_value) {
              if ($sales_value['dealer_id'] == $List_value['dealer_id'] && $sales_value['sale_dealer_code'] == $List_value['sale_dealer_code'] ) {
                array_push($a,$List_value);
              }
            }

            if (count($a) != 0) {
              Sale_dealers::where('dealer_id','=',$a[0]['dealer_id'])->where('sale_dealer_code','=',$a[0]['sale_dealer_code'])->update($a[0]);
            }else {
              Sale_dealers::Insert($List_value);
            }
          }
          // foreach ($sales as $sales_value) {
          //   foreach ($List as $List_value) {
          //     if ($sales_value['dealer_id'] == $List_value['dealer_id'] && $sales_value['sale_dealer_code'] == $List_value['sale_dealer_code'] ) {
          //       // code...
          //     }
          //   }
          // }
        }
        // Sale_dealers::whereIn('dealer_id',$dealer_id)->update(array('dealer_status' => 0 ));
        $DealersC = new DealersController;
        $file_id = $DealersC->MoveFile($request);
        Events::where('id','=',$request->event_id)->update(array( 'file_sale_dealer_id' => $file_id));

        return Response()->json(array('status' => 'success','messages' => '' ,'data' => $List ));
      }else {
        return Response()->json(array('status' => 'error','messages' => 'กรุณาอัพโหลดไฟล์นามสกุล .xlsx' ));
      }
    }

    /*
    public function getZone(Request $request)
    {
        $zones = DB::table('dealers')
            ->select('dealer_zone as zone_name')
            ->distinct('dealer_zone')
            ->where('event_id','=',$request->event_id)
            ->where('dealer_dlr','=',$request->dlr)
            ->where('dealer_status','=',1)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($zones as $zone){
            $output.= '<option value="'.$zone->zone_name.'">'.$zone->zone_name.'</option>';
        }

        echo $output;
    }

    public function getArea(Request $request)
    {
        $areas = DB::table('dealers')
            ->select('dealer_area as area_name')
            ->distinct('dealer_area')
            ->where('event_id','=',$request->event_id)
            ->where('dealer_dlr','=',$request->dlr)
            ->where('dealer_zone','=',$request->zone)
            ->where('dealer_status','=',1)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($areas as $area){
            $output.= '<option value="'.$area->area_name.'">'.$area->area_name.'</option>';
        }

        echo $output;
    }
    */

}
