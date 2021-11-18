@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('sale_dealers/title.sale_dealers_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">




@stop


{{-- Page content --}}
@section('content')
  <style >
    span.select2.select2-container.select2-container--default.select2-container--below.select2-container--open {
      width: 85% !important;
    }
  </style>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}" style="color: #369DE2;">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="active fs-20" style="color: #7b7e82;">@lang('sale_dealers/title.sale_dealers_list')</a>
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
                                <a href="/admin/dealers/downloadfile/sale" class="btn btn-lg button-black"><span class="fa fa-download"></span> @lang('sale_dealers/form.download_button')</a>
                                <a href="" class="btn btn-lg button-red" data-toggle="modal" data-target="#import_sale_dealer"><span class="fa fa-plus"></span> @lang('sale_dealers/form.import_button')</a>

                              @endif

                              <a href="" class="btn btn-lg button-red" data-toggle="modal" data-target="#add_confirm"><span class="fa fa-plus"></span> Sale Dealer</a>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td align="left" class="fs-26">
                               <b>@lang('sale_dealers/title.sale_dealers_list') : </b> <b style="color: #369DE2;">{{ $event->event_name }} </b>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="ml-3 mr-3">


                <div class="form-group">
                    <div class="col-12 row">

                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('sale_dealers/form.text_search') : </label>
                            <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/>
                        </div>
                        <div class="col-6 col-sm-6 col-md-2  col-lg-2">
                            <label for="input">@lang('sale_dealers/form.dlr_search') : </label>
                            <select class="form-control required" name="dlr_search" id="dlr_search">
                                <option value="">@lang('sale_dealers/form.select_zone')</option>
                                @foreach($dlr as $d)
                                    <option value="{{ $d->dlr_name }}">{{ $d->dlr_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-sm-6 col-md-2  col-lg-2">
                            <label for="input">@lang('sale_dealers/form.zone_search') : </label>
                            <select class="form-control required" name="zone_search" id="zone_search">
                                <option value="">@lang('sale_dealers/form.select_zone')</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->zone_name }}">{{ $zone->zone_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-sm-6 col-md-2  col-lg-2">
                            <label for="input">@lang('sale_dealers/form.area_search') : </label>
                            <select class="form-control required" name="area_search" id="area_search">
                                <option value="">@lang('sale_dealers/form.select_area')</option>
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
                            <th>@lang('sale_dealers/table.id_sale')</th>
                            <th>@lang('sale_dealers/table.sale_name')</th>
                            <th>@lang('sale_dealers/table.nickname')</th>
                            <th>@lang('sale_dealers/table.tel_no')</th>
                            <th>@lang('sale_dealers/table.ids')</th>
                            <th>@lang('sale_dealers/table.legacy')</th>
                            <th>@lang('sale_dealers/table.zone')</th>
                            <th>@lang('sale_dealers/table.area')</th>
                            <th>@lang('sale_dealers/table.dlr')</th>
                            <th>@lang('sale_dealers/table.dlr_name')</th>
                            <th>@lang('sale_dealers/table.actions')</th>
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
      <div class="modal fade" id="import_sale_dealer" role="dialog">
          <div class="modal-dialog modal-xl">

              <div class="modal-content">
                  <div class="modal-header">
                      <h3 class="modal-title">@lang('sale_dealers/title.sale_dealers_list')</h3>
                  </div>
                  <div class="modal-body">
                       <form id="my_form">
                            <table>
                                <tr>
                                    <td>
                                         เลือกไฟล์ : <input type='file' name='file' id='file' class='form-control col-sm-12' style="color: #369DE2;" ><br>
                                    </td>
                                    <td>
                                        <button type="submit" name="button" type="button" class="btn button-test"><i class="fas fa-file-import" aria-hidden="true"></i> นำเข้าข้อมูล</button>
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
                    <button onclick="Sale.re_check_alert()" name="button" type="button" class="btn btn-lg button-blue" data-dismiss="modal">ยืนยันนำเข้าข้อมูล</button>
                    <button type="button" class="btn btn-lg button-red" data-dismiss="modal">ยกเลิก</button>
                  </div>
              </div>

          </div>
      </div>
    </div>

</section>

<!-- Modal Edit Sale Dealer -->
<div class="modal fade" id="add_confirm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><b>เพิ่ม Sale Dealer</b></h3>
                <!-- test-header -->
            </div>
            <form id="add_sale_dealer_form">
            <div class="modal-body">

                {{ csrf_field() }}

                <table border="0">
                  <tr>
                      <td>
                          <div class="col-12 row p-b-10">
                              <div class="col-sm-12">
                                  <div class="form-group">
                                      <label class="p-b-5">@lang('sale_dealers/form.dealer_name') : </label>
                                      <select class="form-control col-10" id="add_dealer_name" name="add_dealer_name" style="width: 85%">
                                        <option value="" disabled selected>- ทั้งหมด -</option>
                                        @foreach($dealer_legacy_code as $d)
                                            <option value="@php echo $d->id."_".$d->dealer_legacy_code; @endphp">{{ $d->name}}</option>
                                        @endforeach
                                      </select>
                                  </div>
                              </div>
                          </div>
                      </td>
                  </tr>
                    <tr>
                        <td>
                            <div class="col-12 row p-b-10">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_code') : </label>
                                        <input class="form-control col-sm-12" id="add_sale_dealer_code" name="add_sale_dealer_code" placeholder="" type="text" value="" >
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_name') : </label>
                                        <input class="form-control col-sm-12 required" id="add_sale_dealer_name" name="add_sale_dealer_name" placeholder="" type="text" value="">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-12 row p-b-10">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_nickname') : </label>
                                        <input class="form-control col-sm-12" id="add_sale_dealer_nickname" name="add_sale_dealer_nickname" placeholder="" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_tel') : </label>
                                        <input class="form-control col-sm-12 required" id="add_add_sale_dealer_tel" name="add_sale_dealer_tel" placeholder="" type="text" value="">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
              <button type="submit" name="button" type="button" class="btn btn-lg button-blue" >@lang('sale_dealers/form.save_button')</button>
                {{-- <a href="#" id="edit_sale_dealer" class="btn btn-lg button-blue">@lang('sale_dealers/form.save_button')</a> --}}
                <button type="button" class="btn btn-lg button-red" data-dismiss="modal" onclick="return $('#add_sale_dealer_form')[0].reset();">@lang('sale_dealers/form.cancel_button')</button>
            </div>
            </form>
        </div>
    </div>
</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>

    <script>

    var Sale = {
      DealerList : [],
      DetailList : [],
      SaleDetailList : [],
      tableSale : $('#table_dealer'),
      Sale_tables :  function() {
           Sale.tableSale = $('#table_dealer').DataTable({
             "data" : Sale.SaleDetailList,
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
                { title: '#',"data": function(a,b,c,d) {
                          return "<div class='run_index'>"+(d.row+1)+"</div>";
                }},
                { title: 'ID Sale',"data": function(a) {
                          return a.sale_dealer_code;
                }},
                { title: 'Sale Name',"data": function(a) {
                          return a.sale_dealer_name;
                }},
                { title: 'Nickname',"data": function(a) {
                          return a.sale_dealer_nickname;
                }},
                { title: 'Mobile',"data": function(a) {
                          return a.sale_dealer_tel;
                }},
                { title: 'Legacy',"data": function(a) {
                          return a.dealer_legacy_code;
                }}
              ]
          });
        },
      getData : function () {

      },
      start : function() {
        Sale.Sale_tables();
        $('#add_dealer_name').select2({ width: 'resolve' });
        // $('#add_confirm').on('shown.bs.modal', function(e) {
        //   // alert('555555555555555');
        //   // do cool stuff here all day… no need to change bootstrap
        //   $('span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus')[0].style.width='85%';
        // })
        // $('#add_confirm').click(function(e) {
        //      e.stopPropagation();
        //      $('#add_confirm .modal-body').load(this.href, function(response, status, xhr) {
        //          /* do something cool */
        //          $('#foo').modal();
        //      });
        //  });
        // $('#add_confirm').on('shown.bs.modal', function (e) {
        //
        //   $('span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus')[0].style.width='85%';
        // })
        // $( "#add_confirm" ).on('shown', function(){
        //     $('span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus')[0].style.width='85%';
        // });
        // $('span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus')[0].style.width='85%'
      },
      re_check_alert : function() {
        Swal.fire({
          title: 'คำเตือน',
          text: "คุณกำลัง Import File SaleDealer หลักอีกครั้ง  ข้อมูลปัจจุบันจะถูก Update ใหม่ตามไฟล์ Import ล่าสุด",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'ยืนยัน',
          cancelButtonText: 'ยกเลิก'
        }).then((result) => {
          if (result.value) {
            Sale.sent();
          }
        })
      },
      sent : function () {
        Swal.fire({
          title: 'loading ....',
          onOpen: () => {swal.showLoading() }
        })
        $.ajax({
                type: "POST",
                url: '/admin/saledealers/SentDataExcel',
                data: Sale.DetailList,
                contentType: false,
                cache: false,
                processData: false,
                success: function( data )
                {
                  swal.close();
                  if (data.status != 'error') {
                    Sale.SaleDetailList = [];
                    Sale.Sale_tables();
                    $('#my_form')[0].reset();
                    location.reload();
                  }

                }
           })
      },
      file : $("#my_form").submit(function(event){
        event.preventDefault();
        Sale.DetailList = new FormData(this);
        Swal.fire({
          title: 'loading ....',
          onOpen: () => {swal.showLoading() }
        })
        $.ajax({
                type: "POST",
                url: '/admin/saledealers/dataExcel',
                data: Sale.DetailList,
                contentType: false,
                cache: false,
                processData: false,
                success: function( data )
                {
                  console.log(data);
                  swal.close();
                  if (data.status != 'error') {
                    Sale.SaleDetailList = data.data;
                    Sale.Sale_tables();
                  }

                }
           })

      }),
      add_sale : $("#add_sale_dealer_form").submit(function(event){
        if ($('#add_dealer_name').val() == null ) {
          swal.fire({
              type: 'error',
              title: 'Oops...',
              text: 'กรุณาเลือก DLR Name',
            })
            return false;
        }

        if ($('#add_sale_dealer_code').val() == null ) {
          swal.fire({
              type: 'error',
              title: 'Oops...',
              text: 'กรุณากรอกข้อมูล ID Sale',
            })
            return false;
        }

        if ($('#add_sale_dealer_name').val() == null ) {
          swal.fire({
              type: 'error',
              title: 'Oops...',
              text: 'กรุณากรอกข้อมูล Sale Name',
            })
            return false;
        }

        event.preventDefault();
        var DetailList = new FormData(this);
        console.log(DetailList);
        Swal.fire({
          title: 'loading ....',
          onOpen: () => {swal.showLoading() }
        })
        $.ajax({
                type: "POST",
                url: '/admin/InsertSaleDealer',
                data: DetailList,
                contentType: false,
                cache: false,
                processData: false,
                success: function( data )
                {
                  console.log(data);
                  $('#add_confirm').modal("hide");
                  $('#add_sale_dealer_form')[0].reset();
                  swal.close();
                  if (data.status == 'error') {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: data.messages,
                      })
                      return false;
                  }


                }
           })

      }),
    }
        $(function() {
            Sale.start();
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'url': '{!! route('admin.sale_dealers.postdata') !!}',
                    'data': {
                        text_search: $('#text_search').val(),
                        dlr_search: $('#dlr_search').val(),
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
                    { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                    { data: 'nickname', name: 'nickname' },
                    { data: 'tel', name: 'tel' },
                    { data: 'ids', name: 'ids' },
                    { data: 'legacy', name: 'legacy'},
                    { data: 'zone', name: 'zone' },
                    { data: 'area', name: 'area' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'dealer_name', name: 'dealer_name'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                searching: false
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
                        'url': '{!! route('admin.sale_dealers.postdata') !!}',
                        'data': {
                           text_search: $('#text_search').val(),
                           dlr_search: $('#dlr_search').val(),
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
                        { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                        { data: 'nickname', name: 'nickname' },
                        { data: 'tel', name: 'tel' },
                        { data: 'ids', name: 'ids' },
                        { data: 'legacy', name: 'legacy'},
                        { data: 'zone', name: 'zone' },
                        { data: 'area', name: 'area' },
                        { data: 'dlr', name: 'dlr' },
                        { data: 'dealer_name', name: 'dealer_name'},
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ],
                    searching: false
                });
            //}
        });

        $("#dlr_search").change(function(){
            //destroy first initailize
            $('#table').dataTable().fnDestroy();

            //reinitailize
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'url': '{!! route('admin.sale_dealers.postdata') !!}',
                    'data': {
                        text_search: $('#text_search').val(),
                        dlr_search: $('#dlr_search').val(),
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
                    { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                    { data: 'nickname', name: 'nickname' },
                    { data: 'tel', name: 'tel' },
                    { data: 'ids', name: 'ids' },
                    { data: 'legacy', name: 'legacy'},
                    { data: 'zone', name: 'zone' },
                    { data: 'area', name: 'area' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'dealer_name', name: 'dealer_name'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                searching: false
            });

            /*
            if($('#dlr_search').val() != ''){
                $.ajax({
                    url:"{{ route('admin.sale_dealers.getzone') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_id').val(),
                      dlr: $('#dlr_search').val(),
                      _token: $('input[name="_token"]').val()
                    },
                    success:function(result){
                    $('#zone_search').html(result);
                    $('#area_search').html('<option value="">- ทั้งหมด -</option>');

                    //console.log(result);
                    }
                })
            }
            */
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
                    'url': '{!! route('admin.sale_dealers.postdata') !!}',
                    'data': {
                        text_search: $('#text_search').val(),
                        dlr_search: $('#dlr_search').val(),
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
                    { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                    { data: 'nickname', name: 'nickname' },
                    { data: 'tel', name: 'tel' },
                    { data: 'ids', name: 'ids' },
                    { data: 'legacy', name: 'legacy'},
                    { data: 'zone', name: 'zone' },
                    { data: 'area', name: 'area' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'dealer_name', name: 'dealer_name'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                searching: false
            });

            /*
            if($('#zone_search').val() != ''){
                $.ajax({
                    url:"{{ route('admin.sale_dealers.getarea') }}",
                    method:"POST",
                    data:{
                      event_id: $('#event_id').val(),
                      dlr: $('#dlr_search').val(),
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

        $("#area_search").change(function(){
            //destroy first initailize
            $('#table').dataTable().fnDestroy();

            //reinitailize
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'url': '{!! route('admin.sale_dealers.postdata') !!}',
                    'data': {
                        text_search: $('#text_search').val(),
                        dlr_search: $('#dlr_search').val(),
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
                    { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                    { data: 'nickname', name: 'nickname' },
                    { data: 'tel', name: 'tel' },
                    { data: 'ids', name: 'ids' },
                    { data: 'legacy', name: 'legacy'},
                    { data: 'zone', name: 'zone' },
                    { data: 'area', name: 'area' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'dealer_name', name: 'dealer_name'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                searching: false
            });
        });

        $('#reset_button').click(function() {

            //clear search form
            $('#text_search').val('');
            $('#dlr_search').val('');
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
                    'url': '{!! route('admin.sale_dealers.postdata') !!}',
                    'data': {
                        text_search: $('#text_search').val(),
                        dlr_search: $('#dlr_search').val(),
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
                    { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                    { data: 'nickname', name: 'nickname' },
                    { data: 'tel', name: 'tel' },
                    { data: 'ids', name: 'ids' },
                    { data: 'legacy', name: 'legacy'},
                    { data: 'zone', name: 'zone' },
                    { data: 'area', name: 'area' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'dealer_name', name: 'dealer_name'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                searching: false
            });
        });

    </script>

    <!-- Modal Edit Sale Dealer -->
    <div class="modal fade" id="edit_confirm" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><b>แก้ไข Sale Dealer</b></h3>
                    <!-- test-header -->
                </div>
                <div class="modal-body">
                    <form id="edit_sale_dealer_form" action="" method="POST">
                    {{ csrf_field() }}

                    <table border="0">
                        <tr>
                            <td>
                                <div class="col-12 row p-b-10">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_code') : </label>
                                            <input class="form-control col-sm-12" id="sale_dealer_code" name="sale_dealer_code" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_name') : </label>
                                            <input class="form-control col-sm-12 required" id="sale_dealer_name" name="sale_dealer_name" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-12 row p-b-10">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_nickname') : </label>
                                            <input class="form-control col-sm-12" id="sale_dealer_nickname" name="sale_dealer_nickname" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.sale_dealer_tel') : </label>
                                            <input class="form-control col-sm-12 required" id="sale_dealer_tel" name="sale_dealer_tel" placeholder="" type="text" value="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-12 row p-b-10">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.dealer_ids_code') : </label>
                                            <input class="form-control col-sm-12" id="dealer_ids_code" name="dealer_ids_code" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.dealer_legacy_code') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_legacy_code" name="dealer_legacy_code" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-12 row p-b-10">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.dealer_zone') : </label>
                                            <input class="form-control col-sm-12" id="dealer_zone" name="dealer_zone" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.dealer_area') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_area" name="dealer_area" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="col-12 row p-b-10">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.dealer_dlr') : </label>
                                            <input class="form-control col-sm-12" id="dealer_dlr" name="dealer_dlr" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">@lang('sale_dealers/form.dealer_name') : </label>
                                            <input class="form-control col-sm-12 required" id="dealer_name" name="dealer_name" placeholder="" type="text" value="" disabled>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" id="edit_sale_dealer" class="btn btn-lg button-blue">@lang('sale_dealers/form.save_button')</a>
                    <button type="button" class="btn btn-lg button-red" data-dismiss="modal">@lang('sale_dealers/form.cancel_button')</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/editsaledealer.js') }}"></script>

    <script>
        $(function () {
            $('body').on('hidden.bs.modal', '.modal', function () {
                $(this).removeData('bs.modal');
            });
        });

        //click ที่ id deleteSaleDealer แล้วไปโหลดค่าจากปุ่มมาใช้
        $(document).on('click','.editSaleDealer',function(){

            var data_id = $(this).attr('data_id');

            var data_code = $(this).attr('data_code');
            var data_sale_dealer_name = $(this).attr('data_sale_dealer_name');
            var data_nickname = $(this).attr('data_nickname');
            var data_tel = $(this).attr('data_tel');
            var data_ids = $(this).attr('data_ids');
            var data_legacy = $(this).attr('data_legacy');
            var data_zone = $(this).attr('data_zone');
            var data_area = $(this).attr('data_area');
            var data_dlr = $(this).attr('data_dlr');
            var data_dealer_name = $(this).attr('data_dealer_name');

            //set ค่าใส่ input
            $('#sale_dealer_code.col-sm-12').val(data_code);
            $('#sale_dealer_name.col-sm-12').val(data_sale_dealer_name);
            $('#sale_dealer_nickname.col-sm-12').val(data_nickname);
            $('#sale_dealer_tel.col-sm-12').val(data_tel);
            $('#dealer_ids_code.col-sm-12').val(data_ids);
            $('#dealer_legacy_code.col-sm-12').val(data_legacy);
            $('#dealer_zone.col-sm-12').val(data_zone);
            $('#dealer_area.col-sm-12').val(data_area);
            $('#dealer_dlr.col-sm-12').val(data_dlr);
            $('#dealer_name.col-sm-12').val(data_dealer_name);

            var obj = document.getElementById('edit_sale_dealer_form');
            obj.setAttribute('action', '{!! URL::to('admin/sale_dealers/') !!}'+'/'+data_id+'/update');

            $('#edit_confirm').modal('show');
        });


        //reset error message on close or cancel modal
        $(document).on('click','.clear',function(){
            $('#edit_sale_dealer_form').bootstrapValidator('resetForm', true);
        });

        //reset error message on hide modal
        $('#edit_confirm').on('hide.bs.modal', function () {
            $('#edit_sale_dealer_form').bootstrapValidator('resetForm', true);
        });
    </script>


@stop
