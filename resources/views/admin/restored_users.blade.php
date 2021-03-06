@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Restored users
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
    <!-- end page css -->
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
                <h1>Restored users</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                            Dashboard
                        </a>
                    </li>
                    <li><a href="#"> Users</a></li>
                    <li class="active">Restored users</li>
                </ol>
            </section>
            <!-- Main content -->
         <section class="content paddingleft_right15">
    <div class="row">
        <div class="col-12">
        <div class="card panel-warning">
            <div class="card-heading">
                <h4 class="card-title">
                    <i class="livicon" data-name="users-remove" data-size="18" data-c="#ffffff" data-hc="#ffffff"></i>
                    Restored Users List
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr class="filters">
                        <th>Name</th>
                        <th>User E-mail</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{!! $user->full_name !!}</td>
                            <td>{!! $user->email !!}</td>
                            <td>{!! $user->created_at->diffForHumans() !!}</td>
                            <td>
                                <a href="{{ route('admin.delete.user', $user->id) }}"><i class="livicon" data-name="user-flag" data-c="#6CC66C" data-hc="#6CC66C" data-size="18"></i></a>
                            </td>
                        </tr>
                    @endforeach

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
@section("footer_scripts")
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@stop
