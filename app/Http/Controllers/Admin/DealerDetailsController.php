<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DealerDetail; 
use DOMDocument;
use Response;
use Sentinel;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Log;
use Redirect;
use Session;

class DealerDetailsController extends Controller
{
    public function index()
    { 
        
        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$event_id)
            ->first();

        $dealer = DB::table('dealers')
            ->select('id','dealer_dlr','dealer_name','dealer_zone','dealer_area')
            ->where('id','=',$dealer_id)
            ->first();

        return view('admin.dealer_details.index',[
            'event' => $event,
            'dealer' => $dealer
        ]);  
    }

    
    public function dealerDetailIndex($event_id, $dealer_id)
    { 
        //return $event_id.' -- '.$dealer_id;

        $event = DB::table('events')
            ->select('id','event_name')
            ->where('id','=',$event_id)
            ->first();

        $dealer = DB::table('dealers')
            ->select('id','dealer_dlr','dealer_name','dealer_zone','dealer_area')
            ->where('id','=',$dealer_id)
            ->first();

        return view('admin.dealer_details.index',[
            'event' => $event,
            'dealer' => $dealer
        ]);   
    }
    

    //Table Data to index dealer
    public function data(Request $request)
    {

        $query = DB::table('dealer_details')
            ->join('dealers', 'dealer_details.dealer_id', '=', 'dealers.id')
            ->select(
                'dealer_details.id as id', 
                'dealer_details.dealer_detail_date as date',
                'dealer_details.dealer_detail_type as type',
                'dealer_details.dealer_detail_amount as amount',
                'dealer_details.dealer_detail_brief_time as brief_time',
                'dealer_details.dealer_detail_checkout_time as checkout_time',
                'dealer_details.dealer_id as dealer_id',

                'dealers.dealer_dlr as dlr',
                'dealers.dealer_name as name',
                'dealers.dealer_zone as zone',
                'dealers.dealer_area as area'
            )
            ->where('dealer_details.dealer_detail_status','=',1);

        if(isset($request->dealer_id)){
            $dealer_id = $request->dealer_id;

            $query->where('dealer_details.dealer_id','=',$request->dealer_id);
        }

        
        if(isset($request->text_search) && ($request->text_search != '')){
            $query->where([
                ['dealer_details.dealer_detail_date','like','%'.$request->text_search.'%','or'],
                ['dealer_details.dealer_detail_type','like','%'.$request->text_search.'%','or'],
                ['dealer_details.dealer_detail_amount','like','%'.$request->text_search.'%','or'],
                ['dealer_details.dealer_detail_brief_time','like','%'.$request->text_search.'%','or'],
                ['dealer_details.dealer_detail_checkout_time','like','%'.$request->text_search.'%','or']
            ]);
        }
        if(isset($request->type_search)){
            $query->where('dealer_details.dealer_detail_type','like',$request->type_search);
        }

        $query->orderby('dealer_details.created_at','desc');
        $dealer_detail = $query->get();

        return DataTables::of($dealer_detail)
            ->editColumn('amount', function ($data) {  
                return number_format($data->amount);
            })
            ->addColumn('actions', function ($data) {
                
                // $actions = '<a href=' . route('admin.dealer_details.edit', $data->id) . '><i class="fa fa-cog" aria-hidden="true" style="color: #000;"></i></a>';

                $actions = '<a data-toggle="modal" data-target="#edit_dealer_deatail" href=' . route('admin.dealer_details.edit', $data->id) . '><i class="fa fa-cog" aria-hidden="true" style="color: #000;"></i></a>';

                $actions = '<a href="#" data-toggle="modal"><i class="fa fa-cog editDealerDetail" aria-hidden="true" style="color:#000;" title="แก้ไข Dealer Detail"
                    data_id="'.$data->id.'"
                    data_date="'.$data->date.'"
                    data_type="'.$data->type.'"
                    data_amount="'.$data->amount.'"
                    data_brief_time="'.$data->brief_time.'"
                    data_checkout_time="'.$data->checkout_time.'"
                    data_dealer_id="'.$data->dealer_id.'"
                    data_dlr="'.$data->dlr.'"
                    data_name="'.$data->name.'"
                    data_zone="'.$data->zone.'"
                    data_area="'.$data->area.'" ></i></a>';

                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    } 

    public function edit(DealerDetail $dealer_detail)
    {
        //return $dealer_detail->dealer_id;

        $dealer = DB::table('dealers')
            ->select('id','dealer_dlr','dealer_name','dealer_zone','dealer_area')
            ->where('id','=',$dealer_detail->dealer_id)
            ->first();

        return view('admin.dealer_details.edit', [
            'event_id' => Session::get('event_id'),
            'dealer' => $dealer,
            'dealer_detail' => $dealer_detail
        ]); 
    }

    //Update
    public function update(Request $request, DealerDetail $dealer_detail)
    {
        //return $request;

        $event_id = Session::get('event_id');

        $dealer_detail->dealer_detail_amount = $request->dealer_detail_amount;
        $dealer_detail->dealer_detail_brief_time = $request->dealer_detail_brief_time;
        $dealer_detail->dealer_detail_checkout_time = $request->dealer_detail_checkout_time;
        $dealer_detail->updated_by = Sentinel::getUser()->id;

        if ($dealer_detail->update()) {
            $properties = array(
                'id' => $dealer_detail->id,
                'dealer_detail_amount' => $request->dealer_detail_amount,
                'dealer_detail_brief_time' => $request->dealer_detail_brief_time,
                'dealer_detail_checkout_time' => $request->dealer_detail_checkout_time
            );

            $full_name = Sentinel::getUser()->full_name;

            //Activity log
            activity($full_name)
               ->performedOn($dealer_detail)
               ->causedBy($dealer_detail)
               ->withProperties($properties)
               ->log('Update Dealer Detail');
            //activity log ends

            return redirect('admin/events/'.$event_id.'/dealers/'.$dealer_detail->dealer_id.'/dealer_details')->with('response_success', trans('dealer_details/message.success.update'));
        }
        else{
            return redirect('admin/events/'.$event_id.'/dealers/'.$dealer_detail->dealer_id.'/dealer_details')->withInput()->with('response_error', trans('dealer_details/message.error.update'));
        }
    }

   

}
