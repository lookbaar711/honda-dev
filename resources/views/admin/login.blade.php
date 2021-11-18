<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- global level css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
    <!-- end of global level css -->
    <!-- page level css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/pages/login.css') }}" />
    <link href="{{ asset('assets/vendors/iCheck/css/square/blue.css') }}" rel="stylesheet"/>
    <!-- end of page level css -->

    <!-- font Kanit -->
    <link href="https://fonts.googleapis.com/css?family=Kanit&display=swap" rel="stylesheet">

</head>

<body>

    <!-- <div class="row">
        <div class="col-sm-4">
            <div id="container_demo">
                <a class="hiddenanchor" id="tologin"></a>
                <div id="wrapper">
                    <div id="login" class="animate form">
                        <form class="inline-block" action="{{ route('signin') }}" autocomplete="on" method="post" role="form" id="login_form" class="my-3">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group col-sm-4 {{ $errors->first('email', 'has-error') }}">
                                <input id="email" name="email" type="text" placeholder="Username" value="{!! old('email') !!}"/>
                                <div class="col-sm-12">
                                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group col-sm-4 {{ $errors->first('password', 'has-error') }}">
                                <input id="password" name="password" type="password" placeholder="Enter a password" />
                                <div class="col-sm-12">
                                    {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <button type="submit" class="btn btn-danger col-sm-12">
                                    เข้าสู่ระบบ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="bg-img">
      <form class="container" action="{{ route('signin') }}" autocomplete="on" method="post" role="form" id="login_form" style="">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
         <div align="center">
             <img src="{{ asset ('assets/img/logohonda.png') }}" style="width: 250px; height: 250px;">
         </div>
            <div class="form-group col-sm-14 {{ $errors->first('email', 'has-error') }}">
                <input class="form-control" id="email" name="email" type="text" placeholder="บัญชีผู้ใช้งาน" value="{!! old('email') !!}"/>
                {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-sm-14 {{ $errors->first('password', 'has-error') }}">
                <input class="form-control" id="password" name="password" type="password" placeholder="รหัสผ่าน" />
                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group col-sm-14">
                <button type="submit" class="btn btn-danger col-sm-12" class="">
                    เข้าสู่ระบบ
                </button>
            </div>
            <div style="padding-top: 40px;">
             <img src="{{ asset ('assets/img/img_login.png') }}" style="margin-left: -8px;">
         </div>
        </form>
    </div>

           
    <!-- global js -->
    <script src="{{ asset('assets/js/jquery.min.js') }}" type="text/javascript"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/frontend/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <!--livicons-->
    <script src="{{ asset('assets/js/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/js/livicons-1.4.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/login.js') }}" type="text/javascript"></script>
    <!-- end of global js -->
</body>
</html>