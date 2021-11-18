<!DOCTYPE html>

<?php
    header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
?>

<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Honda Sale Consultant Registration Event
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- global css -->

    <!-- font kanit -->
    <link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">

    {{-- <link href="{{ asset('assets/vendors/sweetalert/css/sweetalert.css') }}" rel="stylesheet" type="text/css"/> --}}
    <link href="{{ asset('/css/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css"/>

    <style type="text/css">
    .dataTable > thead > tr > th[class*="sort"]:after{
        content: "" !important;
    }

    .dataTable > thead > tr > th[class*="sort"]:before{
        content: "" !important;
    }
    </style>


    <!-- font Awesome -->

    <!-- end of global css -->
    <!--page level css-->
    @yield('header_styles')
            <!--end of page level css-->

<body class="skin-josh">
<header class="header">
    <!-- <a href="{{ route('admin.dashboard') }}" class="logo">
        <img src="{{ asset('assets/img/suksa_logo_top.png') }}" alt="logo">
    </a> -->
    <a href="{{ route('admin.dashboard') }}" class="logo" style="padding-top: 20px;">
        <img src="{{ asset('assets/img/logo_honda.png') }}" alt="logo" style="width: 150px;">
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
           <!--  <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a> -->
        </div>
        <div class="navbar-right toggle">
            <ul class="nav navbar-nav  list-inline">

                <li class=" nav-item dropdown user user-menu">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <i class="livicon" data-name="user" data-s="18"></i>

                        <div class="riot">
                            <div>
                                <p class="user_name_max">{{ Sentinel::getUser()->full_name }}</p>
                                <span><i class="caret"></i></span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <!-- <a href="{{ URL::route('admin.users.change',Sentinel::getUser()->id) }}" >
                                <i class="livicon" data-name="lock" data-s="18"></i>
                                เปลี่ยนรหัสผ่าน
                            </a> -->
                            <a href="" data-toggle="modal" data-target="#chang_password_confirm" >
                                <i class="livicon" data-name="lock" data-s="18"></i>
                                เปลี่ยนรหัสผ่าน
                            </a>

                        </li>
                        <li role="presentation"></li>
                        <li>
                            <a href="{{ URL::to('admin/logout') }}">
                                <i class="livicon" data-name="sign-out" data-s="18"></i>
                                ออกจากระบบ
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </nav>
</header>
<div class="wrapper ">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side ">
        <section class="sidebar ">
            <div class="page-sidebar  sidebar-nav">

                <div class="date-header">
                    {{ date("D d-m-Y H:i") }}
                </div>
                <!-- <div class="clearfix"></div> -->
                <!-- BEGIN SIDEBAR MENU -->
                @include('admin.layouts._left_menu')
                <!-- END SIDEBAR MENU -->
            </div>
        </section>
    </aside>
    <aside class="right-side">

        <!-- Notifications -->
        <div id="notific">
        @include('notifications')
        </div>

                <!-- Content -->
        @yield('content')

    </aside>
    <!-- right-side -->
</div>
<!-- <a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top"
   data-toggle="tooltip" data-placement="left">
    <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
</a> -->

<!-- Modal Edit Dealer -->
<div class="modal fade" id="chang_password_confirm" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">เปลี่ยนรหัสผ่าน</h3>
                <button type="button" class="close clear_form" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="change_password_form" action="{{ URL::to('admin/users/'.Sentinel::getUser()->id.'/change-password') }}" method="POST">
                    {{ csrf_field() }}

                    <table border="0">
                        <tr>
                            <td>
                                <div class="col-12 row p-b-10">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            รหัสผ่านปัจจุบัน : </label>
                                            <input class="form-control col-sm-12" id="password" name="password" placeholder="กรอกรหัสผ่านปัจจุบัน" type="password" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            ตั้งรหัสผ่านใหม่ : </label>
                                            <input class="form-control col-sm-12" id="new_password" name="new_password" placeholder="กรอกรหัสผ่านใหม่" type="password" value="">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="p-b-5">
                                            ยืนยันรหัสผ่าน : </label>
                                            <input class="form-control col-sm-12 required" id="confirm_password" name="confirm_password" placeholder="กรอกรหัสผ่านใหม่อีกครั้ง" type="password" value="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#" id="change_password" class="btn btn-lg button-blue">ยืนยันการเปลี่ยนรหัสผ่าน</a>
                <button type="button" class="btn btn-lg button-red clear_form" data-dismiss="modal">@lang('dealers/form.cancel_button')</button>
            </div>
        </div>
    </div>
</div>

<!-- global js -->
<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/sweetalert2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('/js/main/check_running.js') }}" type="text/javascript"></script>
{{-- select2.min.js --}}
<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/changepassword.js') }}"></script>




@if(session('response_success') || session('response_warning') || session('response_error') || session('response_info'))

<script type="text/javascript">
    @if(session('response_success'))
        Swal.fire({
            type: 'success',
            title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">{{ session('response_success') }} </p>',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonColor: '#369DE2',
            cancelButtonText: 'ปิดหน้าต่าง'
        });

    @elseif(session('response_warning'))
        Swal.fire({
            type: 'warning',
            title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">{{ session('response_warning') }}</p>',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonColor: '#369DE2',
            cancelButtonText: 'ปิดหน้าต่าง'
        });
    @elseif(session('response_error'))
        Swal.fire({
            type: 'error',
            title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">{{ session('response_error') }}</p>',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonColor: '#369DE2',
            cancelButtonText: 'ปิดหน้าต่าง'
        });
    @elseif(session('response_info'))
        Swal.fire({
            type: 'info',
            title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">{{ session('response_info') }}</p>',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonColor: '#369DE2',
            cancelButtonText: 'ปิดหน้าต่าง'
        });
    @endif
</script>

@endif

<script type="text/javascript">
    $(function () {
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
        });
    });



    //reset error message on close or cancel modal
    $(document).on('click','.clear_form',function(){
        $('#change_password_form').bootstrapValidator('resetForm', true);
    });

    //reset error message on hide modal
    $('#chang_password_confirm').on('hide.bs.modal', function () {
        $('#change_password_form').bootstrapValidator('resetForm', true);
    });
</script>

<!-- end of global js -->
<!-- begin page level js -->
@yield('footer_scripts')
        <!-- end page level js -->
</body>
</html>
