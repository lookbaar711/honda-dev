<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DealerRequest;
use Illuminate\Http\Request;
use App\Event;
use App\Dealer;
use DOMDocument;
use Response;
use Sentinel;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Log;
use Redirect;
use Session;
use Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\File_managers;
use App\Models\Dealer\Dealers;
use App\Models\Dealer\Dealer_details;
use App\Models\Event\Events;
use Illuminate\Support\Facades\File;
class DealersController extends Controller
{
    public function index()
    {

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$id)
            ->where('event_status','=',1)
            ->first();

        $zones = DB::table('dealers')
            ->select('dealer_zone as zone_name')
            ->distinct('dealer_zone')
            ->where('event_id','=',$id)
            ->where('dealer_status','=',1)
            ->get();

        return view('admin.dealers.index',[
            'event' => $event,
            'zones' => $zones
        ]);
    }

    public function dealerIndex($id)
    {

        Session::put('event_id', $id);

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$id)
            ->where('event_status','=',1)
            ->first();

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

        return view('admin.dealers.index',[
            'event' => $event,
            'zones' => $zones,
            'areas' => $areas
        ]);
    }

    //Table Data to index dealer
    public function data(Request $request)
    {

        $query = DB::table('dealers')
            ->select(
                'id as id',
                'dealer_legacy_code as code',
                'dealer_ids_code as ids',
                'dealer_zone as zone',
                'dealer_area as area',
                'dealer_dlr as dlr',
                'dealer_name as name',
                'dealer_vip as vip',
                'dealer_press as press',
                'dealer_weekday as weekday',
                'dealer_weekend as weekend',
                'dealer_status as dealer_status',
                'event_id as event_id'
            )
            ->where('dealer_status','=',1);


        if(isset($request->event_id)){
            $event_id = $request->event_id;

            $query->where('event_id','=',$request->event_id);
        }


        if(isset($request->text_search)){
            $query->where([
                ['dealer_legacy_code','like','%'.$request->text_search.'%','or'],
                ['dealer_ids_code','like','%'.$request->text_search.'%','or'],
                ['dealer_dlr','like','%'.$request->text_search.'%','or'],
                ['dealer_name','like','%'.$request->text_search.'%','or'],
                ['dealer_vip','like','%'.$request->text_search.'%','or'],
                ['dealer_press','like','%'.$request->text_search.'%','or'],
                ['dealer_weekday','like','%'.$request->text_search.'%','or'],
                ['dealer_weekend','like','%'.$request->text_search.'%','or'],
                ['dealer_zone','like','%'.$request->text_search.'%','or'],
                ['dealer_area','like','%'.$request->text_search.'%','or']
            ]);
        }
        if(isset($request->zone_search)){
            $query->where('dealer_zone','like',$request->zone_search);
        }
        if(isset($request->area_search)){
            $query->where('dealer_area','like',$request->area_search);
        }

        $query->orderby('created_at','desc');
        $dealer = $query->get();

        return DataTables::of($dealer)
            ->editColumn('vip', function ($data) {
                return number_format($data->vip);
            })
            ->editColumn('press', function ($data) {
                return number_format($data->press);
            })
            ->editColumn('weekday', function ($data) {
                return number_format($data->weekday);
            })
            ->editColumn('weekend', function ($data) {
                return number_format($data->weekend);
            })
            ->addColumn('actions', function ($data) {

                //$actions = '<a href=' . route('admin.dealers.edit', $data->id) . '><i class="livicon" data-name="pen" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="แก้ไข Dealer"></i></a>';

                // $actions = '<a href="#" data-toggle="modal"><i class="livicon editDealer" data-name="pen" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="แก้ไข Dealer"
                //     data_id="'.$data->id.'"
                //     data_code="'.$data->code.'"
                //     data_ids="'.$data->ids.'"
                //     data_zone="'.$data->zone.'"
                //     data_area="'.$data->area.'"
                //     data_dlr="'.$data->dlr.'"
                //     data_dealer_name="'.$data->name.'"
                //     data_vip="'.$data->vip.'"
                //     data_press="'.$data->press.'"
                //     data_weekday="'.$data->weekday.'"
                //     data_weekend="'.$data->weekend.'" ></i></a>';

                // $actions .= '<a href="dealers/'.$data->id.'/dealer_details"><i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="จัดการ Dealer Detail"></i></a>';




                //<i class="fa fa-cog" aria-hidden="true" style="color: #000;"></i>
                //<button class="btn btn-sm button-test"><i class="fa fa-cog" aria-hidden="true"></i> จัดการ</button>

                $actions = '<div class="nav-item dropdown">
                            <button class="btn btn-sm button-test nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i> </button>
                                <div class="dropdown-menu">';
                $actions.= '<div style="text-align: left;" class="editDealer"
                                data-size="18" data-loop="true"
                                data_id="'.$data->id.'"
                                data_code="'.$data->code.'"
                                data_ids="'.$data->ids.'"
                                data_zone="'.$data->zone.'"
                                data_area="'.$data->area.'"
                                data_dlr="'.$data->dlr.'"
                                data_dealer_name="'.$data->name.'"
                                data_vip="'.$data->vip.'"
                                data_press="'.$data->press.'"
                                data_weekday="'.$data->weekday.'"
                                data_weekend="'.$data->weekend.'">
                                <a href="#" data-toggle="modal" class="dropdown-item">
                                <i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> แก้ไข Dealer</a>
                            </div>';
                $actions.= '<div style="text-align: left;">
                                <a class="dropdown-item" href="dealers/'.$data->id.'/dealer_details"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ Dealer Detail</a>
                            </div>';
                $actions.= '</div></div>';

                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function edit(Dealer $dealer)
    {
        //return Session::get('event_id');

        return view('admin.dealers.edit', [
            'event_id' => Session::get('event_id'),
            'dealer' => $dealer
        ]);
    }

    public function download()
    {
        return response()->download(storage_path("app/public/download_template/dealer_template.xlsx"));
    }

    public function import()
    {
        return 'import';
    }


    //Update
    public function update(DealerRequest $request, Dealer $dealer)
    {
        //return $request;

        //return $dealer;
        $dealer->dealer_zone = $request->dealer_zone;
        $dealer->dealer_area = $request->dealer_area;
        $dealer->dealer_dlr = $request->dealer_dlr;
        $dealer->dealer_name = $request->dealer_name;
        $dealer->dealer_vip = $request->dealer_vip;
        $dealer->dealer_press = $request->dealer_press;
        $dealer->dealer_weekday = $request->dealer_weekday;
        $dealer->dealer_weekend = $request->dealer_weekend;
        $dealer->updated_by = Sentinel::getUser()->id;

        if ($dealer->update()) {

            //update dealer detail
            //update type vip
            $update_dealer_detail_vip = DB::table('dealer_details')
                                        ->where('dealer_id', $dealer->id)
                                        ->where('dealer_detail_type', 'VIP')
                                        ->update(['dealer_detail_amount' => $request->dealer_vip]);

            //update type press
            $update_dealer_detail_press = DB::table('dealer_details')
                                        ->where('dealer_id', $dealer->id)
                                        ->where('dealer_detail_type', 'PRESS')
                                        ->update(['dealer_detail_amount' => $request->dealer_press]);

            //update type weekday
            $update_dealer_detail_weekday = DB::table('dealer_details')
                                        ->where('dealer_id', $dealer->id)
                                        ->where('dealer_detail_type', 'Weekday') ///******///
                                        ->update(['dealer_detail_amount' => $request->dealer_weekday]);

            //update type weekend
            $update_dealer_detail_weekend = DB::table('dealer_details')
                                        ->where('dealer_id', $dealer->id)
                                        ->where('dealer_detail_type', 'Weekend')
                                        ->update(['dealer_detail_amount' => $request->dealer_weekend]);

            if((isset($update_dealer_detail_vip))
                && (isset($update_dealer_detail_press))
                && (isset($update_dealer_detail_weekday))
                && (isset($update_dealer_detail_weekend))){

                $properties = array(
                    'id' => $dealer->id,
                    'dealer_zone' => $request->dealer_zone,
                    'dealer_area' => $request->dealer_area,
                    'dealer_dlr' => $request->dealer_dlr,
                    'dealer_name' => $dealer->dealer_name,
                    'dealer_vip' => $dealer->dealer_vip,
                    'dealer_press' => $request->dealer_press,
                    'dealer_weekday' => $request->dealer_weekday,
                    'dealer_weekend' => $request->dealer_weekend
                );

                $full_name = Sentinel::getUser()->full_name;

                //Activity log
                activity($full_name)
                   ->performedOn($dealer)
                   ->causedBy($dealer)
                   ->withProperties($properties)
                   ->log('Update Dealer');
                //activity log ends

                return redirect('admin/events/'.$dealer->event_id.'/dealers')->with('response_success', trans('dealers/message.success.update'));
            }
            else{
                return redirect('admin/events/'.$dealer->event_id.'/dealers')->withInput()->with('response_error', trans('dealers/message.error.update_detail'));
            }
        }
        else{
            return redirect('admin/events/'.$dealer->event_id.'/dealers')->withInput()->with('response_error', trans('dealers/message.error.update'));
        }
    }

    public function ReadDate($data)
    {
      $unix_date = (($data*1) - 25569) * 86400;
      $xls_date = 25569 + ($unix_date / 86400);
      $unix_date = ($xls_date - 25569) * 86400;
      return $unix_date;
    }

    public function ReadTime($time)
    {
      date_default_timezone_set('UTC');
      $checkout_time = (($time*1)-529) * 86400;
      return $checkout_time;
    }

    public function MoveFile($request)
    {
      $name = date("Y_m_d_H_i_s");
      $name = $name.'.xlsx';
      Storage::disk('public_uploads')->put($name, File::get($request->file('file')));
      Storage::disk('public_uploads')->move($name,  $request->event_id.'/'.$name);

      $id = DB::table('file_managers')->insertGetId(
          ['url' => 'public/docs/'.$request->event_id.'/'.$name.'.xlsx']
      );
      return $id;
    }

    public function downloadfile($value)
    {
      if ($value == 'dealer') {
        return Storage::disk('public_uploads')->download('template/Template_Dealer.xlsx');
      }else {
        return Storage::disk('public_uploads')->download('template/Template_Sales_Dealer.xlsx');
      }

    }

    public function dataExcel(Request $request)
    {
      $data = array();
      $dateList = array();
      $detailList = array();

      $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw");
      $result = array($request->file('file')->getClientOriginalExtension());

      if (in_array($result[0],$extensions)) {
        $array = Excel::toArray(null,$request->file('file'));
        for ($i=0; $i < count($array[0]); $i++) {
          if ($i != 0) {
            if (($array[0][$i][11]*1) != 0 && ($array[0][$i][14]*1) != 0) {
              $date = $this->ReadDate($array[0][$i][11]);
              $checkout_time = $this->ReadTime($array[0][$i][14]);
              $brief_time = $this->ReadTime($array[0][$i][13]);
              $data_ = array(
                'date' => date("Y-m-d", $date),
                'type' => $array[0][$i][12],
                'brief_time' =>  date('H:i', $brief_time),
                'checkout_time' =>  date('H:i', $checkout_time)
              );
              array_push($dateList,$data_);
            }

            $detail = array(
              'dealer_legacy_code' => $array[0][$i][1],
              'dealer_ids_code' => $array[0][$i][2],
              'dealer_zone' => $array[0][$i][3],
              'dealer_area' => $array[0][$i][4],
              'dealer_dlr' => $array[0][$i][5],
              'dealer_name' => $array[0][$i][6],
              'dealer_vip' => $array[0][$i][7],
              'dealer_press' => $array[0][$i][8],
              'dealer_weekday' => $array[0][$i][9],
              'dealer_weekend' => $array[0][$i][10]
            );
            array_push($detailList,$detail);

          }
        }
        array_push($data,$dateList);
        array_push($data,$detailList);
        return Response()->json(array('status' => 'success','messages' => '' ,'data' => $data ));
      }else {
        return Response()->json(array('status' => 'error','messages' => 'กรุณาอัพโหลดไฟล์นามสกุล .xlsx' ));
      }
    }

    public function SentDataExcel(Request $request)
    {
      $data = array();
      $dateList = array();
      $detailList = array();

      $detail_dealer = array();
      $detail_dealer_des = array();
      $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw");
      $result = array($request->file('file')->getClientOriginalExtension());

      if (in_array($result[0],$extensions)) {
        $array = Excel::toArray(null,$request->file('file'));

        for ($i=0; $i < count($array[0]); $i++) {
          if ($i != 0) {
            if (($array[0][$i][11]*1) != 0 && ($array[0][$i][14]*1) != 0) {
              $date = $this->ReadDate($array[0][$i][11]);
              $checkout_time = $this->ReadTime($array[0][$i][14]);
              $brief_time = $this->ReadTime($array[0][$i][13]);
              $data_ = array(
                'date' => date("Y-m-d", $date),
                'type' => $array[0][$i][12],
                'brief_time' =>  date('H:i', $brief_time),
                'checkout_time' =>  date('H:i', $checkout_time)
              );
              array_push($dateList,$data_);
            }

            $detail = array(
              'dealer_legacy_code' => $array[0][$i][1],
              'dealer_ids_code' => $array[0][$i][2],
              'dealer_zone' => $array[0][$i][3],
              'dealer_area' => $array[0][$i][4],
              'dealer_dlr' => $array[0][$i][5],
              'dealer_name' => $array[0][$i][6],
              'dealer_vip' => $array[0][$i][7],
              'dealer_press' => $array[0][$i][8],
              'dealer_weekday' => $array[0][$i][9],
              'dealer_weekend' => $array[0][$i][10],
              'event_id' => $request->event_id,
              'dealer_status' => 1
            );
            array_push($detailList,$detail);

          }
        }
        array_push($data,$dateList);
        array_push($data,$detailList);

        $file_id = $this->MoveFile($request);
        Events::where('id','=',$request->event_id)->update(array( 'file_dealer_id' => $file_id));

        $dealer = Dealers::where('event_id','=',$request->event_id)->get()->toArray();
        $de_id = array();
        foreach ($dealer as $value) {
          array_push($de_id,$value['id']);
        }
        // $detail_dealer = $dealer;
        if (count($dealer) == 0) {
          Dealers::Insert($detailList);

          $dealer = Dealers::where('event_id','=',$request->event_id)->get()->toArray();
          $detail_dealer = $dealer;
          $dealer_datail = array();
          foreach ($dealer as $dealer_value) {
            foreach ($dateList as $dateList_value) {
              $dealer_datail_time = array(
                'dealer_id' => $dealer_value['id'],
                'dealer_detail_date' => $dateList_value['date'],
                'dealer_detail_type' => $dateList_value['type'],
                'dealer_detail_brief_time' => $dateList_value['brief_time'],
                'dealer_detail_checkout_time' => $dateList_value['checkout_time'],
                'dealer_detail_status' => 1
              );

              switch ($dateList_value['type']) {
                case 'Weekend':
                  $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_weekend'];
                  break;
                case 'Weekday':
                    $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_weekday'];
                  break;
                case 'PRESS':
                    $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_press'];
                  break;
                case 'VIP':
                    $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_vip'];
                  break;

                default:
                  // code...
                  break;
              }

              array_push($dealer_datail,$dealer_datail_time);
            }
          }
          Dealer_details::Insert($dealer_datail);
        }else {

          Dealers::where('event_id','=',$request->event_id)->update(array('dealer_status' => 0 ));
          Dealer_details::whereIn('dealer_id',$de_id)->update(array('dealer_detail_status' => 0 ));
          // $de_id

          foreach ($detailList as $detailList_value) {
            $Dea = Dealers::where('event_id','=',$request->event_id)->where('dealer_legacy_code','=',$detailList_value['dealer_legacy_code'])->get()->toArray();
            if (count($Dea) ==  0) {
              Dealers::Insert($detailList_value);
            }else {
              Dealers::where('event_id','=',$request->event_id)
                              ->where('dealer_legacy_code','=',$detailList_value['dealer_legacy_code'])
                              ->update($detailList_value);
            }

          }

          $dealer = Dealers::where('event_id','=',$request->event_id)->where('dealer_status','<>' ,0)->get()->toArray();
          $dealer_datail = array();
          foreach ($dealer as $dealer_value) {
            foreach ($dateList as $dateList_value) {
              $dealer_datail_time = array(
                'dealer_id' => $dealer_value['id'],
                'dealer_detail_date' => $dateList_value['date'],
                'dealer_detail_type' => $dateList_value['type'],
                'dealer_detail_brief_time' => $dateList_value['brief_time'],
                'dealer_detail_checkout_time' => $dateList_value['checkout_time'],
                'dealer_detail_status' => 1
              );

              switch ($dateList_value['type']) {
                case 'Weekend':
                  $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_weekend'];
                  break;
                case 'Weekday':
                    $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_weekday'];
                  break;
                case 'PRESS':
                    $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_press'];
                  break;
                case 'VIP':
                    $dealer_datail_time['dealer_detail_amount'] = $dealer_value['dealer_vip'];
                  break;

                default:
                  // code...
                  break;
              }

              array_push($dealer_datail,$dealer_datail_time);
            }
          }

          foreach ($dealer_datail as $dealer_datail_value) {
            $Dea = Dealer_details::where('dealer_id','=',$dealer_datail_value['dealer_id'])->where('dealer_detail_date','=',$dealer_datail_value['dealer_detail_date'])->get()->toArray();
            if (count($Dea) ==  0) {
              Dealer_details::Insert($dealer_datail_value);
            }else {
              Dealer_details::where('dealer_id','=',$dealer_datail_value['dealer_id'])->where('dealer_detail_date','=',$dealer_datail_value['dealer_detail_date'])
                              ->update($dealer_datail_value);
            }
          }
        }

        // $de = array();
        // foreach ($$detailList as $detailList_value) {
        //   array_push($de,$detailList_value['dealer_legacy_code']);
        // }




        // Dealers
        // Dealer_details

        return Response()->json(array('status' => 'success','messages' => '' ,'data' => $data ));
      }else {
        return Response()->json(array('status' => 'error','messages' => 'กรุณาอัพโหลดไฟล์นามสกุล .xlsx' ));
      }
    }

    /*
    public function getArea(Request $request)
    {
        $areas = DB::table('dealers')
            ->select('dealer_area as area_name')
            ->distinct('dealer_area')
            ->where('event_id','=',$request->event_id)
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
