@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('dealers/title.dealers_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap4.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">
  a.dropdown-item {
    font-size: smaller;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20" align="left">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}" style="color: #369DE2;">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="active fs-20" style="color: #7b7e82;">@lang('dealers/title.dealers_list')</a>
                </td>
            </tr>
        </table>

        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary ">

            <div class="card-body">

                <div class="card-body" style="padding-bottom: 1px;">
                    <table border="0">
                        <tr>
                            <td align="right">
                              @if (Sentinel::getUser()->status_process == 0)
                                <a href="/admin/dealers/downloadfile/dealer" class="btn btn-lg button-black" onclick="Dealer.downloadData()"><span class="fa fa-download"></span> @lang('dealers/form.download_button')</a>
                                <a href="{{ route('admin.dealers.import') }}" class="btn btn-lg button-red" data-toggle="modal" data-target="#import_dealer"><span class="fa fa-plus"></span> @lang('dealers/form.import_button')</a>
                              @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td align="left" class="fs-26">
                               <b>@lang('dealers/title.dealers_list') : </b><b style="color: #369DE2;">{{ $event->event_name }}</b>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="ml-3 mr-3">


                <div class="form-group">
                    <div class="col-12 row">

                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('dealers/form.text_search') : </label>
                            <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('dealers/form.zone_search') : </label>
                            <select class="form-control required" name="zone_search" id="zone_search">
                                <option value="">@lang('dealers/form.select_zone')</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->zone_name }}">{{ $zone->zone_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('dealers/form.area_search') : </label>
                            <select class="form-control required" name="area_search" id="area_search">
                                <option value="">@lang('dealers/form.select_area')</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->area_name }}">{{ $area->area_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3  col-lg-3" style="bottom: -20px; padding-left: 0px;">

                            <button class="btn btn-outline-dark" id="reset_button" style="margin-left: 5px;">
                                <span class="fa fa-refresh"></span>
                            </button>
                        </div>

                        <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">
                    </div>
                </div>

                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table">
                    <thead>
                        <tr class="filters">
                            <th>#</th>
                            <th>@lang('dealers/table.code')</th>
                            <th>@lang('dealers/table.ids')</th>
                            <th>@lang('dealers/table.zone')</th>
                            <th>@lang('dealers/table.area')</th>
                            <th>@lang('dealers/table.dlr')</th>
                            <th>@lang('dealers/table.name')</th>
                            <th>@lang('dealers/table.vip')</th>
                            <th>@lang('dealers/table.press')</th>
                            <th>@lang('dealers/table.week_day')</th>
                            <th>@lang('dealers/table.week_end')</th>
                            <th>@lang('dealers/table.actions')</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    </div><!-- row-->

    <div class="row">
      <div class="modal fade" id="import_dealer" role="dialog">
          <div class="modal-dialog modal-xxl">

              <div class="modal-content">
                  <div class="modal-header">
                      <h3 class="modal-title"><b>Import Dealer</b></h3>
                  </div>
                  <hr class="ml-3 mr-3">
                  <div class="modal-body">
                       <form id="my_form">
                            <table>
                                <tr>
                                    <td>
                                         เลือกไฟล์ : <input type='file' name='file' id='file' class='form-control col-sm-12' style="color: #369DE2;" ><br>
                                    </td>
                                    <td>
                                        {{-- <input type='button' class='btn button-blue' value='' id='upload'> --}}
                                        <button type="submit" name="button" type="button" class="btn button-test"><i class="fas fa-file-import"></i> นำเข้าข้อมูล</button>
                                    </td>
                                </tr>
                                <input type="hidden" id="event_id" name="event_id" value="{{$event->id}}">
                            </table>


                      </form>

                      <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                          <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table_dealer"></table>
                      </div>

                  </div>
                  <div class="modal-footer">
                    <button onclick="Dealer.sent()" name="button" type="button" class="btn btn-lg button-blue" data-dismiss="modal">ยืนยันนำเข้าข้อมูล</button>
                    <button type="button" class="btn btn-lg button-red" data-dismiss="modal">ยกเลิก</button>
                      {{-- <button type="button" class="btn btn-default" data-dismiss="modal">@lang('sale_dealers/modal.cancel')</button> --}}
                      {{-- <a href="#" id="del_event" class="btn btn-danger">@lang('sale_dealers/modal.success')</a> --}}
                  </div>
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

    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.buttons.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.html5.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/buttons.bootstrap4.js') }}" ></script>

<script>

  var Dealer = {
    DealerList : [],
    DetailList : [],
    DealerDetailList : [],
    tableDealer : $('#table_dealer'),
    Dealer_tables :  function() {
         Dealer.tableDealer = $('#table_dealer').DataTable({
           "data" : Dealer.DealerDetailList,
           "destroy": true,
           paging: false,
           searching: false,
           "ordering": false,
           "bPaginate": false,
           "bLengthChange": false,
           "bFilter": true,
           "bInfo": false,
           "bAutoWidth": false,
           "scrollY": "340px",
           "scrollX": true,
            "columns": [
              { title: 'No.',"data": function(a,b,c,d) {
                        return "<div class='run_index'>"+(d.row+1)+"</div>";
              }},
              { title: 'Code',"data": function(a) {
                        return a.dealer_legacy_code;
              }},
              { title: 'IDS',"data": function(a) {
                if (a.ids_code != null) {
                  return a.dealer_ids_code;
                }else {
                  return '-';
                }
              }},
              { title: 'Zone',"data": function(a) {
                        return a.dealer_zone;
              }},
              { title: 'Area',"data": function(a) {
                        return a.dealer_area;
              }},
              { title: 'DLR',"data": function(a) {
                        return a.dealer_dlr;
              }},
              { title: 'DLR Name',"data": function(a) {
                        return a.dealer_name;
              }},
              { title: 'VIP',"data": function(a) {
                        return a.dealer_vip;
              }},
              { title: 'Press',"data": function(a) {
                        return a.dealer_press;
              }},
              { title: 'Weekday',"data": function(a) {
                        return a.dealer_weekday;
              }},
              { title: 'Weekend',"data": function(a) {
                        return a.dealer_weekend;
              }},

            ]
        });
      },
    getData : function () {

    },
    start : function() {
      Dealer.Dealer_tables();
    },
    sent : function () {
      Swal.fire({
        title: 'loading ....',
        onOpen: () => {swal.showLoading() }
      })

      $.ajax({
              type: "POST",
              url: '/admin/dealers/SentDataExcel',
              data: Dealer.DetailList,
              contentType: false,
              cache: false,
              processData: false,
              success: function( data )
              {
                swal.close();
                if (data.status != 'error') {
                  Dealer.DealerList = data.data;
                  Dealer.DealerDetailList = data.data[1];

                  Dealer.DealerDetailList = [];
                  Dealer.Dealer_tables();
                  $('#my_form')[0].reset();
                  location.reload();
                  // $('#table').DataTable().ajax.reload();
                }

              }
         })
    },
    file : $("#my_form").submit(function(event){
      event.preventDefault();
      Dealer.DetailList = new FormData(this);
      Swal.fire({
        title: 'loading ....',
        onOpen: () => {swal.showLoading() }
      })

      $.ajax({
              type: "POST",
              url: '/admin/dealers/dataExcel',
              data: Dealer.DetailList,
              contentType: false,
              cache: false,
              processData: false,
              success: function( data )
              {
                swal.close();
                if (data.status != 'error') {
                  Dealer.DealerList = data.data;
                  Dealer.DealerDetailList = data.data[1];
                  Dealer.Dealer_tables();
                }else {
                  Dealer.DealerDetailList = [];
                }

              }
         })

    }),
  }

    $(function() {
        Dealer.start();
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            "initComplete": function(settings, json) {
              for (var i = 0; i < $('.dropdown-menu').length; i++) {
                 $('.dropdown-menu')[i].style.width = '10.5rem';
               }
             },

            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.dealers.postdata') !!}',
                'data': {
                    text_search: $('#text_search').val(),
                    zone_search: $('#zone_search').val(),
                    area_search: $('#area_search').val(),
                    event_id: $('#event_id').val()
                },
            },
            columns: [
                {
                    data: null,
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'code', name: 'code' },
                { data: 'ids', name: 'ids' },
                { data: 'zone', name: 'zone' },
                { data: 'area', name: 'area' },
                { data: 'dlr', name: 'dlr' },
                { data: 'name', name: 'name'},
                { data: 'vip', name: 'vip' , orderable: false, searchable: false },
                { data: 'press', name: 'press', orderable: false, searchable: false },
                { data: 'weekday', name: 'weekday', orderable: false, searchable: false },
                { data: 'weekend', name: 'weekend', orderable: false, searchable: false },
                { data: 'actions', name: 'actions' , orderable: false, searchable: false }
            ],
            searching: false
            // ,
            // "dom": '<"pull-left m-t-0"l><"m-t-20 pull-right"B>t<"pull-left m-t-10"i><"pull-right m-t-10 "p>',
            // buttons: [
            //     'csv'
            // ]
        });
    });

    //key and enter
    $("#text_search").on('keyup', function (e) {
        //if (e.keyCode == 13) {
            //destroy first initailize
            $('#table').dataTable().fnDestroy();

            //reinitailize
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'url': '{!! route('admin.dealers.postdata') !!}',
                    'data': {
                       text_search: $('#text_search').val(),
                       zone_search: $('#zone_search').val(),
                       area_search: $('#area_search').val(),
                       event_id: $('#event_id').val()
                    },
                },
                columns: [
                    {
                        data: null,
                        sortable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'code', name: 'code' },
                    { data: 'ids', name: 'ids' },
                    { data: 'zone', name: 'zone' },
                    { data: 'area', name: 'area' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'name', name: 'name'},
                    { data: 'vip', name: 'vip' },
                    { data: 'press', name: 'press' },
                    { data: 'weekday', name: 'weekday' },
                    { data: 'weekend', name: 'weekend'},
                    { data: 'actions', name: 'actions' , orderable: false, searchable: false }
                ],
                searching: false
                // ,
                // "dom": '<"pull-left m-t-0"l><"m-t-20 pull-right"B>t<"pull-left m-t-10"i><"pull-right m-t-10 "p>',
                // buttons: [
                //     'csv'
                // ]
            });
        //}
    });

    $("#zone_search").change(function(){
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.dealers.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   zone_search: $('#zone_search').val(),
                   area_search: $('#area_search').val(),
                   event_id: $('#event_id').val()
                },
            },
            columns: [
                {
                    data: null,
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'code', name: 'code' },
                { data: 'ids', name: 'ids' },
                { data: 'zone', name: 'zone' },
                { data: 'area', name: 'area' },
                { data: 'dlr', name: 'dlr' },
                { data: 'name', name: 'name'},
                { data: 'vip', name: 'vip' },
                { data: 'press', name: 'press' },
                { data: 'weekday', name: 'weekday' },
                { data: 'weekend', name: 'weekend'},
                { data: 'actions', name: 'actions' , orderable: false, searchable: false }
            ],
            searching: false
            // ,
            // "dom": '<"pull-left m-t-0"l><"m-t-20 pull-right"B>t<"pull-left m-t-10"i><"pull-right m-t-10 "p>',
            // buttons: [
            //     'csv'
            // ]
        });

        /*
        if($('#zone_search').val() != ''){
            $.ajax({
                url:"{{ route('admin.dealers.getarea') }}",
                method:"POST",
                data:{
                  event_id: $('#event_id').val(),
                  zone: $('#zone_search').val(),
                  _token: $('input[name="_token"]').val()
                },
                success:function(result){
                $('#area_search').html(result);

                //console.log(result);
                }
            })
        }
        */
    });

    $('#area_search').change(function() {
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.dealers.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   zone_search: $('#zone_search').val(),
                   area_search: $('#area_search').val(),
                   event_id: $('#event_id').val()
                },
            },
            columns: [
                {
                    data: null,
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'code', name: 'code' },
                { data: 'ids', name: 'ids' },
                { data: 'zone', name: 'zone' },
                { data: 'area', name: 'area' },
                { data: 'dlr', name: 'dlr' },
                { data: 'name', name: 'name'},
                { data: 'vip', name: 'vip' },
                { data: 'press', name: 'press' },
                { data: 'weekday', name: 'weekday' },
                { data: 'weekend', name: 'weekend'},
                { data: 'actions', name: 'actions' , orderable: false, searchable: false }
            ],
            searching: false
        });
    });

    $('#reset_button').click(function() {

        //clear search form
        $('#text_search').val('');
        $('#zone_search').val('');
        $('#area_search').val('');

        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.dealers.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   zone_search: $('#zone_search').val(),
                   area_search: $('#area_search').val(),
                   event_id: $('#event_id').val()
                },
            },
            columns: [
                {
                    data: null,
                    sortable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'code', name: 'code' },
                { data: 'ids', name: 'ids' },
                { data: 'zone', name: 'zone' },
                { data: 'area', name: 'area' },
                { data: 'dlr', name: 'dlr' },
                { data: 'name', name: 'name'},
                { data: 'vip', name: 'vip' },
                { data: 'press', name: 'press' },
                { data: 'weekday', name: 'weekday' },
                { data: 'weekend', name: 'weekend'},
                { data: 'actions', name: 'actions' , orderable: false, searchable: false }
            ],
            searching: false
            // ,
            // "dom": '<"pull-left m-t-0"l><"m-t-20 pull-right"B>t<"pull-left m-t-10"i><"pull-right m-t-10 "p>',
            // buttons: [
            //     'csv'
            // ]
        });
    });


</script>

<!-- Modal Edit Dealer -->
<div class="modal fade" id="edit_confirm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><b>@lang('dealers/title.edit_dealer')<b></h3>
            </div>
            <hr class="ml-3 mr-3">
            <div class="modal-body">
                <form id="edit_dealer_form" action="" method="POST">
                    {{ csrf_field() }}

                    <table border="0">
                        <tr>
                            <td>
                                <div class="col-12 row p-b-10">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_legacy_code') : </label>
                                            <input class="form-control col-sm-12" id="dealer_legacy_code" name="dealer_legacy_code" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_ids_code') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_ids_code" name="dealer_ids_code" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_zone') : </label>
                                            <input class="form-control col-sm-12" id="dealer_zone" name="dealer_zone" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_area') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_area" name="dealer_area" placeholder="" type="text" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_dlr') : </label>
                                            <input class="form-control col-sm-12" id="dealer_dlr" name="dealer_dlr" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_name') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_name" name="dealer_name" placeholder="" type="text" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_vip') : </label>
                                            <input class="form-control col-sm-12" id="dealer_vip" name="dealer_vip" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_press') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_press" name="dealer_press" placeholder="" type="text" value="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_weekday') : </label>
                                            <input class="form-control col-sm-12" id="dealer_weekday" name="dealer_weekday" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            @lang('dealers/form.dealer_weekend') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_weekend" name="dealer_weekend" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <hr class="ml-3 mr-3">
            <div class="modal-footer">
                <a href="#" id="edit_dealer" class="btn btn-lg button-blue">@lang('dealers/form.save_button')</a>
                <button type="button" class="btn btn-lg button-red clear" data-dismiss="modal">@lang('dealers/form.cancel_button')</button>
            </div>
        </div>
    </div>
</div>

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/editdealer.js') }}"></script>

    <script>
        $(function () {
            $('body').on('hidden.bs.modal', '.modal', function () {
                $(this).removeData('bs.modal');
            });
        });

        //click ที่ id editDealer แล้วไปโหลดค่าจากปุ่มมาใช้
        $(document).on('click','.editDealer',function(){

            var data_id = $(this).attr('data_id');

            var data_code = $(this).attr('data_code');
            var data_ids = $(this).attr('data_ids');
            var data_zone = $(this).attr('data_zone');
            var data_area = $(this).attr('data_area');

            var data_dlr = $(this).attr('data_dlr');
            var data_dealer_name = $(this).attr('data_dealer_name');
            var data_vip = $(this).attr('data_vip');
            var data_press = $(this).attr('data_press');
            var data_weekday = $(this).attr('data_weekday');
            var data_weekend = $(this).attr('data_weekend');

            //set ค่าใส่ input
            $('#dealer_legacy_code.col-sm-12').val(data_code);
            $('#dealer_ids_code.col-sm-12').val(data_ids);
            $('#dealer_zone.col-sm-12').val(data_zone);
            $('#dealer_area.col-sm-12').val(data_area);
            $('#dealer_dlr.col-sm-12').val(data_dlr);
            $('#dealer_name.col-sm-12').val(data_dealer_name);
            $('#dealer_vip.col-sm-12').val(data_vip);
            $('#dealer_press.col-sm-12').val(data_press);
            $('#dealer_weekday.col-sm-12').val(data_weekday);
            $('#dealer_weekend.col-sm-12').val(data_weekend);

            var obj = document.getElementById('edit_dealer_form');
            obj.setAttribute('action', '{!! URL::to('admin/dealers/') !!}'+'/'+data_id+'/update');

            $('#edit_confirm').modal('show');
        });

        //reset error message on close or cancel modal
        $(document).on('click','.clear',function(){
            $('#edit_dealer_form').bootstrapValidator('resetForm', true);
        });

        //reset error message on hide modal
        $('#edit_confirm').on('hide.bs.modal', function () {
            $('#edit_dealer_form').bootstrapValidator('resetForm', true);
        });

    </script>

@stop
