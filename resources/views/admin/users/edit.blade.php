@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('users/title.edit_user')
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
    <section class="content">
        <div class="row">
             <table border="0">
                <tr>
                    <td class="p-l-40 p-t-20">
                        <a class="fs-20" href="{{ URL::to('admin/users') }}">@lang('users/title.users_list')</a>
                    </td>
                    <td class="p-t-20">
                        <a class="fs-20" style="color: #7b7e82;">/</a>
                    </td>
                     <td class="p-t-20">
                        <a class="active fs-20" style="color: #7b7e82;">@lang('users/title.edit_user')</a>
                    </td>
                </tr>
            </table>
            <!-- <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;"> -->
            <div class="col-12 p-t-30" style="margin-left: 25px; padding-right: 70px;">
                <div class="card panel-primary">
                    <div class="card-body">

                        <div class="card-body">
                            <table border="0" class="col-sm-12">
                                <tr>
                                    <td align="left" class="fs-26">
                                        <b>@lang('users/title.edit_user')</b>
                                    </td>
                                    <td align="right">
                                        <button type="submit" class="btn btn-lg button-blue" id="edit_user">
                                            @lang('users/form.save_button')
                                        </button>

                                       <a class="btn btn-lg button-red" href="{{ route('admin.users.index') }}">
                                            @lang('users/form.cancel_button')
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>


                        <hr class="ml-3 mr-3"><br>

                        <!--main content-->
                        {!! Form::model($user, ['url' => URL::to('admin/users/'. $user->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'commentForm', 'enctype'=>'multipart/form-data','files'=> true]) !!}
                        {{ csrf_field() }}
                            
                            <input id="activate" name="activate" type="hidden" value="1">
                            <input type="hidden" name="groups" value="1" />

                            <div class="col-12 row p-b-10">
                                <div class="col-sm-5">
                                    <div class="form-group required">
                                        <label for="deleted_at" class="p-b-5">@lang('users/form.status') : </label>
                                        <label class="radio-inline">
                                                &nbsp;<input type="radio" class="custom-radio" name="status" id="status1" value="1" {{ ($user->deleted_at === null)?'checked':'' }}>&nbsp;@lang('users/form.active')</label>
                                        <label class="radio-inline">
                                            <input type="radio" class="custom-radio" name="status" id="status2" value="0" {{ ($user->deleted_at !== null)?'checked':'' }}>&nbsp;@lang('users/form.inactive')</label>
                                    </div>
                                    <span class="help-block">{{ $errors->first('group', ':message') }}</span>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                    </div>
                                </div>
                                
                                <div class="col-sm-5">
                                    <div class="form-group required">
                                        <label class="p-b-5">@lang('users/form.group') * : </label>
                                        <select class="form-control required edit-group col-sm-10"  name="status_process" id="status_process">
                                            <option value="">@lang('users/form.select_group')</option>
                                            <option value="0" {{ ($user->status_process == 0)?'selected':'' }}>Super Admin</option>
                                            <option value="1" {{ ($user->status_process == 1)?'selected':'' }}>Operation</option>
                                        </select>
                                        {!! $errors->first('group', '<span class="help-block">:message</span>') !!}
                                    </div>
                                    <span class="help-block">{{ $errors->first('group', ':message') }}</span>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group {{ $errors->first('full_name', 'has-error') }}">
                                        <label for="full_name" class="p-b-5">@lang('users/form.full_name') * : </label>
                                        <input id="full_name" name="full_name" type="text" placeholder="ชื่อ - นามสกุล" class="form-control col-sm-10 required"value="{!! old('full_name', $user->full_name) !!}">
                                       {!! $errors->first('full_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 row p-b-10">
                                <div class="col-sm-5">
                                    <div class="form-group {{ $errors->first('email_info', 'has-error') }}">
                                        <label class="p-b-5">@lang('users/form.email_info') : </label>
                                        <input id="email_info" name="email_info" placeholder="กรอกอีเมล" type="email" class="form-control col-sm-10 email" value="{!! old('email_info', $user->email_info) !!}">
                                        {!! $errors->first('email_info', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group {{ $errors->first('tel_info', 'has-error') }}">
                                        <label class="p-b-5">@lang('users/form.tel_info') : </label>
                                        <input class="form-control col-sm-10 required"  id="tel_info" name="tel_info" placeholder="กรอกตัวเลขเท่านั้น" type="text" value="{!! old('tel_info', $user->tel_info) !!}">
                                        {!! $errors->first('tel_info', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                 <div class="col-12 fs-18 bg-gray p-t-5 p-b-5">
                                    การเข้าสู่ระบบ
                                </div>
                            </div>

                            <div class="col-12 row p-b-80 p-t-15">
                                <div class="col-sm-5">
                                    <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                        <label for="email" class="p-b-5">@lang('users/form.username') * : </label>
                                        <input class="form-control col-sm-10 email" id="email_info" name="email_info" placeholder="กรอกบัญชีผู้ใช้งาน" type="email" value="{!! old('email', $user->email)!!}" disabled>
                                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                    </div>
                                    <p>*หมายเหตุ ภาษาอังกฤษ หรือตัวเลขเท่านั้น</p>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group {{ $errors->first('password', 'has-error') }}">
                                        <label class="p-b-5">@lang('users/form.password') * : </label>
                                        <input class="form-control col-sm-10 required" id="password" name="password" type="password" placeholder="" value="{!! old('password') !!}" disabled>
                                        {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>

                            
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
    <script src="{{ asset('assets/js/pages/edituser.js') }}"></script>
@stop
