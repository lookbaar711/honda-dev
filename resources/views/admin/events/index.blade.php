@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('events/title.events_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
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
<section class="content paddingleft_right15">
    <div class="row" style="padding-top: 30px;">
        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
            <div class="card panel-primary">
                <div class="card-body">

                    <div class="card-body">
                        <table border="0">
                            <tr>
                                <td align="left" class="fs-26">
                                   <b>@lang('events/title.events_list')</b>
                                </td>
                                <td align="right">
                                  @if (Sentinel::getUser()->status_process == 0)
                                    <a href="{{ route('admin.events.create') }}" class="btn btn-lg button-red"><span class="fa fa-plus"></span> @lang('events/title.add_event')</a>
                                  @endif

                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr class="ml-3 mr-3">

                    <div class="form-group">
                        <div class="col-12 row">

                            <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                                <label for="input">@lang('events/form.text_search') : </label>
                                <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                                <label for="input">@lang('events/form.event_status_search') : </label>
                                <select class="form-control required" name="event_status_search" id="event_status_search">
                                    <option value="">@lang('events/form.select_status')</option>
                                    <option value="1">@lang('events/form.on')</option>
                                    <option value="0">@lang('events/form.off')</option>
                                </select>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3  col-lg-3" style="bottom: -20px; padding-left: 0px;">
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
                                <th>@lang('events/table.event_name')</th>
                                <th>@lang('events/table.start_date')</th>
                                <th>@lang('events/table.end_date')</th>
                                <th>@lang('events/table.dlr')</th>
                                <th>@lang('events/table.sale')</th>
                                <th>@lang('events/table.event_status')</th>
                                <th>@lang('events/table.actions')</th>
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

<script>
    $(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.events.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   event_status_search: $('#event_status_search').val(),
                   data_status_search: $('#data_status_search').val()
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
                { data: 'name', name: 'name' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                { data: 'dlr', name: 'dlr' },
                { data: 'sale', name: 'sale' },
                { data: 'event_status', name: 'event_status'},
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
                    'url': '{!! route('admin.events.postdata') !!}',
                    'data': {
                       text_search: $('#text_search').val(),
                       event_status_search: $('#event_status_search').val(),
                       data_status_search: $('#data_status_search').val()
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
                    { data: 'name', name: 'name' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'sale', name: 'sale' },
                    { data: 'event_status', name: 'event_status'},
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                searching: false
            });
        //}
    });

    $("#event_status_search").change(function(){
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.events.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   event_status_search: $('#event_status_search').val(),
                   data_status_search: $('#data_status_search').val()
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
                { data: 'name', name: 'name' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                { data: 'dlr', name: 'dlr' },
                { data: 'sale', name: 'sale' },
                { data: 'event_status', name: 'event_status'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            searching: false
        });
    });

    $('#reset_button').click(function() {
        //clear search form
        $('#text_search').val('');
        $('#event_status_search').val('');

        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.events.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   event_status_search: $('#event_status_search').val()
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
                { data: 'name', name: 'name' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                { data: 'dlr', name: 'dlr' },
                { data: 'sale', name: 'sale' },
                { data: 'event_status', name: 'event_status'},
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            searching: false
        });
    });

</script>

    <!--
    <div class="modal fade" id="delete_confirm" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('events/modal.title')</h4>
                </div>
                <div class="modal-body">
                    @lang('events/modal.body')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('events/modal.cancel')</button>
                    <a href="#" id="del_event" class="btn btn-danger">@lang('events/modal.delete')</a>
                </div>
            </div>
        </div>
    </div>
    -->

<script>
    /*
    $(function () {
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });
    });

    $(document).on('click','.deleteEvent',function(){
        var event_id = $(this).attr('data-eventid');

        var obj = document.getElementById('del_event');
        obj.setAttribute('href', window.location.href+'/'+event_id+'/delete');

        $('#delete_confirm').modal('show');
    });
    */

</script>

@stop
