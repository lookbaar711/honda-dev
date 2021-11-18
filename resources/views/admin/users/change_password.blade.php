@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('users/title.change_password')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">
    <!--end of page level css-->

@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL::to('admin/users') }}">@lang('users/title.users_list')</a>
            </li>
            <li class="active">@lang('users/title.change_password')</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 my-3">
                <div class="card panel-primary">
                    
                    <div class="card-body">
                        <h3><i class="livicon" data-name="tags" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>@lang('users/title.change_password')
                            <div class="form-group" align="right">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary" id="change_password">
                                            @lang('users/form.save_button')
                                        </button>

                                        <a class="btn btn-danger" href="{{ route('admin.users.index') }}">
                                            @lang('users/form.cancel_button')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </h3>
                        <hr class="ml-3 mr-3"><br>

                        <!--main content-->
                        {!! Form::model($user, ['url' => URL::to('admin/users/'. $user->id.'/change-password'), 'method' => 'post', 'class' => 'form-horizontal','id'=>'commentForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                        {{ csrf_field() }}
                        <!-- CSRF Token -->

                            <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                <div class="row">
                                    <label for="email" class="col-sm-2 control-label">@lang('users/form.username') : </label>
                                    <div class="col-sm-10">
                                        <input id="email" name="email" placeholder="administrator@email.com" type="text" class="form-control" value="{!! old('email', $user->email) !!}" disabled />
                                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                <div class="row">
                                    <label for="password" class="col-sm-2 control-label">@lang('users/form.password') : </label>
                                    <div class="col-sm-10">
                                        <input id="password" name="password" type="password" placeholder="Password"
                                               class="form-control required" value="{!! old('password') !!}"/>
                                        {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                                <div class="row">
                                    <label for="password_confirm" class="col-sm-2 control-label">@lang('users/form.confirm_password') : </label>
                                    <div class="col-sm-10">
                                        <input id="password_confirm" name="password_confirm" type="password"
                                               placeholder="Confirm Password " class="form-control required"/>
                                        {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>  
                            <input id="activate" name="activate" type="hidden" value="1">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" ></script>
    <script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/changepassword.js') }}"></script>
@stop
