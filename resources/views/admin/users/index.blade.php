@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('users/title.users_list')
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
<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="col-12 p-t-40" style="margin-left: 25px; padding-right: 70px;">
            <div class="card panel-primary ">
                <div class="card-body">

                    <div class="card-body">
                        <table border="0">
                            <tr>
                                <td align="left" class="fs-26">
                                   <b>@lang('users/title.users_list')</b>
                                </td>
                                <td align="right">
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-lg button-red"><span class="fa fa-plus"></span> @lang('users/title.add_user')</a>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <hr class="ml-3 mr-3">

                    <div class="form-group">
                        <div class="col-12 row">

                            <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                                <label for="input">@lang('users/form.text_search') : </label>
                                <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                                <label for="input">@lang('users/form.group_search') : </label>
                                <select class="form-control required" name="group_search" id="group_search">
                                    <option value="">@lang('users/form.select_all')</option>
                                    <option value="0">Super Admin</option>
                                    <option value="1">Operation</option>
                                    {{-- @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name}}</option>
                                    @endforeach --}}


                                </select>
                            </div>
                            <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                                <label for="input">@lang('users/form.status_search') : </label>
                                <select class="form-control required" id="status_search">
                                    <option value="">@lang('users/form.select_all')</option>
                                    <option value="1">@lang('users/form.active')</option>
                                    <option value="0">@lang('users/form.inactive')</option>
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
                                    <th>@lang('users/table.group')</th>
                                    <th>@lang('users/table.username')</th>
                                    <th>@lang('users/table.fullname')</th>
                                    <th>@lang('users/table.status')</th>
                                    <th>@lang('users/table.actions')</th>
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
            //ajax: '{!! route('admin.users.data') !!}',

            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.users.postdata') !!}',
                'data': {
                   text_search: '',
                   group_search: '',
                   status_search: ''
                },
            },
            columns: [
                // {
                //     data: null,
                //     sortable: false,
                //     render: function (data, type, row, meta) {
                //         return meta.row + meta.settings._iDisplayStart + 1;
                //     }
                // },

                {
                    title : '#',
                    data: (a,b,c,d,e) => {
                        var total_row = (table.page.info().start+d.row+1);
                        return total_row;
                    },
                    name: 'id'
                },
                { data: 'group_name', name: 'group_name'},
                { data: 'email', name: 'email' },
                { data: 'full_name', name: 'full_name' },
                { data: 'status', name: 'status'},
                { data: 'actions', name: 'actions' }
            ],
            searching: false
        });
    });

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
                    'url': '{!! route('admin.users.postdata') !!}',
                    'data': {
                       text_search: $('#text_search').val(),
                       group_search: $('#group_search').val(),
                       status_search: $('#status_search').val()
                    },
                },
                columns: [
                    {
                        title : '#',
                        data: (a,b,c,d,e) => {
                            var total_row = (table.page.info().start+d.row+1);
                            return total_row;
                        },
                        name: 'id'
                    },
                    { data: 'group_name', name: 'group_name'},
                    { data: 'email', name: 'email' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'status', name: 'status'},
                    { data: 'actions', name: 'actions' }
                ],
                searching: false
            });
        //}
    });

    $("#group_search").change(function(){
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.users.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   group_search: $('#group_search').val(),
                   status_search: $('#status_search').val()
                },
            },
            columns: [
                {
                    title : '#',
                    data: (a,b,c,d,e) => {
                        var total_row = (table.page.info().start+d.row+1);
                        return total_row;
                    },
                    name: 'id'
                },
                { data: 'group_name', name: 'group_name'},
                { data: 'email', name: 'email' },
                { data: 'full_name', name: 'full_name' },
                { data: 'status', name: 'status'},
                { data: 'actions', name: 'actions' }
            ],
            searching: false
        });
    });

    $("#status_search").change(function(){
        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.users.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   group_search: $('#group_search').val(),
                   status_search: $('#status_search').val()
                },
            },
            columns: [
                {
                    title : '#',
                    data: (a,b,c,d,e) => {
                        var total_row = (table.page.info().start+d.row+1);
                        return total_row;
                    },
                    name: 'id'
                },
                { data: 'group_name', name: 'group_name'},
                { data: 'email', name: 'email' },
                { data: 'full_name', name: 'full_name' },
                { data: 'status', name: 'status'},
                { data: 'actions', name: 'actions' }
            ],
            searching: false
        });
    });



    $('#reset_button').click(function() {

        //clear search form
        $('#text_search').val('');
        $('#group_search').val('');
        $('#status_search').val('');

        //destroy first initailize
        $('#table').dataTable().fnDestroy();

        //reinitailize
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'url': '{!! route('admin.users.postdata') !!}',
                'data': {
                   text_search: $('#text_search').val(),
                   group_search: $('#group_search').val(),
                   status_search: $('#status_search').val()
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
                { data: 'group_name', name: 'group_name'},
                { data: 'email', name: 'email' },
                { data: 'full_name', name: 'full_name' },
                { data: 'status', name: 'status'},
                { data: 'actions', name: 'actions' }
            ],
            searching: false
        });
    });

</script>

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteLabel">@lang('users/modal.title_delete')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @lang('users/modal.body_delete')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('users/modal.cancel_button')</button>
                    <a  type="button" class="btn btn-danger Remove_square">@lang('users/modal.delete_button')</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>


    <div class="modal fade" id="restore_confirm" tabindex="-1" role="dialog" aria-labelledby="restoreLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="restoreLabel">@lang('users/modal.title_restore')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    @lang('users/modal.title_restore')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('users/modal.cancel_button')</button>
                    <a  type="button" class="btn btn-success Remove_square">@lang('users/modal.restore_button')</a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- /.modal-dialog -->
<script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
var $url_path = '{!! url('/') !!}';
$('#delete_confirm').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var $recipient = button.data('id');
    var modal = $(this)
    modal.find('.modal-footer a').prop("href",$url_path+"/admin/users/"+$recipient+"/delete");
});

$('#restore_confirm').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var $recipient = button.data('id');
    var modal = $(this)
    modal.find('.modal-footer a').prop("href",$url_path+"/admin/users/"+$recipient+"/restore");
});
</script>
@stop
