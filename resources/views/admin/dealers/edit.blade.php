@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('dealers/title.edit_dealer')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/vendors/daterangepicker/css/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <!--end of page level css-->

@stop


{{-- Page content --}}
@section('content')
<!--
    <section class="content-header">
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL::to('admin/dealers') }}">@lang('dealers/title.dealers_list')</a>
            </li>
            <li class="active">@lang('dealers/title.edit_dealer')</li>
        </ol>
    </section>
-->
    <section class="content">
        <div class="row">
            <table border="0">
                <tr>
                    <td class="p-l-40 p-t-20">
                        <a class="fs-20" href="{{ URL::to('admin/events') }}">@lang('events/title.events_list')</a>
                    </td>
                    <td class="p-t-20">
                        <a class="fs-20" style="color: #7b7e82;">/</a>
                    </td>
                    <td class="p-t-20">
                        <a class="fs-20" href="{{ URL::to('admin/events/'.$event_id.'/dealers') }}">@lang('dealers/title.dealers_list')</a>
                    </td>
                    <td class="p-t-20">
                        <a class="fs-20" style="color: #7b7e82;">/</a>
                    </td>
                    <td class="p-t-20">
                        <a class="active fs-20" style="color: #7b7e82;">@lang('dealers/title.edit_dealer')</a>
                    </td>
                </tr>
            </table>

            <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
                <div class="card panel-primary">
                    
                    <div class="card-body">
                        <h3><i class="livicon" data-name="tags" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>@lang('dealers/title.edit_dealer')
                            <div class="form-group" align="right">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary" id="edit_dealer">
                                            @lang('dealers/form.save_button')
                                        </button>

                                        <a class="btn btn-danger" href="{{  URL::to('admin/events/'.$event_id.'/dealers') }}">
                                            @lang('dealers/form.cancel_button')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </h3>
                        <hr class="ml-3 mr-3"><br>

                        <!--main content-->
                        {!! Form::model($dealer, ['url' => URL::to('admin/dealers/'. $dealer->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'commentForm']) !!}
                        {{ csrf_field() }}
                        <!-- CSRF Token -->

                            <div class="form-group {{ $errors->first('dealer_legacy_code', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_legacy_code" class="col-sm-2 control-label">@lang('dealers/form.dealer_legacy_code') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_legacy_code" name="dealer_legacy_code" type="text" placeholder="" class="form-control required" value="{!! old('dealer_legacy_code', $dealer->dealer_legacy_code) !!}" disabled />

                                        {!! $errors->first('dealer_legacy_code', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_ids_code', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_ids_code" class="col-sm-2 control-label">@lang('dealers/form.dealer_ids_code') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_ids_code" name="dealer_ids_code" type="text" placeholder="" class="form-control" value="{!! old('dealer_ids_code', $dealer->dealer_ids_code) !!}" disabled />
                                        {!! $errors->first('dealer_ids_code', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_zone', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_zone" class="col-sm-2 control-label">@lang('dealers/form.dealer_zone') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_zone" name="dealer_zone" placeholder="" type="text" class="form-control required" value="{!! old('dealer_zone', $dealer->dealer_zone) !!}"/>
                                        {!! $errors->first('dealer_zone', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_area', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_area" class="col-sm-2 control-label">@lang('dealers/form.dealer_area') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_area" name="dealer_area" placeholder="" type="text" class="form-control required" value="{!! old('dealer_area', $dealer->dealer_area) !!}"/>
                                        {!! $errors->first('dealer_area', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_dlr', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_dlr" class="col-sm-2 control-label">@lang('dealers/form.dealer_dlr') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_dlr" name="dealer_dlr" placeholder="" type="text" class="form-control required" value="{!! old('dealer_dlr', $dealer->dealer_dlr) !!}"/>
                                        {!! $errors->first('dealer_dlr', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_name', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_name" class="col-sm-2 control-label">@lang('dealers/form.dealer_name') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_name" name="dealer_name" placeholder="" type="text" class="form-control required" value="{!! old('dealer_name', $dealer->dealer_name) !!}"/>
                                        {!! $errors->first('dealer_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_vip', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_vip" class="col-sm-2 control-label">@lang('dealers/form.dealer_vip') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_vip" name="dealer_vip" placeholder="" type="text" class="form-control required" value="{!! old('dealer_vip', $dealer->dealer_vip) !!}"/>
                                        {!! $errors->first('dealer_vip', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_press', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_press" class="col-sm-2 control-label">@lang('dealers/form.dealer_press') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_press" name="dealer_press" placeholder="" type="text" class="form-control required" value="{!! old('dealer_press', $dealer->dealer_press) !!}"/>
                                        {!! $errors->first('dealer_press', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_weekday', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_weekday" class="col-sm-2 control-label">@lang('dealers/form.dealer_weekday') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_weekday" name="dealer_weekday" placeholder="" type="text" class="form-control required" value="{!! old('dealer_weekday', $dealer->dealer_weekday) !!}"/>
                                        {!! $errors->first('dealer_weekday', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_weekend', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_weekend" class="col-sm-2 control-label">@lang('dealers/form.dealer_weekend') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_weekend" name="dealer_weekend" placeholder="" type="text" class="form-control required" value="{!! old('dealer_weekend', $dealer->dealer_weekend) !!}"/>
                                        {!! $errors->first('dealer_weekend', '<span class="help-block">:message</span>') !!}
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

    <script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/js/pages/editdealer.js') }}"></script>
@stop
