@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('checkin_checkout/title.checkin_checkout_list') 
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .daterange-input{ 
        background: url(../../../assets/img/calendar_icon.png) no-repeat right 5px center;
        background-color: #FFFFFF;
    }
</style>
@stop


{{-- Page content --}}
@section('content')
<section class="content paddingleft_right15">
    <div class="row">
        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20" align="left">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="fs-20" style="color: #7b7e82;">@lang('checkin_checkout/title.checkin_checkout_list')</a>
                </td>
            </tr>
        </table>
    
        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary">
            <div class="card-body">

                <div class="card-body" style="padding-bottom: 1px;">
                    <table border="0">
                        <tr>
                            <td align="right">
                                <a href="{{ url('admin/checkin_checkout/checkin') }}" class="btn btn-lg button-blue">@lang('checkin_checkout/form.checkin')</a>
                                <a href="{{ url('admin/checkin_checkout/checkout') }}" class="btn btn-lg button-black">@lang('checkin_checkout/form.checkout')</a>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td align="left" class="fs-26">
                               <b>@lang('checkin_checkout/title.checkin_checkout_list') : </b><b style="color: #369DE2;">{{ $event->event_name }}</b>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="ml-3 mr-3">


                <div class="form-group">
                    <div class="col-12 row">
                        
                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('checkin_checkout/form.text_search') : </label>
                            <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/>
                        </div>
                        <div class="col-6 col-sm-6 col-md-3  col-lg-4">
                            <label for="input">@lang('checkin_checkout/form.daterange_search') : </label>
                            <input type="text" class="form-control daterange-input" name="daterange_search" id="daterange_search" autocomplete="off" placeholder="วว/ดด/ปป - วว/ดด/ปป"/>
                        </div>
                        <div class="col-6 col-sm-6 col-md-2  col-lg-2">
                            <label for="input">@lang('checkin_checkout/form.dlr_search') : </label>
                            <select class="form-control required" name="dlr_search" id="dlr_search">
                                <option value="">@lang('checkin_checkout/form.select_dlr')</option>
                                @foreach($dlr as $d)
                                    <option value="{{ $d->dlr_name }}">{{ $d->dlr_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6 col-sm-6 col-md-3  col-lg-3" style="bottom: -20px; padding-left: 0px;">
                            <!--
                            <button class="btn btn-success" id="search_button" style="margin-left: 5px;">
                                <span class="fa fa-search"></span>
                            </button>
                            -->
                            <button class="btn btn-outline-dark" id="reset_button" style="margin-left: 5px;">
                                <span class="fa fa-refresh"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table">
                    <thead>
                        <tr class="filters">
                            <th>#</th>
                            <th>@lang('checkin_checkout/table.date')</th>
                            <th>@lang('checkin_checkout/table.dlr')</th>
                            <th>@lang('checkin_checkout/table.name')</th>
                            <th>@lang('checkin_checkout/table.id_sale')</th>
                            <th>@lang('checkin_checkout/table.sale_name')</th>
                            <th>@lang('checkin_checkout/table.checkin')</th>
                            <th>@lang('checkin_checkout/table.checkout')</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
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

    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/clockface/js/clockface.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/datepicker.js') }}" type="text/javascript"></script>

<script>
    $(function() {   
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.checkin_checkout.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   daterange_search: $('#daterange_search').val(),
                   dlr_search: $('#dlr_search').val()
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
                { data: 'dlr', name: 'dlr' },
                { data: 'dealer_name', name: 'dealer_name' },
                { data: 'id_sale', name: 'id_sale' },
                { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                { data: 'checkin', name: 'checkin'},
                { data: 'checkout', name: 'checkout'}
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
                    'url': '{!! route('admin.checkin_checkout.postdata') !!}',
                    'data': {
                       text_search: $('#text_search').val(),
                       daterange_search: $('#daterange_search').val(),
                       dlr_search: $('#dlr_search').val()
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
                    { data: 'dlr', name: 'dlr' },
                    { data: 'dealer_name', name: 'dealer_name' },
                    { data: 'id_sale', name: 'id_sale' },
                    { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                    { data: 'checkin', name: 'checkin'},
                    { data: 'checkout', name: 'checkout'}
                ],
                searching: false
            });
        //}
    });

    $("#daterange_search").on('apply.daterangepicker', function(ev, picker) {
        var daterange = picker.startDate.format('DD/MM/YYYY')+' - '+picker.endDate.format('DD/MM/YYYY')

        $(this).val(daterange);

        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.checkin_checkout.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   daterange_search: daterange,
                   dlr_search: $('#dlr_search').val()
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
                { data: 'dlr', name: 'dlr' },
                { data: 'dealer_name', name: 'dealer_name' },
                { data: 'id_sale', name: 'id_sale' },
                { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                { data: 'checkin', name: 'checkin'},
                { data: 'checkout', name: 'checkout'}
            ],
            searching: false
        });
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
                'url': '{!! route('admin.checkin_checkout.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   daterange_search: $('#daterange_search').val(),
                   dlr_search: $('#dlr_search').val()
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
                { data: 'dlr', name: 'dlr' },
                { data: 'dealer_name', name: 'dealer_name' },
                { data: 'id_sale', name: 'id_sale' },
                { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                { data: 'checkin', name: 'checkin'},
                { data: 'checkout', name: 'checkout'}
            ],
            searching: false
        });
    });

    $('#reset_button').click(function() {

        //clear search form
        $('#text_search').val('');
        $('#daterange_search').val('');
        $('#dlr_search').val('');
        
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.checkin_checkout.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   daterange_search: $('#daterange_search').val(),
                   dlr_search: $('#dlr_search').val()
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
                { data: 'dlr', name: 'dlr' },
                { data: 'dealer_name', name: 'dealer_name' },
                { data: 'id_sale', name: 'id_sale' },
                { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                { data: 'checkin', name: 'checkin'},
                { data: 'checkout', name: 'checkout'}
            ],
            searching: false
        });
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
