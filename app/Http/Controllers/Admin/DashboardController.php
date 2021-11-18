<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DOMDocument;
use Response;
use Sentinel;
use DB;
use Illuminate\Support\Facades\Log;
use Redirect;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $event = DB::table('events')
            ->select('id','event_name')
            ->where('event_status','=',1)
            ->get();

        $count_dealer = 0;
        $count_sale_dealer = 0;
        $count_request = 0;
        $count_response = 0;
        $count_cancel = 0;
        $count_checkin = 0;
        $count_checkin_late = 0;
        $count_checkin_over = 0;
        $count_checkout = 0;
        $count_checkout_early = 0;
        $count_not_checkout = 0;

        return view('admin.index',[
            'event' => $event,
            'count_dealer' => $count_dealer,
            'count_sale_dealer' => $count_sale_dealer,
            'count_request' => $count_request,
            'count_response' => $count_response,
            'count_cancel' => $count_cancel,
            'count_checkin' => $count_checkin,
            'count_checkin_late' => $count_checkin_late,
            'count_checkin_over' => $count_checkin_over,
            'count_checkout' => $count_checkout,
            'count_checkout_early' => $count_checkout_early,
            'count_not_checkout' => $count_not_checkout
        ]);
    }

    public function home(Request $request)
    {
        $event = DB::table('events')
            ->select('id','event_name')
            ->where('event_status','=',1)
            ->get();

        $count_dealer = 1;
        $count_sale_dealer = 1;
        $count_request = 1;
        $count_response = 1;
        $count_cancel = 5;

        return view('admin.index',[
            'event' => $event,
            'count_dealer' => $count_dealer,
            'count_sale_dealer' => $count_sale_dealer,
            'count_request' => $count_request,
            'count_response' => $count_response,
            'count_cancel' => $count_cancel
        ]);   
    }

    public function showHome(Request $request)
    {
        $count_dealer = 0;
        $count_sale_dealer = 0;
        $count_request = 0;
        $count_response = 0;
        $count_cancel = 0;
        $count_checkin = 0;
        $count_checkin_late = 0;
        $count_checkin_over = 0;
        $count_checkout = 0;
        $count_checkout_early = 0;
        $count_not_checkout = 0;

        
        //count_dealer
        if(isset($request->event_search)){
            $count_dealer = number_format($this->countDealer($request->event_search));
        }

        //count_sale_dealer
        if(isset($request->event_search)){
            $count_sale_dealer = number_format($this->countSaleDealer($request->event_search));
        }


        //count_request
        $query = DB::table('preemptions_details')
                ->leftJoin('events', 'preemptions_details.event_id', '=', 'events.id')
                ->leftJoin('sale_dealers', 'preemptions_details.sale_dealer_id', '=', 'sale_dealers.id')
                ->leftJoin('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
                ->where('preemption_status','=',1);
                //->where('request_at','!=',null);

        if(isset($request->event_search)){
            $query->where('preemptions_details.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]).' 00:00:00';
            $e_date = str_replace('/', '-', $date[1]).' 23:59:59';
            $start_date = date("Y-m-d H:i:s", strtotime($s_date));
            $end_date = date("Y-m-d H:i:s", strtotime($e_date));

            $query->whereBetween('preemptions_details.request_at', [$start_date, $end_date]);
        }
        $count_request = number_format(count($query->get()));


        //count_response
        $query = DB::table('preemptions_details')
                ->leftJoin('events', 'preemptions_details.event_id', '=', 'events.id')
                ->leftJoin('sale_dealers', 'preemptions_details.sale_dealer_id', '=', 'sale_dealers.id')
                ->leftJoin('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
                ->where('preemption_status','=',2);

        if(isset($request->event_search)){
            $query->where('preemptions_details.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]).' 00:00:00';
            $e_date = str_replace('/', '-', $date[1]).' 23:59:59';
            $start_date = date("Y-m-d H:i:s", strtotime($s_date));
            $end_date = date("Y-m-d H:i:s", strtotime($e_date));

            $query->whereBetween('preemptions_details.request_at', [$start_date, $end_date]);
        }
        $count_response = number_format(count($query->get()));


        //count_cancel
        $query = DB::table('preemptions_details')
                ->leftJoin('events', 'preemptions_details.event_id', '=', 'events.id')
                ->leftJoin('sale_dealers', 'preemptions_details.sale_dealer_id', '=', 'sale_dealers.id')
                ->leftJoin('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
                ->where('preemption_status','=',3);

        if(isset($request->event_search)){
            $query->where('preemptions_details.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]).' 00:00:00';
            $e_date = str_replace('/', '-', $date[1]).' 23:59:59';
            $start_date = date("Y-m-d H:i:s", strtotime($s_date));
            $end_date = date("Y-m-d H:i:s", strtotime($e_date));

            $query->whereBetween('preemptions_details.request_at', [$start_date, $end_date]);
        }
        $count_cancel = number_format(count($query->get()));
        

        //count_checkin
        $query = DB::table('checkin_checkout')
            ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->join('dealer_details', function ($join) {
                $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                    ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
            })
            ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')

            ->where('sale_dealers.sale_dealer_status','=',1);
            

        if(isset($request->event_search)){
            $query->where('checkin_checkout.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]);
            $e_date = str_replace('/', '-', $date[1]);
            $start_date = date("Y-m-d", strtotime($s_date));
            $end_date = date("Y-m-d", strtotime($e_date));

            $query->whereBetween('checkin_checkout.event_date', [$start_date, $end_date]);
        }
        $count_checkin = number_format(count($query->get()));


        //count_checkin_late
        $query = DB::table('checkin_checkout')
            ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->join('dealer_details', function ($join) {
                $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                    ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
            })
            ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
            ->where('checkin_checkout.checkin_reason','!=',null)
            ->where('sale_dealers.sale_dealer_status','=',1);
            

        if(isset($request->event_search)){
            $query->where('checkin_checkout.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]);
            $e_date = str_replace('/', '-', $date[1]);
            $start_date = date("Y-m-d", strtotime($s_date));
            $end_date = date("Y-m-d", strtotime($e_date));

            $query->whereBetween('checkin_checkout.event_date', [$start_date, $end_date]);
        }
        $count_checkin_late = number_format(count($query->get()));


        //count_checkin_over
        $query = DB::table('checkin_checkout')
            ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->join('dealer_details', function ($join) {
                $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                    ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
            })
            ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
            ->where('checkin_checkout.checkin_over_reason','!=',null)
            ->where('sale_dealers.sale_dealer_status','=',1);
            

        if(isset($request->event_search)){
            $query->where('checkin_checkout.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]);
            $e_date = str_replace('/', '-', $date[1]);
            $start_date = date("Y-m-d", strtotime($s_date));
            $end_date = date("Y-m-d", strtotime($e_date));

            $query->whereBetween('checkin_checkout.event_date', [$start_date, $end_date]);
        }
        $count_checkin_over = number_format(count($query->get()));
        

        //count_checkout
        $query = DB::table('checkin_checkout')
            ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->join('dealer_details', function ($join) {
                $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                    ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
            })
            ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')

            ->where('sale_dealers.sale_dealer_status','=',1)
            ->where('checkin_checkout.checkout_time','!=',null);
            

        if(isset($request->event_search)){
            $query->where('checkin_checkout.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]);
            $e_date = str_replace('/', '-', $date[1]);
            $start_date = date("Y-m-d", strtotime($s_date));
            $end_date = date("Y-m-d", strtotime($e_date));

            $query->whereBetween('checkin_checkout.event_date', [$start_date, $end_date]);
        }
        $count_checkout = number_format(count($query->get()));


        //count_checkout_early
        $query = DB::table('checkin_checkout')
            ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->join('dealer_details', function ($join) {
                $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                    ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
            })
            ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
            ->where('sale_dealers.sale_dealer_status','=',1)
            ->where('checkin_checkout.checkout_reason','!=',null) 
            ->where('checkin_checkout.checkout_time','!=',null);
            

        if(isset($request->event_search)){
            $query->where('checkin_checkout.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]);
            $e_date = str_replace('/', '-', $date[1]);
            $start_date = date("Y-m-d", strtotime($s_date));
            $end_date = date("Y-m-d", strtotime($e_date));

            $query->whereBetween('checkin_checkout.event_date', [$start_date, $end_date]);
        }
        $count_checkout_early = number_format(count($query->get()));


        //count_not_checkout
        $query = DB::table('checkin_checkout')
            ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->join('dealer_details', function ($join) {
                $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                    ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
            })
            ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
            ->where('sale_dealers.sale_dealer_status','=',1)
            ->where('checkin_checkout.checkout_time','=',null);
            

        if(isset($request->event_search)){
            $query->where('checkin_checkout.event_id','=',$request->event_search);
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
        //daterange_search
        if(isset($request->daterange_search)){
            $date = explode(" - ",$request->daterange_search);
            $s_date = str_replace('/', '-', $date[0]);
            $e_date = str_replace('/', '-', $date[1]);
            $start_date = date("Y-m-d", strtotime($s_date));
            $end_date = date("Y-m-d", strtotime($e_date));

            $query->whereBetween('checkin_checkout.event_date', [$start_date, $end_date]);
        }
        $count_not_checkout = number_format(count($query->get())); 


        echo json_encode(array(
            'status' => 1,
            'message' => 'Get dashboard success.',
            'data' => [
                'count_dealer' => $count_dealer,
                'count_sale_dealer' => $count_sale_dealer,
                'count_request' => $count_request,
                'count_response' => $count_response,
                'count_cancel' => $count_cancel,
                'count_checkin' => $count_checkin,
                'count_checkin_late' => $count_checkin_late,
                'count_checkin_over' => $count_checkin_over,
                'count_checkout' => $count_checkout,
                'count_checkout_early' => $count_checkout_early,
                'count_not_checkout' => $count_not_checkout
            ]
        ));
    }

    public function getEvent(Request $request)
    {
        $event = DB::table('events')
            ->select('id','event_name')
            ->where('event_status','=',1)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($event as $e){
            $output.= '<option value="'.$e->id.'">'.$e->event_name.'</option>';
        }

        echo $output;
    }

    public function getDLR(Request $request)
    {
        $dlr = DB::table('dealers')
            ->select('id as dlr_id','dealer_dlr as dlr_name')
            ->where('event_id','=',$request->event_id)
            ->where('dealer_status','=',1)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($dlr as $d){
            $output.= '<option value="'.$d->dlr_name.'">'.$d->dlr_name.'</option>';
        }

        echo $output;
    }

    public function getZone(Request $request)
    {
        $zones = DB::table('dealers')
            ->select('dealer_zone as zone_name')
            ->distinct('dealer_zone')
            ->where('event_id','=',$request->event_id)
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
            ->where('dealer_status','=',1)
            ->get();

        $output = '<option value="">- ทั้งหมด -</option>';

        foreach ($areas as $area){
            $output.= '<option value="'.$area->area_name.'">'.$area->area_name.'</option>';
        }

        echo $output;
    }

    public function countDealer($event_id)
    {
        $dealer = DB::table('dealers')
            ->where('event_id','=',$event_id)
            ->where('dealer_status','=',1)
            ->get();

        return count($dealer);
    }

    public function countSaleDealer($event_id)
    {
        $sale_dealer = DB::table('sale_dealers')
            ->join('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
            ->where('dealers.event_id','=',$event_id)
            ->where('sale_dealers.sale_dealer_status','=',1)
            ->get();

        return count($sale_dealer);
    }

}
