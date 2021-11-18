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

class CheckInCheckOutController extends Controller
{
    public function index()
    { 
        return 'CheckInCheckOut';  
    }

    public function checkInCheckOutIndex($id)
    {
        //return $id;

        Session::put('event_id', $id);

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$id)
            ->first();

        $dlr = DB::table('dealers')
            ->select('dealer_dlr as dlr_name')
            ->where('event_id','=',$id)
            ->get();

        return view('admin.checkin_checkout.index',[
            'event' => $event,
            'dlr' => $dlr
        ]);
    }

    public function data(Request $request)
    {   
        $event_id = Session::get('event_id');

        $query = DB::table('checkin_checkout')
            ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
            ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
            ->select(
                'checkin_checkout.id as id', 
                'checkin_checkout.event_date as date',
                'dealers.dealer_dlr as dlr',
                'dealers.dealer_name as dealer_name',
                'sale_dealers.sale_dealer_code as id_sale',
                'sale_dealers.sale_dealer_name as sale_dealer_name',
                'checkin_checkout.checkin_time as checkin',
                'checkin_checkout.checkout_time as checkout',

                'checkin_checkout.event_id as event_id',
                'checkin_checkout.dealer_id as dealer_id',
                'checkin_checkout.sale_dealer_id as sale_dealer_id'
            )
            ->where('sale_dealers.sale_dealer_status','=',1);
            

        if(isset($event_id)){
            $query->where('checkin_checkout.event_id','=',$event_id);
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

        if(isset($request->text_search) && ($request->text_search != '')){
            $query->where([
                ['checkin_checkout.event_date','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_dlr','like','%'.$request->text_search.'%','or'],
                ['dealers.dealer_name','like','%'.$request->text_search.'%','or'],
                ['sale_dealers.sale_dealer_code','like','%'.$request->text_search.'%','or'],
                ['sale_dealers.sale_dealer_name','like','%'.$request->text_search.'%','or'],
                ['checkin_checkout.checkin_time','like','%'.$request->text_search.'%','or'],
                ['checkin_checkout.checkout_time','like','%'.$request->text_search.'%','or']
            ]);
        }
        if(isset($request->dlr_search)){
            $query->where('dealers.dealer_dlr','like',$request->dlr_search);
        }
        
        $query->orderby('checkin_checkout.updated_at','desc');
        $checkin_checkout = $query->get();

        return DataTables::of($checkin_checkout)
            
            ->editColumn('date', function ($data) {
                return $data->date;
            }) 
            ->editColumn('checkin', function ($data) {
                return substr($data->checkin,0,5);
            }) 
            ->editColumn('checkout', function ($data) {
                return substr($data->checkout,0,5);
            })    
            ->rawColumns(['date','checkin','checkout'])
            ->make(true);
    } 

    public function checkin()
    {   
        $event_id = Session::get('event_id');

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$event_id)
            ->first();

        return view('admin.checkin_checkout.checkin',[
            'event' => $event
        ]);  
    }

    public function checkout()
    { 
        $event_id = Session::get('event_id');

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$event_id)
            ->first();

        return view('admin.checkin_checkout.checkout',[
            'event' => $event
        ]);  
    }

    public function getCheckIndata(Request $request)
    {

        if(isset($request->event_id)){
            $today = date('Y-m-d');
            //$today = '2019-08-18';

            $query = DB::table('checkin_checkout')
                
                ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
                ->join('dealer_details', function ($join) {
                    $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                        ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
                })
                ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
                ->select(
                    'checkin_checkout.id as id', 
                    'checkin_checkout.event_date as date',
                    'dealers.dealer_dlr as dlr',
                    'dealers.dealer_name as dealer_name',
                    'sale_dealers.sale_dealer_code as id_sale',
                    'sale_dealers.sale_dealer_name as sale_dealer_name',
                    'checkin_checkout.checkin_time as checkin',

                    'checkin_checkout.event_id as event_id',
                    'checkin_checkout.dealer_id as dealer_id',
                    'checkin_checkout.sale_dealer_id as sale_dealer_id',

                    'checkin_checkout.checkin_reason as note',
                    'dealer_details.dealer_detail_brief_time as brief_time',
                    'dealer_details.dealer_detail_checkout_time as checkout_time',
                    'checkin_checkout.checkin_over_reason as over_reason'
                )
                ->where('sale_dealers.sale_dealer_status','=',1)
                ->where('checkin_checkout.event_id','=',$request->event_id)
                ->where('checkin_checkout.event_date','=',$today)
                ->orderby('checkin_checkout.updated_at','desc')
                ->orderby('checkin_checkout.event_date','desc');   
        }
        else{
            $query = DB::table('checkin_checkout')
                ->where('checkin_checkout.event_id','=',0);
        }

        $query->orderby('checkin_checkout.updated_at','desc');
        $checkin_checkout = $query->get();

        return DataTables::of($checkin_checkout)
            
            ->editColumn('date', function ($data) {
                return $data->date;
            }) 
            ->editColumn('checkin', function ($data) {
                return substr($data->checkin,0,5);
            }) 
            ->addColumn('status', function ($data) {

                if($data->checkin <= $data->brief_time){
                    if(!empty($data->over_reason)){
                        $status = '<span class="fa fa-circle dot-red"></span><span class="fnt-14">&nbsp;&nbsp; เกิน quota</span>';
                    }
                    else{
                        $status = '<span class="fa fa-circle dot-green"></span><span class="fnt-14">&nbsp;&nbsp; มาตรงเวลา</span>';
                    }
                }
                else if(!empty($data->over_reason)){
                    $status = '<span class="fa fa-circle dot-red"></span><span class="fnt-14">&nbsp;&nbsp; เกิน quota</span>';
                }
                else{
                    $status = '<span class="fa fa-circle dot-orange"></span><span class="fnt-14">&nbsp;&nbsp; สาย</span>';
                }

                return $status;
            })   
            ->editColumn('note', function ($data) {
                if(is_null($data->over_reason)){
                    $note = $data->note;
                }
                else{
                    $note = $data->over_reason;
                }
                return $note;
            })   
            ->rawColumns(['date','checkin','status','note'])
            ->make(true);
    } 

    public function getCheckOutdata(Request $request)
    {

        if(isset($request->event_id)){
            $today = date('Y-m-d');
            //$today = '2019-08-17';

            $query = DB::table('checkin_checkout')
                
                ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
                ->join('dealer_details', function ($join) {
                    $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                        ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
                })
                ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
                ->select(
                    'checkin_checkout.id as id', 
                    'checkin_checkout.event_date as date',
                    'dealers.dealer_dlr as dlr',
                    'dealers.dealer_name as dealer_name',
                    'sale_dealers.sale_dealer_code as id_sale',
                    'sale_dealers.sale_dealer_name as sale_dealer_name',
                    'checkin_checkout.checkout_time as checkout',

                    'checkin_checkout.event_id as event_id',
                    'checkin_checkout.dealer_id as dealer_id',
                    'checkin_checkout.sale_dealer_id as sale_dealer_id',

                    'checkin_checkout.checkout_reason as note',
                    'dealer_details.dealer_detail_brief_time as brief_time',
                    'dealer_details.dealer_detail_checkout_time as checkout_time'
                )
                ->where('sale_dealers.sale_dealer_status','=',1)
                ->where('checkin_checkout.event_id','=',$request->event_id)
                ->where('checkin_checkout.checkout_time','!=',null)
                ->where('checkin_checkout.event_date','=',$today)
                ->orderby('checkin_checkout.updated_at','desc')
                ->orderby('checkin_checkout.event_date','desc'); 
        }
        else{
            $query = DB::table('checkin_checkout')
                ->where('checkin_checkout.event_id','=',0);
        }

        $query->orderby('checkin_checkout.updated_at','desc');
        $checkin_checkout = $query->get();

        return DataTables::of($checkin_checkout)
            
            ->editColumn('date', function ($data) {
                return $data->date;
            }) 
            ->editColumn('checkout', function ($data) {
                return substr($data->checkout,0,5);
            }) 
            ->addColumn('status', function ($data) {
                return ($data->checkout >= $data->checkout_time)?'<span class="fa fa-circle dot-green"></span><span class="fnt-14">&nbsp;&nbsp; กลับตรงเวลา</span>':'<span class="fa fa-circle dot-orange"></span><span class="fnt-14">&nbsp;&nbsp; กลับก่อนเวลา</span>';
            })     
            ->rawColumns(['date','checkout','status'])
            ->make(true);
    } 


    public function setCheckIn(Request $request)
    {  
        if(isset($request->code) && ($request->code != null)) {
            $today = date('Y-m-d');
            //$today = '2019-03-30';
            $event_id = Session::get('event_id');

            $query = DB::table('checkin_checkout')
                ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
                ->join('dealer_details', function ($join) {
                    $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                        ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
                })
                ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
                ->select(
                    'checkin_checkout.id as id', 
                    'checkin_checkout.event_date as date',
                    'dealers.dealer_dlr as dlr',
                    'dealers.dealer_name as dealer_name',
                    'sale_dealers.sale_dealer_code as id_sale',
                    'sale_dealers.sale_dealer_name as sale_dealer_name',
                    'sale_dealers.sale_dealer_nickname as nickname',
                    'sale_dealers.sale_dealer_tel as mobile',
                    'checkin_checkout.checkin_time as checkin',

                    'checkin_checkout.event_id as event_id',
                    'checkin_checkout.dealer_id as dealer_id',
                    'checkin_checkout.sale_dealer_id as sale_dealer_id',

                    'checkin_checkout.checkin_reason as note',
                    'dealer_details.dealer_detail_brief_time as brief_time',
                    'dealer_details.dealer_detail_checkout_time as checkout_time',
                    'dealer_details.dealer_detail_amount as quota'
                )
                ->where('sale_dealers.sale_dealer_status','=',1)
                ->where('sale_dealers.sale_dealer_code','=',$request->code)
                ->where('checkin_checkout.event_id','=',$event_id)
                ->where('checkin_checkout.event_date','=',$today); 

            $checkin_checkout = $query->first();

            //return collect($checkin_checkout);


            //found record
            if(isset($checkin_checkout->id_sale)){
                echo json_encode(array(
                    'status' => "0",
                    'message' => 'Alreadey Checkin.',
                    'data' => [
                        'id_sale' => $checkin_checkout->id_sale,
                        'sale_dealer_name' => $checkin_checkout->sale_dealer_name,
                        'nickname' => ($checkin_checkout->nickname)?$checkin_checkout->nickname:'N/A',
                        'mobile' => $checkin_checkout->mobile
                    ]
                ));
            }
            //not found record
            else{

                $query = DB::table('sale_dealers')
                    
                    ->join('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
                    ->join('events', 'dealers.event_id', '=', 'events.id')
                    ->join('dealer_details', 'sale_dealers.dealer_id', '=', 'dealer_details.dealer_id')
                    ->select(
                        'sale_dealers.sale_dealer_code as id_sale',
                        'sale_dealers.sale_dealer_name as sale_dealer_name',
                        'sale_dealers.sale_dealer_nickname as nickname',
                        'sale_dealers.sale_dealer_tel as mobile',
                        'dealer_details.dealer_detail_brief_time as brief_time',

                        'events.id as event_id',
                        'dealers.id as dealer_id',
                        'sale_dealers.id as sale_dealer_id',
                        'dealer_details.dealer_detail_amount as quota'
                    )
                    ->where('sale_dealers.sale_dealer_status','=',1)
                    ->where('sale_dealers.sale_dealer_code','=',$request->code)
                    ->where('events.id','=',$event_id)
                    ->where('dealer_details.dealer_detail_date','=',$today); 

                $checkin_checkout_2 = $query->first();

                //return collect($query);

                //วันนี้ ตรงกับช่วงเวลาใน event
                if(isset($checkin_checkout_2->id_sale)){
                    $time = date('H:i:s');
                    //$time = '13:00:00';
                    //$count_checkin = 3;

                    $query = DB::table('checkin_checkout')
                        ->where('dealer_id','=',$checkin_checkout_2->dealer_id)
                        ->where('event_id','=',$event_id)
                        ->where('event_date','=',$today); 

                    $count_checkin = count($query->get());

                    //check quota
                    if($count_checkin >= $checkin_checkout_2->quota){
                        
                        if(isset($request->over_reason) && ($request->over_reason != null)){
                            //in quota
                            $user_id = Sentinel::getUser()->id;
                            //insert checkin_checkout
                            $checkin_checkout_id = DB::table('checkin_checkout')->insertGetId([
                                'event_id' => $checkin_checkout_2->event_id, 
                                'dealer_id' => $checkin_checkout_2->dealer_id, 
                                'sale_dealer_id' => $checkin_checkout_2->sale_dealer_id,
                                'event_date' => $today,
                                'checkin_time' => $time,
                                'checkin_over_reason' => $request->over_reason,
                                'created_by' => $user_id,
                                'updated_by' => $user_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                            
                            echo json_encode(array(
                                'status' => "1",
                                'message' => 'Checkin Success.',
                                'data' => [
                                    'id_sale' => $checkin_checkout_2->id_sale,
                                    'sale_dealer_name' => $checkin_checkout_2->sale_dealer_name,
                                    'nickname' => ($checkin_checkout_2->nickname)?$checkin_checkout_2->nickname:'N/A',
                                    'mobile' => $checkin_checkout_2->mobile
                                ]
                            ));
                        }
                        else{
                            //out qouta
                            echo json_encode(array(
                                'status' => "0",
                                'message' => 'Checkin Over Quota.'
                            ));
                        }
                    }
                    //check late
                    else if($time > $checkin_checkout_2->brief_time){

                        if(isset($request->reason) && ($request->reason != null)){
                            $user_id = Sentinel::getUser()->id;
                            //insert checkin_checkout
                            $checkin_checkout_id = DB::table('checkin_checkout')->insertGetId([
                                'event_id' => $checkin_checkout_2->event_id, 
                                'dealer_id' => $checkin_checkout_2->dealer_id, 
                                'sale_dealer_id' => $checkin_checkout_2->sale_dealer_id,
                                'event_date' => $today,
                                'checkin_time' => $time,
                                'checkin_reason' => $request->reason,
                                'created_by' => $user_id,
                                'updated_by' => $user_id,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                            
                            echo json_encode(array(
                                'status' => "1",
                                'message' => 'Checkin Success.',
                                'data' => [
                                    'id_sale' => $checkin_checkout_2->id_sale,
                                    'sale_dealer_name' => $checkin_checkout_2->sale_dealer_name,
                                    'nickname' => ($checkin_checkout_2->nickname)?$checkin_checkout_2->nickname:'N/A',
                                    'mobile' => $checkin_checkout_2->mobile
                                ]
                            ));
                        }
                        else{
                            echo json_encode(array(
                                'status' => "0",
                                'message' => 'Checkin Late.'
                            ));
                        }
                    }
                    
                    else{
                        $user_id = Sentinel::getUser()->id;
                        //insert checkin_checkout
                        $checkin_checkout_id = DB::table('checkin_checkout')->insertGetId([
                            'event_id' => $checkin_checkout_2->event_id, 
                            'dealer_id' => $checkin_checkout_2->dealer_id, 
                            'sale_dealer_id' => $checkin_checkout_2->sale_dealer_id,
                            'event_date' => $today,
                            'checkin_time' => $time,
                            'created_by' => $user_id,
                            'updated_by' => $user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                        
                        echo json_encode(array(
                            'status' => "1",
                            'message' => 'Checkin Success.',
                            'data' => [
                                'id_sale' => $checkin_checkout_2->id_sale,
                                'sale_dealer_name' => $checkin_checkout_2->sale_dealer_name,
                                'nickname' => ($checkin_checkout_2->nickname)?$checkin_checkout_2->nickname:'N/A',
                                'mobile' => $checkin_checkout_2->mobile
                            ]
                        ));
                    } 
                }
                //วันนี้ ไม่ตรงกับช่วงเวลาใน event แสดงว่าไม่ได้ลงทะเบียน
                else{
                    echo json_encode(array(
                        'status' => "0",
                        'message' => 'Sale dealer is not registered.'
                    ));
                }
            }
        } 
        else {
            echo json_encode(array(
                'status' => "0",
                'message' => 'Please check your ID Sale.'
            ));
        }
    }

    public function setCheckOut(Request $request)
    {  
        if(isset($request->code) && ($request->code != null)) {
            $today = date('Y-m-d');
            //$today = '2019-03-29';
            $event_id = Session::get('event_id');

            $query = DB::table('checkin_checkout')
                ->join('dealers', 'checkin_checkout.dealer_id', '=', 'dealers.id')
                ->join('dealer_details', function ($join) {
                    $join->on('checkin_checkout.dealer_id', '=', 'dealer_details.dealer_id')
                        ->whereColumn('dealer_details.dealer_detail_date', 'checkin_checkout.event_date');
                })
                ->join('sale_dealers', 'checkin_checkout.sale_dealer_id', '=', 'sale_dealers.id')
                ->select(
                    'checkin_checkout.id as id', 
                    'checkin_checkout.event_date as date',
                    'dealers.dealer_dlr as dlr',
                    'dealers.dealer_name as dealer_name',
                    'sale_dealers.sale_dealer_code as id_sale',
                    'sale_dealers.sale_dealer_name as sale_dealer_name',
                    'sale_dealers.sale_dealer_nickname as nickname',
                    'sale_dealers.sale_dealer_tel as mobile',
                    'checkin_checkout.checkout_time as checkout',

                    'checkin_checkout.event_id as event_id',
                    'checkin_checkout.dealer_id as dealer_id',
                    'checkin_checkout.sale_dealer_id as sale_dealer_id',

                    'checkin_checkout.checkin_reason as note',
                    'dealer_details.dealer_detail_brief_time as brief_time',
                    'dealer_details.dealer_detail_checkout_time as checkout_time',
                    'dealer_details.dealer_detail_amount as quota'
                )
                ->where('sale_dealers.sale_dealer_status','=',1)
                ->where('sale_dealers.sale_dealer_code','=',$request->code)
                ->where('checkin_checkout.event_id','=',$event_id)
                ->where('checkin_checkout.event_date','=',$today); 

            $checkin_checkout = $query->first();

            //return collect($checkin_checkout);

            //not found record
            if(!isset($checkin_checkout->id)){

                $query = DB::table('sale_dealers')
                    ->join('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
                    ->join('events', 'dealers.event_id', '=', 'events.id')
                    ->join('dealer_details', 'sale_dealers.dealer_id', '=', 'dealer_details.dealer_id')
                    ->where('sale_dealers.sale_dealer_status','=',1)
                    ->where('dealers.event_id','=',Session::get('event_id'))
                    ->where('sale_dealers.sale_dealer_code','=',$request->code)
                    ->where('events.id','=',$event_id)
                    ->where('dealer_details.dealer_detail_date','=',$today); 

                $check_sale_dealer = $query->first();

                //เช็คว่า register มาหรือไม่
                if(isset($check_sale_dealer->id)){
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'The user has not checkin.'
                    ));
                }
                else{
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'Sale dealer is not registered.'
                    ));
                }
            }
            // found record
            else{

                $time = date('H:i:s');
                //$time = '21:00:00';
                $now = date('Y-m-d H:i:s');

                if($checkin_checkout->checkout == null){
                    if($time < $checkin_checkout->checkout_time){

                        if(isset($request->reason) && ($request->reason != null)){
                            $user_id = Sentinel::getUser()->id;
                            //update checkin_checkout
                            $update_checkout = DB::table('checkin_checkout')
                                    ->where('event_id','=',$checkin_checkout->event_id)
                                    ->where('dealer_id','=',$checkin_checkout->dealer_id)
                                    ->where('sale_dealer_id','=',$checkin_checkout->sale_dealer_id)
                                    ->where('event_date','=',$today)
                                    ->update([
                                        'checkout_time' => $time,
                                        'checkout_reason' => $request->reason, 
                                        'updated_by' => $user_id, 
                                        'updated_at' => $now
                                    ]);
                            
                            echo json_encode(array(
                                'status' => 1,
                                'message' => 'Checkout Success.',
                                'data' => [
                                    'id_sale' => $checkin_checkout->id_sale,
                                    'sale_dealer_name' => $checkin_checkout->sale_dealer_name,
                                    'nickname' => ($checkin_checkout->nickname)?$checkin_checkout->nickname:'N/A',
                                    'mobile' => $checkin_checkout->mobile
                                ]
                            ));
                        }
                        else{
                            echo json_encode(array(
                                'status' => 0,
                                'message' => 'Checkout Early.',
                                'data' => [
                                    'id_sale' => $checkin_checkout->id_sale,
                                    'sale_dealer_name' => $checkin_checkout->sale_dealer_name,
                                    'nickname' => ($checkin_checkout->nickname)?$checkin_checkout->nickname:'N/A',
                                    'mobile' => $checkin_checkout->mobile
                                ]

                            ));
                        }
                    }
                    else{
                        $user_id = Sentinel::getUser()->id;
                        
                        //update checkin_checkout
                        $update_checkout = DB::table('checkin_checkout')
                                ->where('event_id','=',$checkin_checkout->event_id)
                                ->where('dealer_id','=',$checkin_checkout->dealer_id)
                                ->where('sale_dealer_id','=',$checkin_checkout->sale_dealer_id)
                                ->where('event_date','=',$today)
                                ->update([
                                    'checkout_time' => $time, 
                                    'updated_by' => $user_id, 
                                    'updated_at' => $now
                                ]);
                        
                        echo json_encode(array(
                            'status' => 1,
                            'message' => 'Checkout Success.',
                            'data' => [
                                'id_sale' => $checkin_checkout->id_sale,
                                'sale_dealer_name' => $checkin_checkout->sale_dealer_name,
                                'nickname' => ($checkin_checkout->nickname)?$checkin_checkout->nickname:'N/A',
                                'mobile' => $checkin_checkout->mobile
                            ]
                        ));
                    }
                }
                else{
                    echo json_encode(array(
                        'status' => 0,
                        'message' => 'Already Checkout.',
                        'data' => [
                            'id_sale' => $checkin_checkout->id_sale,
                            'sale_dealer_name' => $checkin_checkout->sale_dealer_name,
                            'nickname' => ($checkin_checkout->nickname)?$checkin_checkout->nickname:'N/A',
                            'mobile' => $checkin_checkout->mobile
                        ]
                    ));
                } 
            }
        } 
        else {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Please check your ID Sale.'
            ));
        }
    }
}
