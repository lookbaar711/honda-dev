@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Dashboard
@parent
@stop

{{-- page level styles --}}
@section('header_styles')


<link rel="stylesheet" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/only_dashboard.css') }}" />
<meta name="_token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/morrisjs/morris.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard2.css') }}" />

<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<link href="{{ asset('assets/vendors/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />

@stop

{{-- Page content --}}
@section('content')
<!--</section>-->
<section class="content">
    <div class="card-body" style="padding-top: 20px; margin-left: 20px; margin-right: 20px;">
        <!-- <div class="card-body"> -->

        <p class="fs-26"><b>Dashboard</b></p>
        <div class="bg-gray" style="padding-bottom: 5px; padding-top: 10px;">
            <div class="form-group">
                <div class="col-12 row">
                    <div class="col-sm-2" id="set_dot">
                        <label for="input" style="font-size: 16px !important;">Event : </label>
                        <select class="form-control required" name="event_search" id="event_search">
                            <option value="">- ทั้งหมด -</option>
                            @foreach($event as $e)
                            <option value="{{ $e->id }}">{{ $e->event_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="input" style="font-size: 16px !important;">วันที่เริ่มต้น-วันที่สิ้นสุด : </label>
                        <input type="text" class="form-control daterange-input" name="daterange_search"
                            id="daterange_search" placeholder="วว/ดด/ปป - วว/ดด/ปป" autocomplete="off" />
                    </div>

                    <div class="col-sm-2">
                        <label for="input" style="font-size: 16px !important;">DLR : </label>
                        <select class="form-control required" name="dlr_search" id="dlr_search">
                            <option value="">- ทั้งหมด -</option>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <label for="input" style="font-size: 16px !important;">Zone : </label>
                        <select class="form-control required" name="zone_search" id="zone_search">
                            <option value="">- ทั้งหมด -</option>
                        </select>
                    </div>

                    <div class="col-sm-2">
                        <label for="input" style="font-size: 16px !important;">Area : </label>
                        <select class="form-control required" name="area_search" id="area_search">
                            <option value="">- ทั้งหมด -</option>
                        </select>
                    </div>
                    <button class="btn btn-outline-dark" id="reset_button"
                        style="margin-left: 5px; height: 37px; margin-top: 21px;">
                        <span class="fa fa-refresh"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- <div class="bg-gray" style="padding-bottom: 20px; padding-top: 20px;">
            <div class="form-group">
                <div class="col-12 row">
                    <div class="col-sm-6">
                        <label for="input" style="font-size: 16px !important;">Event : </label>
                        <select class="form-control required" name="event_search" id="event_search">
                            <option value="">ทั้งหมด</option>
                            @foreach($event as $e)
                            <option value="{{ $e->id }}">{{ $e->event_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="input" style="font-size: 16px !important;">วันที่เริ่มต้น-วันที่สิ้นสุด : </label>
                        <input type="text" class="form-control daterange-input" name="daterange_search"
                            id="daterange_search" placeholder="วว/ดด/ปป - วว/ดด/ปป" autocomplete="off" />
                    </div>
                </div>
                <div class="col-12 row">
                    <div class="col-sm-3">
                        <label for="input" style="font-size: 16px !important;">DLR : </label>
                        <select class="form-control required" name="dlr_search" id="dlr_search">
                            <option value="">ทั้งหมด</option>
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <label for="input" style="font-size: 16px !important;">Zone : </label>
                        <select class="form-control required" name="zone_search" id="zone_search">
                            <option value="">ทั้งหมด</option>
                        </select>
                    </div>

                    <div class="col-sm-3">
                        <label for="input" style="font-size: 16px !important;">Area : </label>
                        <select class="form-control required" name="area_search" id="area_search">
                            <option value="">ทั้งหมด</option>
                        </select>
                    </div>
                    <button class="btn btn-outline-dark" id="reset_button"
                        style="margin-left: 5px; height: 37px; margin-top: 21px;">
                        <span class="fa fa-refresh"></span>
                    </button>
                </div>
            </div>
        </div> --}}
        <!-- </div> -->

        <!-- </div> -->
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="widget-blue" >
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-5" style="padding-top: 15px;">
                            <img src="{{ asset ('assets/img/ic_dashboard.png') }}" style="width: 50px; height: 50px;">
                        </div>
                        <div class="col-6 text-right" style="padding-top: 7px; padding-right: 50px; padding-bottom: 20px;">
                            <b class="font-white fs-26 count_dealer">{{ $count_dealer }}</b>
                            <p class="font-white fs-15">จำนวน Dealer</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="widget-red">
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-5" style="padding-top: 15px;">
                            <img src="{{ asset ('assets/img/ic_dashboard.png') }}" style="width: 50px; height: 50px;">
                        </div>
                        <div class="col-6 text-right" style="padding-top: 7px; padding-right: 50px; padding-bottom: 20px;">
                            <b class="font-white fs-26 count_sale_dealer">{{ $count_sale_dealer }}</b>
                            <p class="font-white fs-15">จำนวน Sale Dealer</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="widget-bluesky" style="padding-top: 10px;">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="font-white fs-15">จำนวน CHECK-IN</p>
                        </div>
                    </div>
                </div>
                <div class="widget-white card" style="padding-top: 10px;">
                    <div class="row fs-26">
                        <div class="col text-center" style="width:  285px; border-right: 1px solid #dedede; height: 60px; padding-left: 30px;">
                            <b class="count_checkin">{{ $count_checkin }}</b>
                            <p class="fs-15 font-grey">CHECK-IN</p>
                        </div>
                        <div class="col text-center" style="width: 285px; border-right: 1px solid #dedede; height: 60px;">
                            <b class="count_checkin_late">{{ $count_checkin_late }}</b>
                            <p class="fs-15 font-grey">มาสาย</p>
                        </div>
                        <div class="col text-center" style="padding-right: 30px;">
                            <b class="count_checkin_over">{{ $count_checkin_over }}</b>
                            <p class="fs-15 font-grey">เกิน QUOTA</p>
                        </div>
                    </div>
                </div>


                {{-- <div class="widget-bluesky" style="padding-top: 32px;">
                    <div class="row" style="padding-top: 9px;">
                        <div class="col-1"></div>
                        <div class="col-8">
                            <img src="{{ asset ('assets/img/ic_dashboard.png') }}" style="width: 50px; height: 50px;">
                            <b class="font-white fs-26">จำนวน CHECK-IN</b>
                        </div>
                        <div class="col-3 text-center">
                            <b class="font-white fs-26 count_checkin">{{ $count_checkin }}</b>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="col-6">
                <div class="widget-black" style="padding-top: 10px;">
                    <div class="row">
                        <div class="col-12 text-center">
                            <p class="font-white fs-15">จำนวน CHECK-OUT</p>
                        </div>
                    </div>
                </div>
                <div class="widget-white card" style="padding-top: 10px;">
                    <div class="row fs-26">
                        <div class="col text-center" style="width:  285px; border-right: 1px solid #dedede; height: 60px; padding-left: 30px;">
                            <b class="count_checkout">{{ $count_checkout }}</b>
                            <p class="fs-15 font-grey">CHECK-OUT</p>
                        </div>
                        <div class="col text-center" style="width: 285px; border-right: 1px solid #dedede; height: 60px;">
                            <b class="count_checkout_early">{{ $count_checkout_early }}</b>
                            <p class="fs-15 font-grey">กลับก่อนเวลา</p>
                        </div>
                        <div class="col text-center" style="padding-right: 30px;">
                            <b class="count_not_checkout">{{ $count_not_checkout }}</b>
                            <p class="fs-15 font-grey">ไม่ CHECK-OUT</p>
                        </div>



                        {{-- <div class="col text-center" style="width:  285px; border-right: 1px solid #dedede; height: 60px; padding-left: 30px;">
                            <b class="count_checkout">{{ $count_checkout }}</b>
                            <p class="fs-15 font-grey">CHECK-OUT</p>
                        </div>
                        <div class="col text-center" style="padding-right: 30px;">
                            <b class="count_checkout_early">{{ $count_checkout_early }}</b>
                            <p class="fs-15 font-grey">กลับก่อนเวลา</p>
                        </div> --}}
                    </div>
                </div>

                {{-- <div class="widget-black" style="padding-top: 32px">
                    <div class="row" style="padding-top: 9px;">
                        <div class="col-1"></div>
                        <div class="col-8">
                            <img src="{{ asset ('assets/img/ic_dashboard.png') }}" style="width: 50px; height: 50px;">
                            <b class="font-white fs-26">จำนวน CHECK-OUT</b>
                        </div>
                        <div class="col-3 text-center">
                            <b class="font-white fs-26 count_checkout">{{ $count_checkout }}</b>
                        </div>
                    </div>
                </div> --}}
            </div>
            
        </div>

        <div class="row text-center">
            <div class="col-12 my-3">
                <div class="card panel-primary" style="padding-top: 10px;">
                    {{-- <div class="card-body"> --}}
                        <div class="row fs-26">
                            <div class="col m-auto" style="padding-right: 30px; padding-bottom: 10px;">
                                <img src="{{ asset ('assets/img/ic_dashboard02.png') }}"
                                    style="width: 50px; height: 50px;"> <b class="fs-20">รายการใบจอง</b>
                            </div>
                            <div class="col" style="width:  285px; border-right: 1px solid #dedede; height: 60px;">
                                <b class="count_request">{{ $count_request }}</b>
                                <p class="fs-15 font-green">เบิกใบจอง</p>
                            </div>
                            <div class="col" style="width: 285px; border-right: 1px solid #dedede; height: 60px;">
                                <b class="count_response">{{ $count_response }}</b>
                                <p class="fs-15 font-blue">คืนใบจอง</p>
                            </div>
                            <div class="col">
                                <b class="count_cancel">{{ $count_cancel }}</b>
                                <p class="fs-15 font-red">ยกเลิกใบจอง</p>
                            </div>
                        </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>

    </div>
</section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
<!--for calendar-->

<!-- Back to Top-->
{{-- <script type="text/javascript" src="{{ asset('assets/vendors/countUp.js/js/countUp.js') }}"></script>
<script src="http://maps.google.com/maps/api/js?key=AIzaSyADWjiTRjsycXf3Lo0ahdc7dDxcQb475qw&libraries=places"></script>
<script src="{{ asset('assets/vendors/morrisjs/morris.min.js') }}"></script> --}}

<script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript">
</script>
<script src="{{ asset('assets/vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/datepicker.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $("#event_search").change(function(){
        if($('#event_search').val() != ''){
            $.ajax({
                url:"{{ route('admin.dashboard.getdlr') }}",
                method:"POST",
                data:{
                  event_id: $('#event_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(result){
                  $('#dlr_search').html(result);
                }
            });

            $.ajax({
                url:"{{ route('admin.dashboard.getzone') }}",
                method:"POST",
                data:{
                  event_id: $('#event_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(result){
                  $('#zone_search').html(result);
                }
            });

            $.ajax({
                url:"{{ route('admin.dashboard.getarea') }}",
                method:"POST",
                data:{
                  event_id: $('#event_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(result){
                  $('#area_search').html(result);
                }
            });


            $.ajax({
                url:"{{ route('admin.search_dashboard') }}",
                method:"POST",
                data:{
                  event_search: $('#event_search').val(),
                  daterange_search: $('#daterange_search').val(),
                  dlr_search: $('#dlr_search').val(),
                  zone_search: $('#zone_search').val(),
                  area_search: $('#area_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(response){
                  var json_data = JSON.parse(response);

                  $("b.count_dealer").text(json_data.data.count_dealer);
                  $("b.count_sale_dealer").text(json_data.data.count_sale_dealer);
                  $("b.count_request").text(json_data.data.count_request);
                  $("b.count_response").text(json_data.data.count_response);
                  $("b.count_cancel").text(json_data.data.count_cancel);
                  $("b.count_checkin").text(json_data.data.count_checkin);
                  $("b.count_checkin_late").text(json_data.data.count_checkin_late);
                  $("b.count_checkin_over").text(json_data.data.count_checkin_over);
                  $("b.count_checkout").text(json_data.data.count_checkout);
                  $("b.count_checkout_early").text(json_data.data.count_checkout_early);
                  $("b.count_not_checkout").text(json_data.data.count_not_checkout);
                }
            });

            $('#set_dot').addClass('set-dot');
        }
        else{
          //clear search form
          $('#event_search').val('');
          $('#daterange_search').val('');
          $('#dlr_search').val('');
          $('#zone_search').val('');
          $('#area_search').val('');

          $('#dlr_search').html('<option value="">- ทั้งหมด -</option>');
          $('#zone_search').html('<option value="">- ทั้งหมด -</option>');
          $('#area_search').html('<option value="">- ทั้งหมด -</option>');

          $("b.count_dealer").text("0");
          $("b.count_sale_dealer").text("0");
          $("b.count_request").text("0");
          $("b.count_response").text("0");
          $("b.count_cancel").text("0");
          $("b.count_checkin").text("0");
          $("b.count_checkin_late").text("0");
          $("b.count_checkin_over").text("0");
          $("b.count_checkout").text("0");
          $("b.count_checkout_early").text("0");
          $("b.count_not_checkout").text("0");

          $('#set_dot').removeClass('set-dot');
        }
    });

    $("#daterange_search").on('apply.daterangepicker', function(ev, picker) {
        var daterange = picker.startDate.format('DD/MM/YYYY')+' - '+picker.endDate.format('DD/MM/YYYY')

        $(this).val(daterange);

        $.ajax({
            url:"{{ route('admin.search_dashboard') }}",
            method:"POST",
            data:{
              event_search: $('#event_search').val(),
              daterange_search: $('#daterange_search').val(),
              dlr_search: $('#dlr_search').val(),
              zone_search: $('#zone_search').val(),
              area_search: $('#area_search').val(),
              _token: $('input[name="_token"]').val()
            },
            success:function(response){
              var json_data = JSON.parse(response);

              $("b.count_dealer").text(json_data.data.count_dealer);
              $("b.count_sale_dealer").text(json_data.data.count_sale_dealer);
              $("b.count_request").text(json_data.data.count_request);
              $("b.count_response").text(json_data.data.count_response);
              $("b.count_cancel").text(json_data.data.count_cancel);
              $("b.count_checkin").text(json_data.data.count_checkin);
              $("b.count_checkin_late").text(json_data.data.count_checkin_late);
              $("b.count_checkin_over").text(json_data.data.count_checkin_over);
              $("b.count_checkout").text(json_data.data.count_checkout);
              $("b.count_checkout_early").text(json_data.data.count_checkout_early);
              $("b.count_not_checkout").text(json_data.data.count_not_checkout);
            }
        });   
    });

    
    $("#dlr_search").change(function(){
        //if($('#dlr_search').val() != ''){
            // $.ajax({
            //     url:"{{ route('admin.dashboard.getzone') }}",
            //     method:"POST",
            //     data:{
            //       event_id: $('#event_search').val(),
            //       dlr: $('#dlr_search').val(),
            //       _token: $('input[name="_token"]').val()
            //     },
            //     success:function(result){
            //       $('#daterange_search').val('');
            //       $('#zone_search').html(result);
            //       $('#area_search').html('<option value="">- ทั้งหมด -</option>');
            //     }
            // });

            $.ajax({
                url:"{{ route('admin.search_dashboard') }}",
                method:"POST",
                data:{
                  event_search: $('#event_search').val(),
                  daterange_search: $('#daterange_search').val(),
                  dlr_search: $('#dlr_search').val(),
                  zone_search: $('#zone_search').val(),
                  area_search: $('#area_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(response){
                  var json_data = JSON.parse(response);

                  $("b.count_dealer").text(json_data.data.count_dealer);
                  $("b.count_sale_dealer").text(json_data.data.count_sale_dealer);
                  $("b.count_request").text(json_data.data.count_request);
                  $("b.count_response").text(json_data.data.count_response);
                  $("b.count_cancel").text(json_data.data.count_cancel);
                  $("b.count_checkin").text(json_data.data.count_checkin);
                  $("b.count_checkin_late").text(json_data.data.count_checkin_late);
                  $("b.count_checkin_over").text(json_data.data.count_checkin_over);
                  $("b.count_checkout").text(json_data.data.count_checkout);
                  $("b.count_checkout_early").text(json_data.data.count_checkout_early);
                  $("b.count_not_checkout").text(json_data.data.count_not_checkout);
                }
            });
        //}
    });
    
    $("#zone_search").change(function(){
        //if($('#zone_search').val() != ''){
            // $.ajax({
            //     url:"{{ route('admin.dashboard.getarea') }}",
            //     method:"POST",
            //     data:{
            //       event_id: $('#event_search').val(),
            //       dlr: $('#dlr_search').val(),
            //       zone: $('#zone_search').val(),
            //       _token: $('input[name="_token"]').val()
            //     },
            //     success:function(result){
            //       $('#daterange_search').val('');
            //       $('#area_search').html(result);
            //     }
            // });

            $.ajax({
                url:"{{ route('admin.search_dashboard') }}",
                method:"POST",
                data:{
                  event_search: $('#event_search').val(),
                  daterange_search: $('#daterange_search').val(),
                  dlr_search: $('#dlr_search').val(),
                  zone_search: $('#zone_search').val(),
                  area_search: $('#area_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(response){
                  var json_data = JSON.parse(response);

                  $("b.count_dealer").text(json_data.data.count_dealer);
                  $("b.count_sale_dealer").text(json_data.data.count_sale_dealer);
                  $("b.count_request").text(json_data.data.count_request);
                  $("b.count_response").text(json_data.data.count_response);
                  $("b.count_cancel").text(json_data.data.count_cancel);
                  $("b.count_checkin").text(json_data.data.count_checkin);
                  $("b.count_checkin_late").text(json_data.data.count_checkin_late);
                  $("b.count_checkin_over").text(json_data.data.count_checkin_over);
                  $("b.count_checkout").text(json_data.data.count_checkout);
                  $("b.count_checkout_early").text(json_data.data.count_checkout_early);
                  $("b.count_not_checkout").text(json_data.data.count_not_checkout);
                }
            });
        //}
    });

    $("#area_search").change(function(){
        //if($('#area_search').val() != ''){
            $.ajax({
                url:"{{ route('admin.search_dashboard') }}",
                method:"POST",
                data:{
                  event_search: $('#event_search').val(),
                  daterange_search: $('#daterange_search').val(),
                  dlr_search: $('#dlr_search').val(),
                  zone_search: $('#zone_search').val(),
                  area_search: $('#area_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(response){
                  var json_data = JSON.parse(response);

                  $("b.count_dealer").text(json_data.data.count_dealer);
                  $("b.count_sale_dealer").text(json_data.data.count_sale_dealer);
                  $("b.count_request").text(json_data.data.count_request);
                  $("b.count_response").text(json_data.data.count_response);
                  $("b.count_cancel").text(json_data.data.count_cancel);
                  $("b.count_checkin").text(json_data.data.count_checkin);
                  $("b.count_checkin_late").text(json_data.data.count_checkin_late);
                  $("b.count_checkin_over").text(json_data.data.count_checkin_over);
                  $("b.count_checkout").text(json_data.data.count_checkout);
                  $("b.count_checkout_early").text(json_data.data.count_checkout_early);
                  $("b.count_not_checkout").text(json_data.data.count_not_checkout);
                }
            });
        //}
    });

    $('#reset_button').click(function() {

        //clear search form
        $('#event_search').val('');
        $('#daterange_search').val('');
        $('#dlr_search').val('');
        $('#zone_search').val('');
        $('#area_search').val('');

        $('#dlr_search').html('<option value="">- ทั้งหมด -</option>');
        $('#zone_search').html('<option value="">- ทั้งหมด -</option>');
        $('#area_search').html('<option value="">- ทั้งหมด -</option>');

        $("b.count_dealer").text("0");
        $("b.count_sale_dealer").text("0");
        $("b.count_request").text("0");
        $("b.count_response").text("0");
        $("b.count_cancel").text("0");
        $("b.count_checkin").text("0");
        $("b.count_checkin_late").text("0");
        $("b.count_checkin_over").text("0");
        $("b.count_checkout").text("0");
        $("b.count_checkout_early").text("0");
        $("b.count_not_checkout").text("0");

        $('#set_dot').removeClass('set-dot');
    });

    $("#daterange_search").daterangepicker({
        autoApply: true,
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY'
        }
    });


</script>

@stop