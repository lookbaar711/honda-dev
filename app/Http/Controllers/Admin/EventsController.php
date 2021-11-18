<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Request;
use App\Event;
use DOMDocument;
use Response;
use Sentinel;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Log;
use Redirect;

class EventsController extends Controller
{
    public function index()
    {
        return view('admin.events.index');
    }

    //Table Data to index event
    public function data(Request $request)
    {

      // [original:protected]
        $query = DB::table('events')
            ->select(
                'id as id',
                'event_name as name',
                'event_start_date as start_date',
                'event_end_date as end_date',
                'event_status as event_status',
                'created_at as created_at'
            )
            ->where('event_status', '=', 1);

        if (isset($request->text_search) && ($request->text_search != '')) {
            $query->where([
                ['event_name', 'like', '%' . $request->text_search . '%', 'or'],
                ['event_start_date', 'like', '%' . $request->text_search . '%', 'or'],
                ['event_end_date', 'like', '%' . $request->text_search . '%', 'or']
            ]);
        }
        if (isset($request->event_status_search)) {
            $current_date = date('Y-m-d', strtotime(now()));

            //on
            if ($request->event_status_search == 1) {
                $query->where('event_start_date', '<=', $current_date)
                    ->where('event_end_date', '>=', $current_date);
            }
            //off
            else {
                $query->whereRaw(
                    "(event_start_date > '" . $current_date . "' AND event_end_date > '" . $current_date . "') OR
                    (event_start_date < '" . $current_date . "' AND event_end_date < '" . $current_date . "')"
                );
            }
        }

        $query->orderby('created_at','desc');
        $event = $query->get();

        return DataTables::of($event)
            ->addColumn('dlr', function ($data) {
                $dlr = $this->countDealer($data->id);
                return number_format($dlr);
            })
            ->addColumn('sale', function ($data) {
                $sale = $this->countSaleDealer($data->id);
                return number_format($sale);
            })
            ->editColumn('event_status', function ($data) {
                $s_date = str_replace('/', '-', $data->start_date);
                $e_date = str_replace('/', '-', $data->end_date);
                $event_start_date = date("Y-m-d", strtotime($s_date));
                $event_end_date = date("Y-m-d", strtotime($e_date));
                $current_date = date('Y-m-d', strtotime(now()));

                //on
                if (($current_date >= $event_start_date) && ($current_date <= $event_end_date)) {
                    $event_status = 1;
                } else {
                    $event_status = 0;
                }

                return ($event_status == 1) ? '<span class="fa fa-circle dot-green"></span><span class="fnt-14">&nbsp;&nbsp; On</span>' : '<span class="fa fa-circle dot-gray"></span><span class="fnt-14">&nbsp;&nbsp; Off</span>';
            })
            ->addColumn('actions', function ($data) {

                // $role = Sentinel::getUser()->roles[0]->slug;
                $role = 'admin';
                $status_process = Sentinel::getUser()->status_process;
                if ($status_process == 1) {
                  $role = 'user';
                }
                
                //on mouse over
                if ($role == 'admin') {
                    $actions = '<div class="nav-item dropdown">
                    <button class="btn btn-sm button-test nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i> จัดการ</button>
                                <div class="dropdown-menu">';
                    $actions .= '<div style="text-align: left;">
                                <a class="dropdown-item" href=' . route('admin.events.edit', $data->id) . '><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> แก้ไข Event</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                <a class="dropdown-item" href="events/' . $data->id . '/dealers"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ Dealer</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                <a class="dropdown-item" href="events/' . $data->id . '/sale_dealers"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ Sale Dealer</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                <a class="dropdown-item" href="events/' . $data->id . '/checkin_checkout"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ CHECK-IN <br> /CHECK-OUT</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                    <a class="dropdown-item" href="events/' . $data->id . '/preemptions"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการใบจอง</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                    <a class="dropdown-item" href="events/' . $data->id . '/reports"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> รายงาน</a>
                                </div>';

                    $actions .= '</div></div>';
                } else if ($role == 'user') {
                    $actions = '<div class="nav-item dropdown">
                    <button class="btn btn-sm button-test nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i> จัดการ</button>
                                <div class="dropdown-menu">';
            
                    $actions .= '<div style="text-align: left;">
                                <a class="dropdown-item" href="events/' . $data->id . '/sale_dealers"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ Sale Dealer</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                <a class="dropdown-item" href="events/' . $data->id . '/checkin_checkout"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ CHECK-IN <br> /CHECK-OUT</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                    <a class="dropdown-item" href="events/' . $data->id . '/preemptions"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการใบจอง</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                    <a class="dropdown-item" href="events/' . $data->id . '/reports"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> รายงาน</a>
                                </div>';

                    $actions .= '</div></div>';


                    /*
                    //on mouse over 
                    $actions = '<div class="dropdown">
                                <button class="btn btn-sm button-test"><i class="fa fa-cog" aria-hidden="true"></i> จัดการ</button>
                                <div class="dropdown-content">';
                    $actions .= '<div style="text-align: left;">
                                <a class="dropdown-item" href="events/' . $data->id . '/sale_dealers"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ Sale Dealer</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                <a href="events/' . $data->id . '/checkin_checkout"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการ CHECK-IN <br>/CHECK-OUT</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                    <a href="events/' . $data->id . '/preemptions"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> จัดการใบจอง</a>
                                </div>';
                    $actions .= '<div style="text-align: left;">
                                    <a class="dropdown-item" href="events/' . $data->id . '/reports"><i class="fa fa-cog" aria-hidden="true" style="text-align: center;"></i> รายงาน</a>
                                </div>';

                    $actions .= '</div></div>';
                    */

                }

                return $actions;
            })
            ->rawColumns(['dlr', 'sale', 'event_status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function edit(Event $event)
    {
        $s_date = str_replace('-', '/', $event->event_start_date);
        $e_date = str_replace('-', '/', $event->event_end_date);
        $event_start_date = date("d/m/Y", strtotime($s_date));
        $event_end_date = date("d/m/Y", strtotime($e_date));
        $event->event_daterange = $event_start_date . ' - ' . $event_end_date;

        return view('admin.events.edit', [
            'event' => $event,
        ]);
    }

    public function getModalDelete(Event $event)
    {
        $model = 'event';
        $confirm_route = $error = null;
        try {
            $confirm_route = route('admin.events.delete', ['id' => $event->id]);
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        } catch (GroupNotFoundException $e) {

            $error = trans('events/message.error.destroy', compact('id'));
            return view('admin.layouts.modal_confirmation', compact('error', 'model', 'confirm_route'));
        }
    }




    //Insert
    public function store(EventRequest $request)
    {

        /*
        $s_date = str_replace('/', '-', $request->event_start_date);
        $e_date = str_replace('/', '-', $request->event_end_date);
        $event_start_date = date("Y-m-d", strtotime($s_date));
        $event_end_date = date("Y-m-d", strtotime($e_date));
        $current_date = date('Y-m-d',strtotime(now()));

        if(($current_date >= $event_start_date) && ($current_date <= $event_end_date)){
            $event_status = 1;
        }
        else if(($current_date < $event_start_date) && ($current_date < $event_end_date)){
            $event_status = 2;
        }
        else if(($current_date > $event_start_date) && ($current_date > $event_end_date)){
            $event_status = 0;
        }
        */


        $date = explode(" - ", $request->event_daterange);
        $s_date = str_replace('/', '-', $date[0]);
        $e_date = str_replace('/', '-', $date[1]);
        $event_start_date = date("Y-m-d", strtotime($s_date));
        $event_end_date = date("Y-m-d", strtotime($e_date));
        $current_date = date('Y-m-d', strtotime(now()));

        if (($current_date >= $event_start_date) && ($current_date <= $event_end_date)) {
            $event_status = 1;
        } else if (($current_date < $event_start_date) && ($current_date < $event_end_date)) {
            $event_status = 2;
        } else if (($current_date > $event_start_date) && ($current_date > $event_end_date)) {
            $event_status = 0;
        }

        $event = new Event();
        $event->event_name = $request->event_name;
        $event->event_location = $request->event_location;
        $event->event_start_date = $event_start_date;
        $event->event_end_date = $event_end_date;
        $event->event_status = 1;
        $event->created_by = Sentinel::getUser()->id;
        $event->updated_by = Sentinel::getUser()->id;
        $event->save();

        if ($event->id) {
            $properties = array(
                'id' => $event->id,
                'event_name' => $request->event_name,
                'event_location' => $request->event_location,
                'event_start_date' => $request->event_start_date,
                'event_status' => $event->event_status
            );

            $full_name = Sentinel::getUser()->full_name;

            //Activity log
            activity($full_name)
                ->performedOn($event)
                ->causedBy($event)
                ->withProperties($properties)
                ->log('Create Event');
            //activity log ends
                
            return redirect('admin/events')->with('response_success', trans('events/message.success.create'));
        } else {
            return Redirect::route('admin/events')->withInput()->with('response_error', trans('events/message.error.create'));
        }
    }

    //Update
    public function update(EventRequest $request, Event $event)
    {
        $date = explode(" - ", $request->event_daterange);
        $s_date = str_replace('/', '-', $date[0]);
        $e_date = str_replace('/', '-', $date[1]);
        $event_start_date = date("Y-m-d", strtotime($s_date));
        $event_end_date = date("Y-m-d", strtotime($e_date));
        $current_date = date('Y-m-d', strtotime(now()));

        $event->event_name = $request->event_name;
        $event->event_location = $request->event_location;
        $event->event_start_date = $event_start_date;
        $event->event_end_date = $event_end_date;
        $event->updated_by = Sentinel::getUser()->id;

        if ($event->update()) {
            $properties = array(
                'id' => $event->id,
                'event_name' => $request->event_name,
                'event_location' => $request->event_location,
                'event_start_date' => $request->event_start_date
            );

            $full_name = Sentinel::getUser()->full_name;

            //Activity log
            activity($full_name)
                ->performedOn($event)
                ->causedBy($event)
                ->withProperties($properties)
                ->log('Update Event');
            //activity log ends

            return redirect('admin/events')->with('response_success', trans('events/message.success.update'));
        } else {
            return Redirect::route('admin/events')->withInput()->with('response_error', trans('events/message.error.update'));
        }
    }

    //Delete
    public function destroy(Event $event)
    {
        $event->event_status = 0;

        if ($event->update()) {
            return redirect('admin/events')->with('response_success', trans('events/message.success.delete'));
        } else {
            return Redirect::route('admin/events')->withInput()->with('response_error', trans('events/message.error.delete'));
        }
    }

    public function countDealer($event_id)
    {
        $dealer = DB::table('dealers')
            ->where('event_id', '=', $event_id)
            ->where('dealer_status', '=', 1)
            ->get();

        return count($dealer);
    }

    public function countSaleDealer($event_id)
    {
        $sale_dealer = DB::table('sale_dealers')
            ->join('dealers', 'sale_dealers.dealer_id', '=', 'dealers.id')
            ->where('dealers.event_id', '=', $event_id)
            ->where('sale_dealers.sale_dealer_status', '=', 1)
            ->get();

        return count($sale_dealer);
    }
}
