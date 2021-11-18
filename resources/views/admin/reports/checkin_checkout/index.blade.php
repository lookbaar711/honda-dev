@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('events/title.events_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
  <style media="screen">
    .buttons-excel {
      display: none !important;
    }
  </style>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

<!-- datatable -->
<!-- <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> -->
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

<!-- datepicker -->
<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .daterange-input{
        background: url(../../../assets/img/calendar_icon.png) no-repeat right 5px center;
        background-color: #FFFFFF;
    }

   /* div#table_preemption_processing {
        background-color: #fff;
        padding-top: 6px;
        position: relative;
    }

    div#table_checkin_checkout_processing {
        background-color: #fff;
        padding-top: 6px;
        position: relative;
    }

    div#table_sale_dealer_processing {
        background-color: #fff;
        padding-top: 6px;
        position: relative;
    }

    div#table_dealer_checkin_processing {
        background-color: #fff;
        padding-top: 6px;
        position: relative;
    }*/

    div#table_dealer_checkin_info{
        display: none;
    }

    div#table_checkin_checkout_info{
        display: none;
    }

    div#table_sale_dealer_info{
        display: none;
    }

    div#table_preemption_info{
        display: none;
    }

    div#table_dealer_checkin_filter{
        display: none;
    }

    div#table_checkin_checkout_filter{
        display: none;
    }

    div#table_sale_dealer_filter{
        display: none;
    }

    div#table_preemption_filter{
        display: none;
    }


</style>

@stop

{{-- Page content --}}
@section('content')
<section class="content">
    <div class="row">
        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20" align="left">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="active fs-20" style="color: #7b7e82;">รายงาน</a>
                </td>
            </tr>
        </table>
        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary">
            <div class="card-body">

                <div class="card-body">
                    <div class="bg-gray" style="padding-bottom: 20px; padding-top: 20px;">
                        <table border="0">
                            <tr>
                                <td align="right">
                                    <div>
                                        <label >ค้นหารายงาน :</label>
                                         <select id="list_report" class="form-report col-sm-6">
                                          <!-- <option value="0">กรุณาเลือก</option> -->
                                          <option value="checkin_checkouts">รายงานการ CHECK-IN / CHECK-OUT</option>
                                          <option value="sale_dealers">รายงานข้อมูลของ Sale Dealer</option>
                                          <option value="preemptions">รายงานใบจอง</option>
                                          <option value="dealer_checkins">รายงานข้อมูลของ Dealer Checkin</option>
                                        </select>
                                    </div>
                                    <!-- <label >ค้นหารายงาน :</label> -->
                                </td>
                                 <td align="right" style="padding-right: 10px;">
                                    <button class="dt-buttons btn btn-lg button-green" tabindex="0" aria-controls="table_checkin_checkout"  type="button" onclick="$('.buttons-excel').click()" id="export_but"><i class="fa fa-upload" aria-hidden="true"></i> Export Excel</button>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>

                <hr class="ml-3 mr-3">

                <!-- Check-in Check-out -->
                <div id="checkin_checkouts">
                    <div class="form-group">
                        <div class="col-12 row">
                            <div class="col-4 col-sm-4 col-md-3  col-lg-3">
                                <label for="input">@lang('events/form.text_search') : </label>
                                <input type="text" class="form-control" name="text_search_checkin_checkout" id="text_search_checkin_checkout" placeholder="ค้นหาจาก" value=""/>
                            </div>
                            <div class="col-2 col-sm-2 col-md-3  col-lg-3">
                                <label for="input">Event : </label>
                                <!-- <select class="form-control required column_checkin_checkout" name="event_search_checkin_checkout" data-column="14" id="col14_filter"> -->
                                <select class="form-control required" name="event_search_checkin_checkout" id="event_search_checkin_checkout">
                                    @foreach($event_all as $eve)
                                        <option value="{{ $eve->id }}" <?php echo ($event->id == $eve->id)? 'selected':''; ?>>{{ $eve->event_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 col-sm-2 col-md-3  col-lg-3">
                                <label for="input">@lang('checkin_checkout/form.daterange_search') : </label>
                                <input type="text" class="form-control daterange-input daterange_search" name="daterange_search" id="daterange_search" placeholder="วว/ดด/ปป - วว/ดด/ปป" autocomplete="off"/>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">DLR : </label>
                                <select class="form-control required column_checkin_checkout" name="dlr_search_checkin_checkout" data-column="2" id="col2_filter">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_dlr as $value)
                                      <option value="{{$value['dealer_dlr']}}">{{$value['dealer_dlr']}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">Zone : </label>
                                <select class="form-control required column_checkin_checkout" name="zone_search_checkin_checkout" data-column="3" id="col3_filter">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_zone as $value)
                                      <option value="{{$value['dealer_zone']}}">{{$value['dealer_zone']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">Area : </label>
                                <select class="form-control required column_checkin_checkout" name="area_search_checkin_checkout" data-column="4" id="col4_filter">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_area as $value)
                                      <option value="{{$value['dealer_area']}}">{{$value['dealer_area']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-outline-dark" id="reset_button_checkin_checkout" style="margin-left: 5px; height: 37px; margin-top: 21px;" onclick="reports.reData_checkin_checkout()">
                                    <span class="fa fa-refresh"></span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                        <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
                        <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table_checkin_checkout"></table>
                    </div>

                </div>

                <!-- Sale Dealer -->
                <div id="sale_dealers" style="display: none;">
                    <div class="form-group">
                        <div class="col-12 row">
                            <div class="col-4 col-sm-4 col-md-2  col-lg-2">
                                <label for="input">@lang('events/form.text_search') : </label>
                                <input type="text" class="form-control" name="text_search_sale_dealer" id="text_search_sale_dealer" placeholder="ค้นหาจาก" value=""/>
                            </div>
                            <div class="col-2 col-sm-2 col-md-3  col-lg-3">
                                <label for="input">Event : </label>
                                    <select class="form-control required" name="event_search_sale_dealer" id="event_search_sale_dealer">
                                    @foreach($event_all as $eve)
                                        <option value="{{ $eve->id }}" <?php echo ($event->id == $eve->id)? 'selected':''; ?> >{{ $eve->event_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">DLR : </label>
                                <select class="form-control required column_sale_dealer" name="dlr_search_sale_dealer" data-column="10" id="col10_filter">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_dlr as $value)
                                      <option value="{{$value['dealer_dlr']}}">{{$value['dealer_dlr']}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">Zone : </label>
                                <select class="form-control required column_sale_dealer" name="zone_search_sale_dealer" data-column="8" id="col8_filter">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_zone as $value)
                                      <option value="{{$value['dealer_zone']}}">{{$value['dealer_zone']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">Area : </label>
                                <select class="form-control required column_sale_dealer" name="area_search_sale_dealer" data-column="9" id="col9_filter">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_area as $value)
                                      <option value="{{$value['dealer_area']}}">{{$value['dealer_area']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-outline-dark" onclick="reports.reData_sale_dealer()" style="margin-left: 5px; height: 37px; margin-top: 19px;">
                                    <span class="fa fa-refresh"></span>
                            </button>
                        </div>
                    </div>

                     <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                        <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
                        <table class="table table-hover table-striped table-condensed flip-content" id="table_sale_dealer"></table>
                    </div>

                </div>

                <!-- Preemption -->
                <div id="preemptions" style="display: none;">
                    <div class="form-group">
                        <div class="col-12 row">
                            <div class="col-4 col-sm-4 col-md-2  col-lg-2">
                                <label for="input">@lang('events/form.text_search') : </label>
                                <input type="text" class="form-control" name="text_search_preemption" id="text_search_preemption" placeholder="ค้นหาจาก" value=""/>
                            </div>
                            <div class="col-2 col-sm-2 col-md-3  col-lg-3">
                                <label for="input">Event : </label>
                                <select class="form-control required" name="event_search_preemption" id="event_search_preemption">
                                    @foreach($event_all as $eve)
                                        <option value="{{ $eve->id }}" <?php echo ($event->id == $eve->id)? 'selected':''; ?>>{{ $eve->event_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 col-sm-2 col-md-3  col-lg-3">
                                <label for="input">@lang('checkin_checkout/form.daterange_search') : </label>
                                <input type="text" class="form-control daterange-input daterange_search_preemption" name="daterange_search_preemption" id="daterange_search_preemption" placeholder="วว/ดด/ปป - วว/ดด/ปป" autocomplete="off"/>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">DLR : </label>
                                <select class="form-control required column_preemption" name="dlr_search_preemption" data-column="7" id="col7_filterPreemption">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_dlr as $value)
                                      <option value="{{$value['dealer_dlr']}}">{{$value['dealer_dlr']}}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">Zone : </label>
                                <select class="form-control required column_preemption" name="zone_search_preemption" data-column="8" id="col8_filterPreemption">
                                    <option value="">@lang('events/form.select_status')</option>
                                     @foreach ($dealer_zone as $value)
                                      <option value="{{$value['dealer_zone']}}">{{$value['dealer_zone']}}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2  col-lg-2" style="padding-top: 5px;">
                                <label for="input">Area : </label>
                                <select class="form-control required column_preemption" name="area_search_preemption" data-column="9" id="col9_filterPreemption">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_area as $value)
                                      <option value="{{$value['dealer_area']}}">{{$value['dealer_area']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-2 col-sm-2 col-md-2  col-lg-2" style="padding-top: 5px;">
                                <label for="input">Model Car : </label>
                                <select class="form-control required column_preemption" name="model_car" data-column="4" id="col4_filterPreemption">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($model_car as $value)
                                      <option value="{{$value['model_car_name']}}">{{$value['model_car_name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-2 col-sm-2 col-md-2  col-lg-2" style="padding-top: 5px;">
                                <label for="input">Sub Model Car : </label>
                                <select class="form-control required column_preemption" name="sub_model_car" data-column="5" id="col5_filterPreemption">
                                    <option value="">@lang('events/form.select_status')</option>
                                </select>
                            </div>

                             <div class="col-2 col-sm-2 col-md-2  col-lg-2" style="padding-top: 5px;">
                                <label for="input">Type : </label>
                                <select class="form-control required column_preemption" name="area_search_preemption" data-column="6" id="col6_filterPreemption">
                                    <option value="">@lang('events/form.select_status')</option>
                                    <option value="TB">Turbo</option>
                                    <option value="NORMAL">Normal</option>
                                </select>
                            </div>

                             <div class="col-2 col-sm-2 col-md-2  col-lg-2" style="padding-top: 5px;">
                                <label for="input">สถานะใบจอง : </label>
                                <select class="form-control required column_preemption" name="area_search_preemption" data-column="15" id="col15_filterPreemption">
                                    <option value="">@lang('events/form.select_status')</option>
                                    <option value="1">เบิกใบจอง</option>
                                    <option value="2">คืนใบจอง</option>
                                    <option value="3">ยกเลิกใบจอง</option>
                                </select>
                            </div>

                            <button class="btn btn-outline-dark" id="reset_button_preemption" style="margin-left: 5px; height: 37px; margin-top: 26px;" onclick="reports.reData_preemption()">
                                    <span class="fa fa-refresh"></span>
                            </button>
                        </div>
                    </div>

                     <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                        <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
                        <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table_preemption"></table>
                    </div>
                </div>
                <!-- Preemption -->

                <!-- Dealer Checkin -->
                <div id="dealer_checkins" style="display: none;">
                    <div class="form-group">
                        <div class="col-12 row">
                            <div class="col-4 col-sm-4 col-md-2  col-lg-2">
                                <label for="input">@lang('events/form.text_search') : </label>
                                <input type="text" class="form-control" name="text_search_dealer_checkin" id="text_search_dealer_checkin" placeholder="ค้นหาจาก" value=""/>
                            </div>
                            <div class="col-2 col-sm-2 col-md-3  col-lg-3">
                                <label for="input">Event : </label>
                                <select class="form-control required get_dlr_dealer_checkin" name="event_search_dealer_checkin" id="event_search_dealer_checkin">
                                    @foreach($event_all as $eve)
                                        <option value="{{ $eve->id }}" <?php echo ($event->id == $eve->id)? 'selected':''; ?>>{{ $eve->event_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2 col-sm-2 col-md-3  col-lg-3">
                                <label for="input">@lang('checkin_checkout/form.daterange_search') : </label>
                                <input type="text" class="form-control daterange-input daterange_search_dealer_checkin" name="daterange_search_dealer_checkin" id="daterange_search_dealer_checkin" placeholder="วว/ดด/ปป - วว/ดด/ปป" autocomplete="off"/>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2  col-lg-2">
                                <label for="input">DLR : </label>
                                <select class="form-control required column_dealer_checkin dlr_dealer_checkin" name="dlr_search_dealer_checkin" data-column="2" id="col2_filterDealercheckin">
                                    <option value="">@lang('events/form.select_status')</option>
                                    @foreach ($dealer_dlr as $value)
                                      <option value="{{$value['dealer_dlr']}}">{{$value['dealer_dlr']}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn btn-outline-dark" id="reset_button_dealer_checkin" style="margin-left: 5px; height: 37px; margin-top: 20px;" onclick="reports.reData_dealer_checkin()">
                                    <span class="fa fa-refresh"></span>
                            </button>
                        </div>
                    </div>

                     <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                        <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
                        <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table_dealer_checkin"></table>
                    </div>
                </div>
                <!-- Dealer Checkin -->
            </div>
        </div>
    </div>
    </div>
</section>


@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>

<!-- datapicker -->
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>

<!-- export excel -->
<!-- {{-- <script src="https://code.jquery.com/jquery-3.3.1.js" type="text/javascript"></script> --}}
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script> -->


<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script> -->

<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js" type="text/javascript"></script>



<script type="text/javascript">

var reports = {

    table_checkin_checkout : $('#table_checkin_checkout'),
    table_sale_dealer : $('#table_sale_dealer'),
    table_preemption : $('#table_preemption'),
    table_dealer_checkin : $('#table_dealer_checkin'),
    ////////////////////////////////////////////// table_checkin_checkout //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    tableListcheckin_checkout: function () {

        reports.table_checkin_checkout = $('#table_checkin_checkout').DataTable({

            "drawCallback": function( settings ) {
                document.getElementById('table_checkin_checkout').style='';

                for (var i = 0; i < $('#table_checkin_checkout .sorting_disabled').length; i++) {
                  $('#table_checkin_checkout .sorting_disabled')[i].style='';
                }
            },

            "preDrawCallback": function( settings ) {
                document.getElementById('table_checkin_checkout').style='';
                for (var i = 0; i < $('#table_checkin_checkout .sorting_disabled').length; i++) {
                  $('#table_checkin_checkout .sorting_disabled')[i].style='';
                }
            },

            processing: true,
            // serverSide: true,   
            destroy: true,
            ordering: false,
            dom: 'lBfrtip',
            buttons: [
                {
                  extend: 'excel',
                  title: 'รายงานการ CHECK-IN CHECK-OUT',
                  exportOptions: {
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                        //columns: [ 0, 1, 2, 5, 6, 7, 8, 9, 10, 11, 12, 13] 
                        //Your Colume value those you want
                     }
                }
            ],
            ajax: {
              "url": window.location.origin+'/admin/reports/DataCheckinCheckoutReport',
              "type": "post",
              'data': {
                    event_id : function() {
                      return $('#event_search_checkin_checkout').val();
                    },
                    daterange_search : function() {
                      return $('#daterange_search').val();
                    },
                },
              headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
              }
            },
            columns:
            [
              { title : '#' ,data: (a,b,c,d,e) => {
                return (reports.table_checkin_checkout.page.info().start+d.row+1);
              } },

              { title : 'Date' ,data: (e) => {
                return e.event_date;
              }, name: 'event_date' },

              { title : 'Code' ,data: (e) => {
                  return e.dealer_legacy_code;
              }, name: 'dealer_legacy_code', visible: false },

              { title : 'DLR' ,data: (e) => {
                  return e.dealer_dlr;
              }, name: 'dealer_dlr' },

              { title : 'Zone' ,data: (e) => {
                return e.dealer_zone;
              }, name: 'dealer_zone', visible: false },

              { title : 'Area' ,data: (e) => {
                return e.dealer_area;
              }, name: 'dealer_area', visible: false },

              { title : 'DLR Name' ,data: (e) => {
                  return e.dealer_name;
              }, name: 'dealer_name' },

              { title : 'ID Sale' ,data: (e) => {
                  return e.sale_dealer_code;
              }, name: 'sale_dealer_code' },

              { title : 'Sale Name' ,data: (e) => {
                  return e.sale_dealer_name;
              }, name: 'sale_dealer_name' },

              { title : 'Nickname' ,data: (e) => {
                  return e.sale_dealer_nickname;
              }, name: 'sale_dealer_nickname' },

              { title : 'Mobile' ,data: (e) => {
                  return e.sale_dealer_tel;
              }, name: 'sale_dealer_tel' },

              { title : 'CHECK-IN TIME' ,data: (e) => {
                  return e.checkin_time;
              }, name: 'checkin_time' },

              { title : 'CHECK-IN หมายเหตุ' ,data: (e) => {

                let checkin_reason = e.checkin_reason;
                let checkin_over_reason = e.checkin_over_reason;

                if (checkin_over_reason != null) {
                    return checkin_over_reason;
                } else {
                    return checkin_reason;
                }
              }, name: 'checkin_reason' },

              { title : 'CHECK-OUT TIME' ,data: (e) => {
                  return e.checkout_time;
              }, name: 'checkout_time' },

              { title : 'CHECK-OUT หมายเหตุ' ,data: (e) => {
                  return e.checkout_reason;
              }, name: 'checkout_reason' },
            ],
        });

        $('.column_checkin_checkout').on('change', function () {
          reports.filterColumn_checkin_checkout($(this).attr('data-column'))
        });

        // Search
        $('#text_search_checkin_checkout').on( 'keyup', function () {
          reports.table_checkin_checkout.search( this.value ).draw();
        });

        $('#export_but' ).attr( "aria-controls" ,'table_checkin_checkout')
            for (var i = 0; i < $( '.buttons-excel' ).length; i++) {
                if ($( '.buttons-excel' )[i].getAttribute('aria-controls') != 'table_checkin_checkout') {
                    $( '.buttons-excel' )[i].remove();
                }
            }

        $("#daterange_search").on('apply.daterangepicker', function(ev, picker) {
            var daterange = picker.startDate.format('DD/MM/YYYY')+' - '+picker.endDate.format('DD/MM/YYYY')
            $(this).val(daterange);
            reports.table_checkin_checkout.ajax.reload();
        });

        $("#daterange_search").daterangepicker({
            autoApply: true,
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY'
            }

           },
        );

        // select event
        $('#event_search_checkin_checkout').on('change', function () {

            if($('#event_search_checkin_checkout').val() != ''){

                reports.table_checkin_checkout = $('#table_checkin_checkout').DataTable({

                    processing: true,
                    // serverSide: true,
                    dom: 'lBfrtip',
                    buttons: [
                        {
                          extend: 'excel',
                          title: 'รายงานการ CHECK-IN CHECK-OUT',
                          exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                                //columns: [ 0, 1, 2, 5, 6, 7, 8, 9, 10, 11, 12, 13],
                                //Your Colume value those you want
                             }
                        }
                    ],
                    bInfo: true,
                    destroy: true,
                    ordering: false,
                    "searching": true,
                    ajax: {
                        "url": window.location.origin+'/admin/reports/DataCheckinCheckoutReport',
                        "type": "POST",
                        data:{
                            event_id : function() {
                              return $('#event_search_checkin_checkout').val();
                            },
                            daterange_search : function() {
                              return $('#daterange_search').val();
                            },
                          },
                        headers: {
                          'X-CSRF-TOKEN': $('input[name=_token]').val()
                        }
                    },
                    columns:
                    [
                      { title : '#' ,data: (a,b,c,d,e) => {
                        return (reports.table_checkin_checkout.page.info().start+d.row+1);
                      } },

                      { title : 'Date' ,data: (e) => {
                        return e.event_date;
                      }, name: 'event_date' },

                      { title : 'Code' ,data: (e) => {
                          return e.dealer_legacy_code;
                      }, name: 'dealer_legacy_code', visible: false },

                      { title : 'DLR' ,data: (e) => {
                          return e.dealer_dlr;
                      }, name: 'dealer_dlr' },

                      { title : 'Zone' ,data: (e) => {
                        return e.dealer_zone
                      }, name: 'dealer_zone', visible: false},

                      { title : 'Area' ,data: (e) => {
                        return e.dealer_area;
                      }, name: 'dealer_area', visible: false},

                      { title : 'DLR Name' ,data: (e) => {
                          return e.dealer_name;
                      }, name: 'dealer_name' },

                      { title : 'ID Sale' ,data: (e) => {
                          return e.sale_dealer_code;
                      }, name: 'sale_dealer_code' },

                      { title : 'Sale Name' ,data: (e) => {
                          return e.sale_dealer_name;
                      }, name: 'sale_dealer_name' },

                      { title : 'Nickname' ,data: (e) => {
                          return e.sale_dealer_nickname;
                      }, name: 'sale_dealer_nickname' },

                      { title : 'Mobile' ,data: (e) => {
                          return e.sale_dealer_tel;
                      }, name: 'sale_dealer_tel' },

                      { title : 'CHECK-IN TIME' ,data: (e) => {
                          return e.checkin_time;
                      }, name: 'checkin_time' },

                      { title : 'CHECK-IN หมายเหตุ' ,data: (e) => {

                        let checkin_reason = e.checkin_reason;
                        let checkin_over_reason = e.checkin_over_reason;

                        if (checkin_over_reason != null) {
                            return checkin_over_reason;
                        } else {
                            return checkin_reason;
                        }
                      }, name: 'checkin_reason' },

                      { title : 'CHECK-OUT TIME' ,data: (e) => {
                          return e.checkout_time;
                      }, name: 'checkout_time' },

                      { title : 'CHECK-OUT หมายเหตุ' ,data: (e) => {
                          return e.checkout_reason;
                      }, name: 'checkout_reason' },
                    ],
                });

            }
        });

        // filter dlr,zone,area according event
        $('#event_search_checkin_checkout').on('change', function () {
            if($('#event_search_checkin_checkout').val() != ''){
                $.ajax({
                    url:"{{ route('admin.reports.getDlr') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_checkin_checkout').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col2_filter').html(result);
                    },
                });

                $.ajax({
                    url:"{{ route('admin.reports.getZone') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_checkin_checkout').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col3_filter').html(result);
                    },
                });

                $.ajax({
                    url:"{{ route('admin.reports.getArea') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_checkin_checkout').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col4_filter').html(result);
                    },
                });
            } 
        });
    },

    // draw filter in select
    filterColumn_checkin_checkout : function(i){
        console.log($('#col'+i+'_filter').val());
        $('#table_checkin_checkout').DataTable().column(i).search(
            $('#col'+i+'_filter').val()
        ).draw();
    },

    // reload data
    reData_checkin_checkout : function() {
        reports.table_checkin_checkout.ajax.reload();
    },

    ///////////////////////////////////////////// table_checkin_checkout //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////// table_sale_dealer //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    tableListsale_dealer: function () {

        reports.table_sale_dealer = $('#table_sale_dealer').DataTable({

            "drawCallback": function( settings ) {
                document.getElementById('table_sale_dealer').style='';
                for (var i = 0; i < $('#table_sale_dealer .sorting_disabled').length; i++) {
                  $('#table_sale_dealer .sorting_disabled')[i].style='';
                }

            },

            "preDrawCallback": function( settings ) {
                document.getElementById('table_sale_dealer').style='';
                for (var i = 0; i < $('#table_sale_dealer .sorting_disabled').length; i++) {
                  $('#table_sale_dealer .sorting_disabled')[i].style='';
                }
            },

            processing: true,
            bInfo: true,
            destroy: true,
            ordering: false,
            dom: 'lBfrtip',
            buttons: [
                {
                  extend: 'excelHtml5',
                  title: 'รายงานข้อมูลของ Sale Dealer',
                  exportOptions: {
                      columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] //Your Colume value those you want
                  }
                }
            ],
            ajax: {
                "url": window.location.origin+'/admin/reports/DataSaleDealerReport',
                "type": "post",
                data:{
                    event_id : function() {
                        return $('#event_search_sale_dealer').val();
                    },
                },
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            },
            columns:
            [
                { title : '#' ,data: (a,b,c,d,e) => {
                    return (reports.table_sale_dealer.page.info().start+d.row+1);
                } },

                { title : 'ID Sale' ,data: (e) => {
                    return e.sale_dealer_code;
                }, name: 'sale_dealer_code' },

                { title : 'Sale Name' ,data: (e) => {
                    return e.sale_dealer_name;
                }, name: 'sale_dealer_name' },

                { title : 'Nickname' ,data: (e) => {
                    return e.sale_dealer_nickname;
                }, name: 'sale_dealer_nickname' },

                { title : 'Mobile' ,data: (e) => {
                    return e.sale_dealer_tel;
                }, name: 'sale_dealer_tel' },

                { title : 'IDS' ,data: (e) => {
                    return e.dealer_ids_code;
                }, name: 'dealer_ids_code' },

                { title : 'Legacy' ,data: (e) => {
                    return e.dealer_legacy_code;
                }, name: 'dealer_legacy_code' },

                { title : 'DLR Name' ,data: (e) => {
                    return e.dealer_name;
                }, name: 'dealer_name'},

                { title : 'Zone' ,data: (e) => {
                    return e.dealer_zone;
                }, name: 'dealer_zone' },

                { title : 'Area' ,data: (e) => {
                    return e.dealer_area;
                }, name: 'dealer_area' },

                { title : 'DLR' ,data: (e) => {
                    return e.dealer_dlr;
                }, name: 'dealer_dlr' },
            ]
        });

        // filter column with data-column
        $('.column_sale_dealer').on('change', function () {
          reports.filterColumn_sale_dealer($(this).attr('data-column'))
        });

        // search 
        $('#text_search_sale_dealer').on( 'keyup', function () {
          reports.table_sale_dealer.search( this.value ).draw();
        });

        // export excel
        $('#export_but' ).attr( "aria-controls" ,'table_sale_dealer')
        for (var i = 0; i < $( '.buttons-excel' ).length; i++) {
            if ($( '.buttons-excel' )[i].getAttribute('aria-controls') != 'table_sale_dealer') {
                $( '.buttons-excel' )[i].remove();
            }
        }

        // select event
        $('#event_search_sale_dealer').on('change', function () {

            if($('#event_search_sale_dealer').val() != ''){

                reports.table_sale_dealer = $('#table_sale_dealer').DataTable({

                    "drawCallback": function( settings ) {
                        document.getElementById('table_sale_dealer').style='';

                        for (var i = 0; i < $('#table_sale_dealer .sorting_disabled').length; i++) {
                          $('#table_sale_dealer .sorting_disabled')[i].style='';
                        }

                    },

                    "preDrawCallback": function( settings ) {
                        document.getElementById('table_sale_dealer').style='';
                        for (var i = 0; i < $('#table_sale_dealer .sorting_disabled').length; i++) {
                          $('#table_sale_dealer .sorting_disabled')[i].style='';
                        }
                    },

                    processing: true,
                    bInfo: true,
                    destroy: true,
                    ordering: false,
                    "oSearch": {"sSearch": $('select[name="event_search_sale_dealer"]').val() },
                    dom: 'lBfrtip',
                    buttons: [
                        {
                          extend: 'excelHtml5',
                          title: 'รายงานข้อมูลของ Sale Dealer',
                          exportOptions: {
                              columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] //Your Colume value those you want
                          }
                        }
                    ],
                    ajax: {
                      "url": window.location.origin+'/admin/reports/DataSaleDealerReport',
                      "type": "post",
                       data:{
                        event_id : function() {
                          return $('#event_search_sale_dealer').val();
                        },
                      },
                      headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                      }
                    },
                    columns:
                    [
                      { title : '#' ,data: (a,b,c,d,e) => {
                        return (reports.table_sale_dealer.page.info().start+d.row+1);
                      } },

                      { title : 'ID Sale' ,data: (e) => {
                        return e.sale_dealer_code;
                      }, name: 'sale_dealer_code' },

                      { title : 'Sale Name' ,data: (e) => {
                          return e.sale_dealer_name;
                      }, name: 'sale_dealer_name' },

                      { title : 'Nickname' ,data: (e) => {
                          return e.sale_dealer_nickname;
                      }, name: 'sale_dealer_nickname' },

                      { title : 'Mobile' ,data: (e) => {
                          return e.sale_dealer_tel;
                      }, name: 'sale_dealer_tel' },

                      { title : 'IDS' ,data: (e) => {
                          return e.dealer_ids_code;
                      }, name: 'dealer_ids_code' },

                      { title : 'Legacy' ,data: (e) => {
                          return e.dealer_legacy_code;
                      }, name: 'dealer_legacy_code' },

                      { title : 'DLR Name' ,data: (e) => {
                          return e.dealer_name;
                      }, name: 'dealer_name'},

                      { title : 'Zone' ,data: (e) => {
                          return e.dealer_zone;
                      }, name: 'dealer_zone' },

                      { title : 'Area' ,data: (e) => {
                          return e.dealer_area;
                      }, name: 'dealer_area' },

                      { title : 'DLR' ,data: (e) => {
                          return e.dealer_dlr;
                      }, name: 'dealer_dlr' },
                    ]
                });

            }
        });

        // filter dlr,zone,area according event
        $('#event_search_sale_dealer').on('change', function () {
            if($('#event_search_sale_dealer').val() != ''){
                $.ajax({
                    url:"{{ route('admin.reports.getDlr') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_sale_dealer').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col10_filter').html(result);
                    },
                });

                $.ajax({
                    url:"{{ route('admin.reports.getZone') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_sale_dealer').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col8_filter').html(result);
                    },
                });

                $.ajax({
                    url:"{{ route('admin.reports.getArea') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_sale_dealer').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col9_filter').html(result);
                    },
                });
            } 
        });
    },

    // draw filter in select
    filterColumn_sale_dealer : function(i){
        console.log($('#col'+i+'_filter'));
        $('#table_sale_dealer').DataTable().column(i).search(
            $('#col'+i+'_filter').val()
        ).draw();
    },

    // reload data
    reData_sale_dealer : function() {
        reports.table_sale_dealer.ajax.reload();
    },
    ///////////////////////////////////////////// table_sale_dealer //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////// table_preemption //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    tableListpreemption: function () {

        reports.table_preemption = $('#table_preemption').DataTable({

            "drawCallback": function( settings ) {
                document.getElementById('table_preemption').style='';

                for (var i = 0; i < $('#table_preemption .sorting_disabled').length; i++) {
                  $('#table_preemption .sorting_disabled')[i].style='';
                }
            },

            "preDrawCallback": function( settings ) {
                document.getElementById('table_preemption').style='';
                for (var i = 0; i < $('#table_preemption .sorting_disabled').length; i++) {
                  $('#table_preemption .sorting_disabled')[i].style='';
                }
            },           

            processing: true,
            // serverSide: true,
            bInfo: true,
            destroy: true,
            ordering: false,
            "searching": true,
            "oSearch": {"sSearch": $('select[name="event_search_preemption"]').val() },
            dom: 'lBfrtip',
            buttons: [
                {
                  extend: 'excelHtml5',
                  title: 'รายงานใบจอง',
                  exportOptions: {
                      columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14] //Your Colume value those you want
                  }
                }
            ],
            ajax: {
                "url": window.location.origin+'/admin/reports/DataPreemptionReport',
                "type": "post",
                data:{
                    event_id : function() {
                      return $('#event_search_preemption').val();
                    },
                    daterange_search_preemption : function() {
                      return $('#daterange_search_preemption').val();
                      console.log($('#daterange_search_preemption').val());
                    },
                },
                headers: {
                  'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            },
            columns:
            [
                { title : '#' ,data: (a,b,c,d,e) => {
                    return (reports.table_preemption.page.info().start+d.row+1);
                }},

                { title : 'Date' ,data: (e) => {
                  var formatted_date = moment(e.updated_at).format('YYYY-MM-DD');
                  return formatted_date;
                }, name: 'updated_at' },

                { title : 'Event' ,data: (e) => {
                    return e.event_id;
                }, name: 'event_id', visible: false},

                { title : 'เลขที่ใบจอง' ,data: (e) => {
                    return e.preemption_no;
                }, name: 'preemption_no' },

                { title : 'Model Car' ,data: (e) => {
                    return e.model_car_name;
                    
                }, name: 'model_car_name'},

                { title : 'Sub Model Car' ,data: (e) => {
                    return e.sub_model_car_name;
                }, name: 'sub_model_car_name'},

                { title : 'Type' ,data: (e) => {
                    return e.preemption_type;
                }, name: 'preemption_type' },

                { title : 'DLR' ,data: (e) => {
                    return e.dealer_dlr;
                }, name: 'dealer_dlr' },

                { title : 'Zone' ,data: (e) => {
                    return e.dealer_zone;
                }, name: 'dealer_zone' },

                { title : 'Area' ,data: (e) => {
                    return e.dealer_area;
                }, name: 'dealer_area' },

                { title : 'ID Sale' ,data: (e) => {
                    return e.sale_dealer_code;
                }, name: 'sale_dealer_code' },

                { title : 'Sale Name' ,data: (e) => {
                    return e.sale_dealer_name;
                }, name: 'sale_dealer_name' },

                { title : 'เบิกใบจอง' ,data: (e) => {
                    return e.request_at;
                }, name: 'request_at' },

                { title : 'คืนใบจอง' ,data: (e) => {
                    let response = e.response_at;
                        if (e.preemption_status == 2) {
                            return response;
                        } else {
                            return null;
                        }
                }, name: 'response_at' },

                { title : 'ยกเลิกใบจอง' ,data: (e) => {
                    let response = e.response_at;
                        if (e.preemption_status == 3) {
                            return response;
                        } else {
                            return null;
                        }
                }, name: 'response_at' },

                { title : 'สถานะใบจอง' ,data: (e) => {
                    return e.preemption_status;
                }, name: 'preemption_status', visible: false},
            ],
        });

        $('.column_preemption').on('change', function () {
            reports.filterColumn_preemption($(this).attr('data-column'))
        });

        // default data
        $('#table_preemption').DataTable().column(2).search(
            $('select[name="event_search_preemption"]').val()
        ).draw();

        // search
        $('#text_search_preemption').on( 'keyup', function () {
          reports.table_preemption.search( this.value ).draw();
        });

        // export excel
        $( '#export_but' ).attr( "aria-controls" ,'table_preemption')
        for (var i = 0; i < $( '.buttons-excel' ).length; i++) {
            if ($( '.buttons-excel' )[i].getAttribute('aria-controls') != 'table_preemption') {
                $( '.buttons-excel' )[i].remove();
            }
        }

        // date
        $("#daterange_search_preemption").on('apply.daterangepicker', function(ev, picker) {
            var daterange = picker.startDate.format('DD/MM/YYYY')+' - '+picker.endDate.format('DD/MM/YYYY')
            $(this).val(daterange);
            reports.table_preemption.ajax.reload();
        });

        $("#daterange_search_preemption").daterangepicker({
            autoApply: true,
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY'
            }
        });

        // select event
        $('#event_search_preemption').on('change', function () {

            if($('#event_search_preemption').val() != ''){

                reports.table_preemption = $('#table_preemption').DataTable({

                    "drawCallback": function( settings ) {
                        document.getElementById('table_preemption').style='';

                        for (var i = 0; i < $('#table_preemption .sorting_disabled').length; i++) {
                          $('#table_preemption .sorting_disabled')[i].style='';
                        }

                    },

                    "preDrawCallback": function( settings ) {
                        document.getElementById('table_sale_dealer').style='';
                        for (var i = 0; i < $('#table_sale_dealer .sorting_disabled').length; i++) {
                          $('#table_sale_dealer .sorting_disabled')[i].style='';
                        }
                    },            

                    processing: true,
                    // serverSide: true,
                    bInfo: true,
                    destroy: true,
                    ordering: false,
                    "searching": true,
                    "oSearch": {"sSearch": $('select[name="event_search_preemption"]').val() },
                    dom: 'lBfrtip',
                    buttons: [
                        {
                          extend: 'excelHtml5',
                          title: 'รายงานใบจอง',
                          exportOptions: {
                              columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14] //Your Colume value those you want
                          }
                        }
                    ],
                    ajax: {
                        "url": window.location.origin+'/admin/reports/DataPreemptionReport',
                        "type": "post",
                        data:{
                            event_id : function() {
                              return $('#event_search_preemption').val();
                            },
                            daterange_search_preemption : function() {
                              return $('#daterange_search_preemption').val();
                            },
                        },
                        headers: {
                          'X-CSRF-TOKEN': $('input[name=_token]').val()
                        }
                    },
                    columns:
                    [
                        { title : '#' ,data: (a,b,c,d,e) => {
                            return (reports.table_preemption.page.info().start+d.row+1);
                        }},

                        { title : 'Date' ,data: (e) => {
                          let formatted_date = moment(e.updated_at).format('YYYY-MM-DD');
                          return formatted_date;
                        }, name: 'updated_at' },

                        { title : 'Event' ,data: (e) => {
                            return e.event_id;
                        }, name: 'event_id', visible: false},

                        { title : 'เลขที่ใบจอง' ,data: (e) => {
                            return e.preemption_no;
                        }, name: 'preemption_no' },

                        { title : 'Model Car' ,data: (e) => {
                            return e.model_car_name;
                        }, name: 'model_car_name'},

                        { title : 'Sub Model Car' ,data: (e) => {
                            return e.sub_model_car_name;
                        }, name: 'sub_model_car_name'},

                        { title : 'Type' ,data: (e) => {
                            return e.preemption_type;
                        }, name: 'preemption_type' },

                        { title : 'DLR' ,data: (e) => {
                            return e.dealer_dlr;
                        }, name: 'dealer_dlr' },

                        { title : 'Zone' ,data: (e) => {
                            return e.dealer_zone;
                        }, name: 'dealer_zone' },

                        { title : 'Area' ,data: (e) => {
                            return e.dealer_area;
                        }, name: 'dealer_area' },

                        { title : 'ID Sale' ,data: (e) => {
                            return e.sale_dealer_code;
                        }, name: 'sale_dealer_code' },

                        { title : 'Sale Name' ,data: (e) => {
                            return e.sale_dealer_name;
                        }, name: 'sale_dealer_name' },

                        { title : 'เบิกใบจอง' ,data: (e) => {
                            return e.request_at;
                        }, name: 'request_at' },

                        { title : 'คืนใบจอง' ,data: (e) => {
                            let response = e.response_at;
                                if (e.preemption_status == 2) {
                                    return response;
                                } else {
                                    return null;
                                }
                        }, name: 'response_at' },

                        { title : 'ยกเลิกใบจอง' ,data: (e) => {
                            let response = e.response_at;
                                if (e.preemption_status == 3) {
                                    return response;
                                } else {
                                    return null;
                                }
                        }, name: 'response_at' },

                        { title : 'สถานะใบจอง' ,data: (e) => {
                            return e.preemption_status;
                        }, name: 'preemption_status', visible: false},

                    ],
                });

            }
        });

        // filter dlr,zone,area according event
        $('#event_search_preemption').on('change', function () {
            if ($('#event_search_preemption').val() != ''){
                $.ajax({
                    url:"{{ route('admin.reports.getDlr') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_preemption').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col7_filterPreemption').html(result);
                    },
                });

                $.ajax({
                    url:"{{ route('admin.reports.getZone') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_preemption').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col8_filterPreemption').html(result);
                    },
                });

                $.ajax({
                    url:"{{ route('admin.reports.getArea') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_preemption').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col9_filterPreemption').html(result);
                    },
                });
            } 
        });

        // select model car
        $('#col4_filterPreemption').on('change', function () {

            if ($('#col4_filterPreemption').val() != ''){

                $.ajax({

                    url:"{{ route('admin.reports.getSubModelCar') }}",
                    method:"POST",
                    data:{
                      model_car_name: $('#col4_filterPreemption').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col5_filterPreemption').html(result)  
                    },

                });
            } 
        });

    },

    // draw filter in select
    filterColumn_preemption : function(i){
        console.log($('#col'+i+'_filterPreemption').val());
        $('#table_preemption').DataTable().column(i).search(
            $('#col'+i+'_filterPreemption').val()
        ).draw();
    },

    // reload data
    reData_preemption : function() {
        reports.table_preemption.ajax.reload();
    },
    ///////////////////////////////////////////// table_preemption //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ///////////////////////////////////////////// table_dealer_checkin //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    tableListdealer_checkin: function () {

        reports.table_dealer_checkin = $('#table_dealer_checkin').DataTable({

            "drawCallback": function( settings ) {
                document.getElementById('table_dealer_checkin').style='';

                for (var i = 0; i < $('#table_dealer_checkin .sorting_disabled').length; i++) {
                  $('#table_dealer_checkin .sorting_disabled')[i].style='';
                }
            },

            "preDrawCallback": function( settings ) {
                document.getElementById('table_checkin_checkout').style='';
                for (var i = 0; i < $('#table_checkin_checkout .sorting_disabled').length; i++) {
                  $('#table_checkin_checkout .sorting_disabled')[i].style='';
                }
            },

            processing: true,
            // serverSide: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 10,
            dom: 'lBfrtip',
            buttons: [
                {
                  extend: 'excel',
                  title: 'รายงานข้อมูลของ Dealer Check-in',
                  exportOptions: {
                        columns: [0, 2, 3, 4, 5, 6, 7],//Your Colume value those you want
                        modifier: {
                            page: 'all'
                        },
                     }
                }
            ],
            bInfo: true,
            destroy: true,
            ordering: false,
            "searching": true,
            "oSearch": {"sSearch": $('select[name="event_search_dealer_checkin"]').val() },
            ajax: {
                "url": window.location.origin+'/admin/reports/DataDealerCheckinReport',
                "type": "POST",
                data:{
                    // event_id : function() {
                    //   return $('#event_id').val();
                    // },
                    daterange_search_dealer_checkin : function() {
                      return $('#daterange_search_dealer_checkin').val();
                    },
                    event_id : function() {
                      return $('#event_search_dealer_checkin').val();
                    },
                  },
                headers: {
                  'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            },
            columns:
            [
                { title : '#' ,data: (a,b,c,d,e) => {
                    var total_row = (reports.table_dealer_checkin.page.info().start+d.row+1);
                    return total_row;
                }},

                { title : 'Event' ,data: (e) => {
                    return e.event_id;
                }, name: 'event_id', visible:false},

                { title : 'DLR' ,data: (e) => {
                    return e.dealer_dlr;
                }, name: 'dealer_dlr'},

                { title : 'DLR Name' ,data: (e) => {
                    return e.dealer_name;
                }, name: 'dealer_name'},

                { title : 'จำนวนโควต้า Checkin ทั้งหมด' ,data: (e) => {
                    return e.checkin_quota;
                }, name: 'checkin_quota'},

                { title : 'จำนวน Checkin ทั้งหมด' ,data: (e) => {
                    //var checkin_quota = e.checkin_quota;
                    if(e.checkin_time == 0){
                        e.checkin_time = '<p style="color:red">'+e.checkin_time+'</p>'
                    }
                    return e.checkin_time;
                }, name: 'checkin_time'},

                { title : 'จำนวน Checkin ที่มาสายทั้งหมด' ,data: (e) => {
                    return e.checkin_late;
                }, name: 'checkin_late'},

                { title : 'จำนวน Checkin เกินโควต้าทั้งหมด' ,data: (e) => {
                    return e.checkin_over;
                }, name: 'checkin_over'},

            ],
            columnDefs: [
                { "width": "30%", "targets": 3 }
            ],
        });

        $('.column_dealer_checkin').on('change', function () {
            reports.filterColumn_dealer_checkin($(this).attr('data-column'))
        });

        $('#table_dealer_checkin').DataTable().column(1).search(
            $('select[name="event_search_dealer_checkin"]').val()
        ).draw();

        $('#text_search_dealer_checkin').on( 'keyup', function () {
          reports.table_dealer_checkin.search( this.value ).draw();
        });

        $( '#export_but' ).attr( "aria-controls" ,'table_dealer_checkin')
        for (var i = 0; i < $( '.buttons-excel' ).length; i++) {
            if ($( '.buttons-excel' )[i].getAttribute('aria-controls') != 'table_dealer_checkin') {
                $( '.buttons-excel' )[i].remove();
            }
        }

        // date
        $("#daterange_search_dealer_checkin").on('apply.daterangepicker', function(ev, picker) {
            var daterange = picker.startDate.format('DD/MM/YYYY')+' - '+picker.endDate.format('DD/MM/YYYY')
            $(this).val(daterange);
            reports.table_dealer_checkin.ajax.reload();
        });

        $("#daterange_search_dealer_checkin").daterangepicker({
            autoApply: true,
            autoUpdateInput: false,
            locale: {
                format: 'DD/MM/YYYY'
            }

           },
        );

        // select event
        $('#event_search_dealer_checkin').on('change', function () {

            if($('#event_search_dealer_checkin').val() != ''){

                reports.table_dealer_checkin = $('#table_dealer_checkin').DataTable({

                    processing: true,
                    // serverSide: true,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    pageLength: 10,
                    dom: 'lBfrtip',
                    buttons: [
                        {
                          extend: 'excel',
                          title: 'รายงานข้อมูลของ Dealer Check-in',
                          exportOptions: {
                                columns: [0, 2, 3, 4, 5, 6, 7],//Your Colume value those you want
                                modifier: {
                                    page: 'all'
                                },
                             }
                        }
                    ],
                    bInfo: true,
                    destroy: true,
                    ordering: false,
                    "searching": true,
                    "oSearch": {"sSearch": $('select[name="event_search_dealer_checkin"]').val() },
                    ajax: {
                        "url": window.location.origin+'/admin/reports/DataDealerCheckinReport',
                        "type": "POST",
                        data:{
                            daterange_search_dealer_checkin : function() {
                              return $('#daterange_search_dealer_checkin').val();
                            },
                            event_id : function() {
                              return $('#event_search_dealer_checkin').val();
                            },
                          },
                        headers: {
                          'X-CSRF-TOKEN': $('input[name=_token]').val()
                        }
                    },
                    columns:
                    [
                        { title : '#' ,data: (a,b,c,d,e) => {
                            var total_row = (reports.table_dealer_checkin.page.info().start+d.row+1);
                            return total_row;
                        }},

                        { title : 'Event' ,data: (e) => {
                            return e.event_id;
                        }, name: 'event_id', visible:false},

                        { title : 'DLR' ,data: (e) => {
                            return e.dealer_dlr;
                        }, name: 'dealer_dlr'},

                        { title : 'DLR Name' ,data: (e) => {
                            return e.dealer_name;
                        }, name: 'dealer_name'},

                        { title : 'จำนวนโควต้า Checkin ทั้งหมด' ,data: (e) => {
                            return e.checkin_quota;
                        }, name: 'checkin_quota'},

                        { title : 'จำนวน Checkin ทั้งหมด' ,data: (e) => {
                            //var checkin_quota = e.checkin_quota;
                            if(e.checkin_time == 0){
                                e.checkin_time = '<p style="color:red">'+e.checkin_time+'</p>'
                            }
                            return e.checkin_time;
                        }, name: 'checkin_time'},

                        { title : 'จำนวน Checkin ที่มาสายทั้งหมด' ,data: (e) => {
                            return e.checkin_late;
                        }, name: 'checkin_late'},

                        { title : 'จำนวน Checkin เกินโควต้าทั้งหมด' ,data: (e) => {
                            return e.checkin_over;
                        }, name: 'checkin_over'},

                    ],
                    columnDefs: [
                        { "width": "30%", "targets": 3 }
                    ],
                });

            }
        });

        // 
        $('#event_search_dealer_checkin').on('change', function () {
            if($('#event_search_dealer_checkin').val() != ''){
                $.ajax({
                    url:"{{ route('admin.reports.getDlr') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_search_dealer_checkin').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                        $('#col2_filterDealercheckin').html(result);
                    },
                });
            } 
        });
    },

    // draw filter in select
    filterColumn_dealer_checkin : function(i){
    console.log($('#col'+i+'_filterDealercheckin').val());
    $('#table_dealer_checkin').DataTable().column(i).search(
        $('#col'+i+'_filterDealercheckin').val()
    ).draw();
    },

    // reload data
    reData_dealer_checkin : function() {
        reports.table_dealer_checkin.ajax.reload();
    },
    ///////////////////////////////////////////// table_dealer_checkin //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

} // var

$(function () {

    reports.tableListcheckin_checkout();

    $('#table_checkin_checkout_filter').hide();

    $('#list_report').on('change', function () {
      if ($(this).val() === 'checkin_checkouts') {
        reports.tableListcheckin_checkout();
            $('#checkin_checkouts').show();
            $('#sale_dealers').hide();
            $('#preemptions').hide();
            $('#dealer_checkins').hide();
            $('#table_checkin_checkout_filter').hide();
      }else if ($(this).val() === 'sale_dealers') {
        reports.tableListsale_dealer();
          $('#sale_dealers').show();
          $('#checkin_checkouts').hide();
          $('#preemptions').hide();
          $('#dealer_checkins').hide();
          $('#table_sale_dealer_filter').hide();
      }else if ($(this).val() === 'preemptions') {
         reports.tableListpreemption();
         $('#preemptions').show();
         $('#checkin_checkouts').hide();
         $('#sale_dealers').hide();
         $('#dealer_checkins').hide();
         $('#table_preemption_filter').hide();
      } else {
         reports.tableListdealer_checkin();
         $('#dealer_checkins').show();
         $('#preemptions').hide();
         $('#checkin_checkouts').hide();
         $('#sale_dealers').hide();
         $('#table_dealer_checkin_filter').hide();
      }
    });

})

</script>

@stop
