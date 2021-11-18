@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('dealer_details/title.dealer_details_list') 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    
{{-- time format --}}
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .dot-green {
        color: #2eb35d;
    }
    .dot-gray {
        color: #c1bfc0;
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
                <td class="p-l-40 p-t-20">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}" style="color: #369DE2;">@lang('events/title.events_list')</a>
                
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                
                    <a class="fs-20" href="{{ URL::to('admin/events/'.$event->id.'/dealers') }}" style="color: #369DE2;">@lang('dealers/title.dealers_list')</a>
                
                    <a class="fs-20" style="color: #7b7e82;">/</a>
               
                    <a class="active fs-20" style="color: #7b7e82;">@lang('dealer_details/title.dealer_details_list')</a>
                </td>
            </tr>
        </table>

        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary ">
            
            <div class="card-body">
                
                <div class="card-body"> 
                    <b style="font-size: 20px;">@lang('dealer_details/title.dealer_details_list') Event :</b> <b style="color: #369DE2; font-size: 20px;"> {{ $event->event_name }}</b>
                </div>

                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td style="width: 20%;">
                                <label class="control-label">@lang('dealer_details/form.dealer_detail_dlr') : </label> <label style="color: #369DE2;">{{ $dealer->dealer_dlr }}</label>
                            </td>
                            <td>
                                <label class="control-label">@lang('dealer_details/form.dealer_detail_dlr') : </label> <label style="color: #369DE2;">{{ $dealer->dealer_name }}</label>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 20%;">
                                <label class="control-label">@lang('dealer_details/form.dealer_detail_zone') : </label> <label style="color: #369DE2;">{{ $dealer->dealer_zone }}</label>
                            </td>
                            <td>
                                <label class="control-label">@lang('dealer_details/form.dealer_detail_area') : </label> <label style="color: #369DE2;">{{ $dealer->dealer_area }}</label>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="ml-3 mr-3">


                <div class="form-group">
                    <div class="col-12 row">
                        
                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('dealer_details/form.text_search') : </label>
                            <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('dealer_details/form.type_search') : </label>
                            <select class="form-control required" name="type_search" id="type_search">
                                <option value="">@lang('dealer_details/form.select_type')</option>
                                <option value="VIP">@lang('dealer_details/form.vip')</option>
                                <option value="PRESS">@lang('dealer_details/form.press')</option>
                                <option value="Weekday">@lang('dealer_details/form.weekday')</option>
                                <option value="Weekend">@lang('dealer_details/form.weekend')</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3  col-lg-3" style="padding-top: 22px; padding-left: 0px;">

                            <button class="btn btn-outline-dark" id="reset_button" style="margin-left: 5px;">
                                <span class="fa fa-refresh"></span>
                            </button>
                        </div>

                        <input type="hidden" name="dealer_id" id="dealer_id" value="{{ $dealer->id }}">
                    </div>
                </div>

                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table">
                    <thead>
                        <tr class="filters">
                            <th>#</th>
                            <th>@lang('dealer_details/table.date')</th>
                            <th>@lang('dealer_details/table.type')</th>
                            <th>@lang('dealer_details/table.amount')</th>
                            <th>@lang('dealer_details/table.brief_time')</th>
                            <th>@lang('dealer_details/table.checkout_time')</th>
                            <th>@lang('dealer_details/table.actions')</th>
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
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

<div class="modal fade" id="delete_confirm" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('dealer_details/modal.title')</h4>
            </div>
            <div class="modal-body">
                @lang('dealer_details/modal.body')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('dealer_details/modal.cancel')</button>
                <a href="#" id="del_dealer" class="btn btn-danger">@lang('dealer_details/modal.delete')</a>
            </div>
        </div>
      
    </div>
</div>

<!-- Modal Edit Dealer deatail -->
<div class="modal fade" id="edit_confirm" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><b>@lang('dealer_details/title.edit_dealer_detail')</b></h3>
                <!-- test-header -->
            </div>

            <form id="edit_dealer_detail_form" action="" method="POST">
                {{ csrf_field() }}

                <table border="0">
                    <tr>
                        <td>
                            <label class="control-label" style="padding-left: 16px;">
                            @lang('dealer_details/form.dealer_detail_dlr') : </label> 
                            <label class="dealer_detail_dlr" style="color: #369DE2;"></label>
                        </td>
                        <td>
                            <label class="control-label">
                            @lang('dealer_details/form.dealer_detail_name') : </label> 
                            <label class="dealer_detail_name" style="color: #369DE2;"></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label" style="padding-left: 16px;">
                            @lang('dealer_details/form.dealer_detail_zone') : </label> 
                            <label class="dealer_detail_zone" style="color: #369DE2;"></label>
                        </td>
                        <td>
                            <label class="control-label">
                            @lang('dealer_details/form.dealer_detail_area') : </label> 
                            <label class="dealer_detail_area" style="color: #369DE2;"></label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label class="control-label" style="padding-left: 16px;">
                            @lang('dealer_details/form.dealer_detail_date') : </label> 
                            <label class="dealer_detail_date" style="color: #369DE2;"></label>
                        </td>
                        <td>
                            <label class="control-label">
                            @lang('dealer_details/form.dealer_detail_type') : </label> 
                            <label class="dealer_detail_type" style="color: #369DE2;"></label>
                        </td>
                    </tr>
                </table>

                <hr class="ml-3 mr-3">

                <table border="0">
                    <tr>
                        <td>
                            <div class="col-12 row p-b-10">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="p-b-5">
                                        @lang('dealer_details/form.dealer_detail_amount') : </label>
                                        <input class="form-control col-sm-12" id="dealer_detail_amount" name="dealer_detail_amount" placeholder="" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"></div>
                                </div>
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="col-12 row p-b-10">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="p-b-5">
                                        @lang('dealer_details/form.dealer_detail_brief_time') : </label>
                                        <input class="form-control col-sm-12" id="dealer_detail_brief_time" name="dealer_detail_brief_time" placeholder="" type="text" value="" data-mask="99:99:99">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="p-b-5">
                                        @lang('dealer_details/form.dealer_detail_checkout_time') : </label>
                                        <input class="form-control col-sm-12 required" id="dealer_detail_checkout_time" name="dealer_detail_checkout_time" placeholder="" type="text" value="" data-mask="99:99:99">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <hr class="ml-3 mr-3">

                <div class="modal-footer">
                    <a href="#" id="edit_dealer_detail" class="btn btn-lg button-blue">@lang('dealer_details/form.save_button')</a>
                    <button type="button" class="btn btn-lg button-red" data-dismiss="modal">@lang('dealer_details/form.cancel_button')</button>
                </div>
            </form>
        </div>
      
    </div>
</div>

<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>

<script>
    $(function() {   

        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.dealer_details.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   type_search: $('#type_search').val(),
                   dealer_id: $('#dealer_id').val()
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
                { data: 'date', name: 'date' },
                { data: 'type', name: 'type' },
                { data: 'amount', name: 'amount' },
                { data: 'brief_time', name: 'brief_time' },
                { data: 'checkout_time', name: 'checkout_time' },
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
                    'url': '{!! route('admin.dealer_details.postdata') !!}',
                    'data': {
                       text_search: $('#text_search').val(),
                       type_search: $('#type_search').val(),
                       dealer_id: $('#dealer_id').val()
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
                    { data: 'date', name: 'date' },
                    { data: 'type', name: 'type' },
                    { data: 'amount', name: 'amount' },
                    { data: 'brief_time', name: 'brief_time' },
                    { data: 'checkout_time', name: 'checkout_time' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                searching: false
            });
        //}
    });

    $("#type_search").change(function(){
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.dealer_details.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   type_search: $('#type_search').val(),
                   dealer_id: $('#dealer_id').val()
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
                { data: 'date', name: 'date' },
                { data: 'type', name: 'type' },
                { data: 'amount', name: 'amount' },
                { data: 'brief_time', name: 'brief_time' },
                { data: 'checkout_time', name: 'checkout_time' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            searching: false
        });
    });

    $('#reset_button').click(function() {

        //clear search form
        $('#text_search').val('');
        $('#type_search').val('');
        
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.dealer_details.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   type_search: $('#type_search').val(),
                   dealer_id: $('#dealer_id').val()
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
                { data: 'date', name: 'date' },
                { data: 'type', name: 'type' },
                { data: 'amount', name: 'amount' },
                { data: 'brief_time', name: 'brief_time' },
                { data: 'checkout_time', name: 'checkout_time' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            searching: false
        });
    });

</script>

    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

    {{-- time format --}}
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/editdealerdetail.js') }}"></script>

    <script>
        $(function () {
            $('body').on('hidden.bs.modal', '.modal', function () {
                $(this).removeData('bs.modal');
            });
        });

        //click ที่ id deleteDealer แล้วไปโหลดค่าจากปุ่มมาใช้
        $(document).on('click','.editDealerDetail',function(){

            var data_id = $(this).attr('data_id');

            var data_date = $(this).attr('data_date');
            var data_type = $(this).attr('data_type');
            var data_amount = $(this).attr('data_amount');
            var data_brief_time = $(this).attr('data_brief_time');
            var data_checkout_time = $(this).attr('data_checkout_time');
            var data_dealer_id = $(this).attr('data_dealer_id');
            var data_dlr = $(this).attr('data_dlr');
            var data_name = $(this).attr('data_name');
            var data_zone = $(this).attr('data_zone');
            var data_area = $(this).attr('data_area');

            //set ค่าใส่ input
            $('#dealer_detail_amount.col-sm-12').val(data_amount);
            $('#dealer_detail_brief_time.col-sm-12').val(data_brief_time);
            $('#dealer_detail_checkout_time.col-sm-12').val(data_checkout_time);
            
            //set ค่าใส่ label
            $("label.dealer_detail_dlr").text(data_dlr);
            $("label.dealer_detail_name").text(data_name);
            $("label.dealer_detail_zone").text(data_zone);
            $("label.dealer_detail_area").text(data_area);
            $("label.dealer_detail_date").text(data_date);
            $("label.dealer_detail_type").text(data_type);

            //set ค่าให้ปุ่มที่มี id del_sale_dealer ว่ากดแล้วไปที่ url ไหน และเปิด modal ที่มี id delete_confirm
            //var obj = document.getElementById('edit_sale_dealer');
            //obj.setAttribute('href', window.location.href+'/'+data_id+'/update');

            var obj = document.getElementById('edit_dealer_detail_form');
            obj.setAttribute('action', '{!! URL::to('admin/dealer_details/') !!}'+'/'+data_id+'/update');

            $('#edit_confirm').modal('show');
        });

        //reset error message on close or cancel modal
        $(document).on('click','.clear',function(){
            $('#edit_dealer_detail_form').bootstrapValidator('resetForm', true); 
        });

        //reset error message on hide modal
        $('#edit_confirm').on('hide.bs.modal', function () {
            $('#edit_dealer_detail_form').bootstrapValidator('resetForm', true); 
        });

    </script>
@stop
