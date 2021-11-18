@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
@lang('groups/title.management')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>@lang('groups/title.management')</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li><a href="#"> @lang('groups/title.groups')</a></li>
        <li class="active">@lang('groups/title.groups_list')</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card panel-primary ">
                <div class="card-heading">
                    <h4 class="card-title float-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Groups
                    </h4>
                </div>

                <div class="card-body">
                    <a href="{{ route('admin.groups.create') }}" class="btn btn-primary ml-3 mb-3"><span class="fa fa-plus"></span> @lang('button.create')</a>

                    @if ($roles->count() >= 1)
                        <div class="table-responsive-lg table-responsive-md table-responsive-sm table-responsive ">

                        <table class="table table-bordered width100" id="table">
                            <thead>
                                <tr>
                                    <th>@lang('groups/table.id')</th>
                                    <th>@lang('groups/table.name')</th>
                                    <th>@lang('groups/table.users')</th>
                                    <th>@lang('groups/table.created_at')</th>
                                    <th>@lang('groups/table.actions')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($roles as $role)
                                <tr>
                                    <td>{!! $role->id !!}</td>
                                    <td>{!! $role->name !!}</td>
                                    <td>{!! $role->users()->count() !!}</td>
                                    <td>{!! $role->created_at->diffForHumans() !!}</td>
                                    <td>

                                        <!--
                                        <button type="button" class="btn btn-responsive btn-info btn-sm fa fa-info-circle" data-toggle="button">Edit</button>

                                        <button type="button" class="btn btn-responsive btn-primary btn-sm fa fa-edit" data-toggle="button">Edit</button>

                                        <button type="button" class="btn btn-responsive btn-danger btn-sm fa fa-trash-o" data-toggle="button">Delete</button>
                                        
                                        <button type="button" class="btn btn-responsive btn-danger btn-sm fa fa-ban" data-toggle="button"></button>
                                        
                                        <a href="#" class="btn btn-responsive button-alignment btn-info btn-sm fa fa-info-circle mr-2" title="edit group"></a>
                                        -->

                                        <!--
                                        <a href="{{ route('admin.groups.edit', $role->id) }}" class="btn btn-responsive button-alignment btn-primary btn-sm fa fa-edit mr-2" title="edit group"></a>
                                        -->

                                        <a href="{{ route('admin.groups.edit', $role->id) }}">
                                            <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="edit group"></i>
                                        </a>

                                            <!-- let's not delete 'Admin' group by accident -->
                                            @if ($role->id !== 1)
                                                @if($role->users()->count())
                                                    <!--
                                                    <a href="#" class="btn btn-responsive button-alignment btn-secondary btn-sm fa fa-ban mr-2" title="users_exists"></a>
                                                    -->

                                                    <a href="#">
                                                        <i class="livicon" data-name="warning-alt" data-size="18"
                                                           data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                           title="@lang('groups/form.users_exists')"></i>
                                                    </a>
                                                @else
                                                    <!--
                                                    <a href="{{ route('admin.groups.confirm-delete', $role->id) }}" data-toggle="modal" data-id ="{{ $role->id }}" data-target="#delete_confirm" class="btn btn-responsive button-alignment btn-danger btn-sm fa fa-trash-o mr-2" title="users_exists"></a>
                                                    -->

                                                    <a href="{{ route('admin.groups.confirm-delete', $role->id) }}" data-toggle="modal" data-id ="{{ $role->id }}" data-target="#delete_confirm">
                                                        <i class="livicon" data-name="remove-alt" data-size="18"
                                                           data-loop="true" data-c="#f56954" data-hc="#f56954"
                                                           title="@lang('groups/form.delete_group')"></i>
                                                    </a>
                                                @endif

                                            @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        @lang('general.noresults')
                    @endif   
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>




@stop

{{-- Body Bottom confirm modal --}}
@section('footer_scripts')


<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>


<div class="modal fade" id="users_exists" tabindex="-2" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                @lang('groups/message.users_exists')
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteLabel">Delete Group</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                Are you sure to delete this Group? This operation is irreversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a  type="button" class="btn btn-danger Remove_square">Delete</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<script>
    $(document).ready( function () {
        $('#table').DataTable();
    });

    var $url_path = '{!! url('/admin/') !!}';
    $('#delete_confirm').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var $recipient = button.data('id');
        var modal = $(this)
        modal.find('.modal-footer a').prop("href",$url_path+"/groups/"+$recipient+"/delete");
    });
    
</script>
@stop
