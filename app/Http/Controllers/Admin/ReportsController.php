<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SaleDealerRequest;
use App\Http\Requests\EventRequest;
use DOMDocument;
use Response;
use Sentinel;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Log;
use Redirect;
use Session;
use App\Dlr;

use App\CheckInCheckOut;
use App\SaleDealer;
use App\Models\Preemptions\Preemptions_details;
use App\Models\Dealer\Dealers;
use App\Models\Dealer\Dealer_details;
use App\Models\ModelCar\Model_cars;
use App\Models\ModelCar\Sub_model_cars;


use App\Exports\SaledealerExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Event;

class ReportsController extends Controller
{
    public function index() {
        return 'Preemptions';
    }

    public function reportIndex(Request $request, $id) {
        Session::put('event_id', $id);

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$id)
            ->first();

        $event_all = DB::table('events')
            ->select('id', 'event_name')
            ->where('event_status', 1)
            ->get();

        $dealer_zone = Dealers::select('dealer_zone')
            ->where('event_id','=',$id)
            ->groupBy('dealer_zone')
            ->get()->toArray();

        $dealer_area = Dealers::select('dealer_area')
            ->where('event_id','=',$id)
            ->groupBy('dealer_area')
            ->get()->toArray();

        $dealer_dlr = Dealers::select('dealer_dlr')
            ->where('event_id','=',$id)
            ->groupBy('dealer_dlr')
            ->get()->toArray();

        $model_car = Model_cars::select(
                'id',
                'model_car_name'
            )
            ->where('model_cars.model_car_status', 1)
            ->groupBy('model_car_name')
            ->get()->toArray();

        return view('admin.reports.checkin_checkout.index',[
            'event'         => $event,
            'dealer_zone'   => $dealer_zone,
            'dealer_area'   => $dealer_area,
            'dealer_dlr'    => $dealer_dlr,
            'event_all'     => $event_all,
            'model_car'     => $model_car
        ]);

    }

    //Table Data to index sale_dealer
    public function DataSaleDealerReport(Request $request) {
        $sale_dealers = SaleDealer::select(
                'sale_dealers.id as id',
                'sale_dealers.sale_dealer_code',
                'sale_dealers.sale_dealer_name',
                'sale_dealers.sale_dealer_nickname',
                'sale_dealers.sale_dealer_tel',
                'dealers.dealer_ids_code',
                'dealers.dealer_legacy_code',
                'dealers.dealer_zone',
                'dealers.dealer_area',
                'dealers.dealer_dlr',
                'dealers.dealer_name',
                'sale_dealers.sale_dealer_status',
                'dealers.event_id'
                )

            ->leftJoin('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
            ->where('sale_dealers.sale_dealer_status','=',1)
            ->where('dealers.event_id', '=', $request->event_id)
            ->where('dealers.dealer_status', 1)
            ->get();

        return datatables($sale_dealers)->toJson();
    }

    public function DataCheckinCheckoutReport(Request $request) {
        if($request->daterange_search) {

            $dates = $request->daterange_search;
            $date = explode(" - ",$dates);

            for ($i=0; $i < count($date); $i++){
                $date_ = explode("/",$date[$i]);
                $date[$i] = $date_[2].'-'.$date_[1].'-'.$date_[0];
            }

            $checkin_checkouts = CheckInCheckOut::select(
                'checkin_checkout.id',
                'checkin_checkout.event_date',
                'dealers.dealer_dlr',
                'dealers.dealer_zone',
                'dealers.dealer_area',
                'dealers.dealer_name',

                'sale_dealers.sale_dealer_code',
                'sale_dealers.sale_dealer_name',
                'sale_dealers.sale_dealer_nickname',
                'sale_dealers.sale_dealer_tel',

                'checkin_checkout.checkin_time',
                'checkin_checkout.checkout_time',
                'checkin_checkout.checkin_reason',
                'checkin_checkout.checkin_over_reason',
                'checkin_checkout.checkout_reason',
                'checkin_checkout.dealer_id',
                'checkin_checkout.sale_dealer_id',

                'checkin_checkout.event_id'
                )
            ->leftJoin('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->leftJoin('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
            ->where('sale_dealers.sale_dealer_status','=',1)
            ->where('checkin_checkout.event_id','=',$request->event_id)
            ->whereBetween('checkin_checkout.event_date', $date)
            ->get();

        } else {
            $checkin_checkouts = CheckInCheckOut::select(
                'checkin_checkout.id',
                'checkin_checkout.event_date',
                'dealers.dealer_dlr',
                'dealers.dealer_zone',
                'dealers.dealer_area',
                'dealers.dealer_name',

                'sale_dealers.sale_dealer_code',
                'sale_dealers.sale_dealer_name',
                'sale_dealers.sale_dealer_nickname',
                'sale_dealers.sale_dealer_tel',
                'sale_dealers.dealer_legacy_code',

                'checkin_checkout.checkin_time',
                'checkin_checkout.checkout_time',
                'checkin_checkout.checkin_reason',
                'checkin_checkout.checkin_over_reason',
                'checkin_checkout.checkout_reason',
                'checkin_checkout.dealer_id',
                'checkin_checkout.sale_dealer_id',

                'checkin_checkout.event_id'
                )
            ->leftJoin('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->leftJoin('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
            ->where('sale_dealers.sale_dealer_status','=',1)
            ->where('checkin_checkout.event_id','=',$request->event_id)
            ->get();
        }

        return datatables($checkin_checkouts)->toJson();
    }

    public function DataPreemptionReport(Request $request) {
        if ($request->daterange_search_preemption) {

            $date = explode(" - ",$request->daterange_search_preemption);
            $s_date = str_replace('/', '-', $date[0]).' 00:00:00';
            $e_date = str_replace('/', '-', $date[1]).' 23:59:59';
            $start_date = date("Y-m-d H:i:s", strtotime($s_date));
            $end_date = date("Y-m-d H:i:s", strtotime($e_date));

            $preemptions = Preemptions_details::select(
                'preemptions_details.id',
                DB::raw ("DATE_FORMAT(preemptions_details.updated_at, '%Y-%m-%d') as updated_at"),
                'preemptions_details.preemption_type',
                'preemptions_details.preemption_no',
                'dealers.dealer_dlr',
                'dealers.dealer_zone',
                'dealers.dealer_area',
                'sale_dealers.sale_dealer_code',
                'sale_dealers.sale_dealer_name',
                'preemptions_details.request_at',
                'preemptions_details.response_at',
                'preemptions_details.preemption_status',
                'preemptions_details.event_id',
                'preemptions_details.model_car_id',
                'preemptions_details.sub_model_car_id',

                'model_cars.model_car_name',
                'model_cars.id',
                'sub_model_cars.sub_model_car_name',
                'sub_model_cars.id'
            )
            ->leftJoin('sale_dealers','preemptions_details.sale_dealer_id','=','sale_dealers.id')
            ->leftJoin('dealers','sale_dealers.dealer_id','=','dealers.id')
            ->leftJoin('model_cars', 'preemptions_details.model_car_id', 'model_cars.id')
            ->leftJoin('sub_model_cars', 'preemptions_details.sub_model_car_id', 'sub_model_cars.id')
            ->whereBetween('preemptions_details.updated_at', [$start_date, $end_date])
            ->where('preemptions_details.event_id','=',$request->event_id)
            ->where('preemptions_details.sale_dealer_id', '!=', null)
            ->get();

        } else {
            $preemptions = Preemptions_details::select(
              'preemptions_details.id',
              DB::raw ("DATE_FORMAT(preemptions_details.updated_at, '%Y-%m-%d') as updated_at"),
              'preemptions_details.preemption_type',
              'preemptions_details.preemption_no',
              'dealers.dealer_dlr',
              'dealers.dealer_zone',
              'dealers.dealer_area',
              'sale_dealers.sale_dealer_code',
              'sale_dealers.sale_dealer_name',
              'preemptions_details.request_at',
              'preemptions_details.response_at',
              'preemptions_details.preemption_status',
              'preemptions_details.event_id',
              'preemptions_details.model_car_id',
              'preemptions_details.sub_model_car_id',

              'model_cars.model_car_name',
              'model_cars.id',
              'sub_model_cars.sub_model_car_name',
              'sub_model_cars.id'
              )
            ->leftJoin('sale_dealers','preemptions_details.sale_dealer_id','=','sale_dealers.id')
            ->leftJoin('dealers','sale_dealers.dealer_id','=','dealers.id')
            ->leftJoin('model_cars', 'preemptions_details.model_car_id', 'model_cars.id')
            ->leftJoin('sub_model_cars', 'preemptions_details.sub_model_car_id', 'sub_model_cars.id')
            ->where('preemptions_details.event_id','=',$request->event_id)
            ->where('preemptions_details.sale_dealer_id', '!=', null)
            ->get();
        }

      return datatables($preemptions)->toJson();
    }

    public function DataDealerCheckinReport(Request $request) {
        if(isset($request->daterange_search_dealer_checkin)) {
            $date = explode(" - ",$request->daterange_search_dealer_checkin);
            $s_date = str_replace('/', '-', $date[0]);
            $e_date = str_replace('/', '-', $date[1]);
            $start_date = date("Y-m-d", strtotime($s_date));
            $end_date = date("Y-m-d", strtotime($e_date));
        }

        $query = Dealers::select(
            'id',
            'dealer_dlr',
            'dealer_name',
            'event_id'
            )
        ->where('dealer_status', '=', 1)
        ->where('dealers.event_id','=',$request->event_id);
        $dealers = $query->get();

        
        foreach ($dealers as $dealer){
            //$dealer->id

            //count จำนวนโควต้า Checkin
            $query = DB::table('dealer_details')
                ->where('dealer_id','=',$dealer->id);

            if(isset($request->daterange_search_dealer_checkin)) {
                $query->whereBetween('dealer_detail_date', [$start_date, $end_date]);
            } 

            $checkin_quota = $query->sum('dealer_detail_amount');
            $dealer->checkin_quota = number_format($checkin_quota);


            //count จำนวน Checkin 
            $query = DB::table('checkin_checkout')
                ->where('dealer_id','=',$dealer->id);

            if(isset($request->daterange_search_dealer_checkin)) {
                $query->whereBetween('event_date', [$start_date, $end_date]);
            } 
            $checkin_time = count($query->get());
            $dealer->checkin_time = number_format($checkin_time);


            //count จำนวน Checkin ที่มาสาย
            $query = DB::table('checkin_checkout')
                ->where('dealer_id','=',$dealer->id)
                ->where('checkin_reason','!=',null);

            if(isset($request->daterange_search_dealer_checkin)) {
                $query->whereBetween('event_date', [$start_date, $end_date]);
            }
            $checkin_late = count($query->get());
            $dealer->checkin_late = number_format($checkin_late);


            //count จำนวน Checkin เกินโควต้า
            $query = DB::table('checkin_checkout')
                ->where('dealer_id','=',$dealer->id)
                ->where('checkin_over_reason','!=',null);

            if(isset($request->daterange_search_dealer_checkin)) {
                $query->whereBetween('event_date', [$start_date, $end_date]);
            }
            $checkin_over = count($query->get());
            $dealer->checkin_over = number_format($checkin_over);
        }
        return DataTables::of($dealers)->make(true);
    }

    public function getDlr(Request $request) {
        $getDlrs = Dealers::select('dealer_dlr')
            ->where('dealer_status','=',1)
            ->groupBy('dealer_dlr')
            ->where('event_id','=',$request->event_id)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($getDlrs as $dlr){
            $output.= '<option value="'.$dlr->dealer_dlr.'">'.$dlr->dealer_dlr.'</option>';
        }

        echo $output;
    }

    public function getZone(Request $request) {
        $getZones = Dealers::select('dealer_zone')
            ->where('dealer_status','=',1)
            ->groupBy('dealer_zone')
            ->where('event_id','=',$request->event_id)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($getZones as $zone){
            $output.= '<option value="'.$zone->dealer_zone.'">'.$zone->dealer_zone.'</option>';
        }

        echo $output;
    }

    public function getArea(Request $request) {
        $getAreas = Dealers::select('dealer_area')
            ->where('dealer_status','=',1)
            ->groupBy('dealer_area')
            ->where('event_id','=',$request->event_id)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($getAreas as $area){
            $output.= '<option value="'.$area->dealer_area.'">'.$area->dealer_area.'</option>';
        }

        echo $output;
    }

    public function getSubModelCar(Request $request) {
        $sub_model_cars = Sub_model_cars::select('sub_model_car_name','model_car_id')
            ->leftJoin('model_cars','sub_model_cars.model_car_id', 'model_cars.id')
            ->where('sub_model_car_status','=',1)
            ->where('model_cars.model_car_name', $request->model_car_name)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($sub_model_cars as $sub_model_car){
            $output.= '<option value="'.$sub_model_car->sub_model_car_name.'">'.$sub_model_car->sub_model_car_name.'</option>';
        }

        echo $output;
    }

    // public function ExportExcel() {
    //     return Excel::download(new SaledealerExport, 'sale_dealers.xlsx');
    // }

}
