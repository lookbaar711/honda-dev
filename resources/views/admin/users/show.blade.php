@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    View Administrator Detail
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/vendors/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet"/>

    <link href="{{ asset('assets/css/pages/user_profile.css') }}" rel="stylesheet"/>
@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>Administrator Profile</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ URL::to('admin/users') }}">Administrators</a>
            </li>
            <li class="active">Administrator Profile</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content user_profile">
        <div class="row">
            <div class="col-lg-12">

                <div class="card panel-primary">
                    <div class="card-heading">
                        <h3 class="card-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Administrator Profile
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <!--
                                <div class="img-file">
                                    @if($user->pic)
                                        <img src="{!! url('/').'/uploads/users/'.$user->pic !!}" alt="img" class="img-fluid"/>
                                    @elseif($user->gender === "male")
                                        <img src="{{ asset('assets/images/authors/avatar3.png') }}" alt="..." class="img-fluid"/>
                                    @elseif($user->gender === "female")
                                        <img src="{{ asset('assets/images/authors/avatar5.png') }}" alt="..." class="img-fluid"/>
                                    @else
                                        <img src="{{ asset('assets/images/authors/no_avatar.jpg') }}" alt="..." class="img-fluid"/>
                                    @endif
                                </div>
                                -->

                                <div class="table-responsive-lg table-responsive-sm table-responsive-md table-responsive">
                                    <table class="table table-bordered table-striped" id="users">
                                        
                                        <tr>
                                            <td>Username</td>
                                            <td>
                                                {{ $user->email }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Full Name</td>
                                            <td>
                                                <p class="user_name_max">{{ $user->first_name.' '.$user->last_name }}</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ID Card Number</td>
                                            <td>
                                                {{ $user->id_card_no }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Birth Date</td>

                                            @if($user->dob=='0000-00-00')
                                                <td>
                                                </td>
                                            @else
                                                <td>
                                                    {{ $user->dob }}
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Phone Info</td>
                                            <td>
                                                {{ $user->phone_info }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>E-Mail Info</td>
                                            <td>
                                                {{ $user->email_info }}
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Status</td>
                                            <td>

                                                @if($user->deleted_at)
                                                    Deleted
                                                @else
                                                    Activated
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Created At</td>
                                            <td>
                                                {!! $user->created_at->diffForHumans() !!}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <a href="{{ URL::to('admin/users') }}" class="btn btn-sm btn-warning"><span class="fa fa-arrow-left"></span> @lang('Back')</a>
                    </div>
                </div>

                <br>
            </div>
        </div>
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop
