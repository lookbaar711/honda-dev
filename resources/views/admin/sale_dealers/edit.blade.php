@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('sale_dealers/title.edit_sale_dealer')
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
                <a href="{{ URL::to('admin/sale_dealers') }}">@lang('sale_dealers/title.sale_dealer_list')</a>
            </li>
            <li class="active">@lang('sale_dealers/title.edit_sale_dealer')</li>
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
                        <a class="fs-20" href="{{ URL::to('admin/events/'.$event_id.'/sale_dealers') }}">@lang('sale_dealers/title.sale_dealers_list')</a>
                    </td>
                    <td class="p-t-20">
                        <a class="fs-20" style="color: #7b7e82;">/</a>
                    </td>
                    <td class="p-t-20">
                        <a class="active fs-20" style="color: #7b7e82;">@lang('sale_dealers/title.edit_sale_dealer')</a>
                    </td>
                </tr>
            </table>

            <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
                <div class="card panel-primary">
                    
                    <div class="card-body">
                        <h3><i class="livicon" data-name="tags" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>@lang('sale_dealers/title.edit_sale_dealer')
                            <div class="form-group" align="right">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary" id="edit_sale_dealer">
                                            @lang('sale_dealers/form.save_button')
                                        </button>

                                        <a class="btn btn-danger" href="{{  URL::to('admin/events/'.$event_id.'/sale_dealers') }}">
                                            @lang('sale_dealers/form.cancel_button')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </h3>
                        <hr class="ml-3 mr-3"><br>

                        <!--main content-->
                        {!! Form::model($sale_dealer, ['url' => URL::to('admin/sale_dealers/'. $sale_dealer->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'commentForm']) !!}
                        {{ csrf_field() }}
                        <!-- CSRF Token -->

                            <div class="form-group {{ $errors->first('sale_dealer_code', 'has-error') }}">
                                <div class="row">
                                    <label for="sale_dealer_code" class="col-sm-2 control-label">@lang('sale_dealers/form.sale_dealer_code') : </label>
                                    <div class="col-sm-10">
                                        <input id="sale_dealer_code" name="sale_dealer_code" type="text" placeholder="" class="form-control required" value="{!! old('sale_dealer_code', $sale_dealer->sale_dealer_code) !!}" disabled />

                                        {!! $errors->first('sale_dealer_code', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('sale_dealer_name', 'has-error') }}">
                                <div class="row">
                                    <label for="sale_dealer_name" class="col-sm-2 control-label">@lang('sale_dealers/form.sale_dealer_name') : </label>
                                    <div class="col-sm-10">
                                        <input id="sale_dealer_name" name="sale_dealer_name" type="text" placeholder="" class="form-control" value="{!! old('sale_dealer_name', $sale_dealer->sale_dealer_name) !!}" />
                                        {!! $errors->first('sale_dealer_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('sale_dealer_nickname', 'has-error') }}">
                                <div class="row">
                                    <label for="sale_dealer_nickname" class="col-sm-2 control-label">@lang('sale_dealers/form.sale_dealer_nickname') : </label>
                                    <div class="col-sm-10">
                                        <input id="sale_dealer_nickname" name="sale_dealer_nickname" placeholder="" type="text" class="form-control required" value="{!! old('sale_dealer_nickname', $sale_dealer->sale_dealer_nickname) !!}"/>
                                        {!! $errors->first('sale_dealer_nickname', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('sale_dealer_tel', 'has-error') }}">
                                <div class="row">
                                    <label for="sale_dealer_tel" class="col-sm-2 control-label">@lang('sale_dealers/form.sale_dealer_tel') : </label>
                                    <div class="col-sm-10">
                                        <input id="sale_dealer_tel" name="sale_dealer_tel" placeholder="" type="text" class="form-control required" value="{!! old('sale_dealer_tel', $sale_dealer->sale_dealer_tel) !!}"/>
                                        {!! $errors->first('sale_dealer_tel', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_ids_code', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_ids_code" class="col-sm-2 control-label">@lang('sale_dealers/form.dealer_ids_code') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_ids_code" name="dealer_ids_code" placeholder="" type="text" class="form-control required" value="{!! old('dealer_ids_code', $sale_dealer->dealer_ids_code) !!}" disabled/>
                                        {!! $errors->first('dealer_ids_code', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_legacy_code', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_legacy_code" class="col-sm-2 control-label">@lang('sale_dealers/form.dealer_legacy_code') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_legacy_code" name="dealer_legacy_code" placeholder="" type="text" class="form-control required" value="{!! old('dealer_legacy_code', $sale_dealer->dealer_legacy_code) !!}" disabled/>
                                        {!! $errors->first('dealer_legacy_code', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group {{ $errors->first('dealer_zone', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_zone" class="col-sm-2 control-label">@lang('sale_dealers/form.dealer_zone') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_zone" name="dealer_zone" placeholder="" type="text" class="form-control required" value="{!! old('dealer_zone', $sale_dealer->dealer_zone) !!}" disabled/>
                                        {!! $errors->first('dealer_zone', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_area', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_area" class="col-sm-2 control-label">@lang('sale_dealers/form.dealer_area') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_area" name="dealer_area" placeholder="" type="text" class="form-control required" value="{!! old('dealer_area', $sale_dealer->dealer_area) !!}" disabled/>
                                        {!! $errors->first('dealer_area', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_dlr', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_dlr" class="col-sm-2 control-label">@lang('sale_dealers/form.dealer_dlr') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_dlr" name="dealer_dlr" placeholder="" type="text" class="form-control required" value="{!! old('dealer_dlr', $sale_dealer->dealer_dlr) !!}" disabled/>
                                        {!! $errors->first('dealer_dlr', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_name', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_name" class="col-sm-2 control-label">@lang('sale_dealers/form.dealer_name') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_name" name="dealer_name" placeholder="" type="text" class="form-control required" value="{!! old('dealer_name', $sale_dealer->dealer_name) !!}" disabled/>
                                        {!! $errors->first('dealer_name', '<span class="help-block">:message</span>') !!}
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

    <script src="{{ asset('assets/js/pages/editsaledealer.js') }}"></script>
@stop
