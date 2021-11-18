@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    @lang('dealer_details/title.edit_dealer_detail')
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('assets/vendors/select2/css/select2-bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <!--end of page level css-->

@stop


{{-- Page content --}}
@section('content')
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
                        <a class="fs-20" href="{{ URL::to('admin/events/'.$event_id.'/dealers/'.$dealer_detail->dealer_id.'/dealer_details') }}">@lang('dealer_details/title.dealer_details_list')</a>
                    </td>
                    <td class="p-t-20">
                        <a class="fs-20" style="color: #7b7e82;">/</a>
                    </td>
                    <td class="p-t-20">
                        <a class="active fs-20" style="color: #7b7e82;">@lang('dealer_details/title.edit_dealer_detail')</a>
                    </td>
                </tr>
            </table>
        
            <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
                <div class="card panel-primary">
                    
                    <div class="card-body">
                        <h3><i class="livicon" data-name="tags" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>@lang('dealer_details/title.edit_dealer_detail')
                            <div class="form-group" align="right">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn btn-primary" id="edit_dealer_detail">
                                            @lang('dealer_details/form.save_button')
                                        </button>

                                        <a class="btn btn-danger" href="{{  URL::to('admin/events/'.$event_id.'/dealers/'.$dealer_detail->dealer_id.'/dealer_details') }}">
                                            @lang('dealer_details/form.cancel_button')
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </h3>
                        <hr class="ml-3 mr-3"><br>

                        <!--main content-->
                        {!! Form::model($dealer_detail, ['url' => URL::to('admin/dealer_details/'. $dealer_detail->id.''), 'method' => 'put', 'class' => 'form-horizontal','id'=>'commentForm']) !!}
                        {{ csrf_field() }}
                        <!-- CSRF Token -->

                            <div class="form-group">
                                <div class="row">
                                    <label for="dealer_detail_dlr" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_dlr') : </label>
                                    <div class="col-sm-10">
                                        {{ $dealer->dealer_dlr }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="dealer_detail_name" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_name') : </label>
                                    <div class="col-sm-10">
                                        {{ $dealer->dealer_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="dealer_detail_zone" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_zone') : </label>
                                    <div class="col-sm-10">
                                        {{ $dealer->dealer_zone }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="dealer_detail_area" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_area') : </label>
                                    <div class="col-sm-10">
                                        {{ $dealer->dealer_area }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="dealer_detail_date" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_date') : </label>
                                    <div class="col-sm-10">
                                        {{ $dealer_detail->dealer_detail_date }}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="dealer_detail_type" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_type') : </label>
                                    <div class="col-sm-10">
                                        {{ $dealer_detail->dealer_detail_type }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('dealer_detail_amount', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_detail_amount" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_amount') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_detail_amount" name="dealer_detail_amount" placeholder="" type="text" class="form-control required" value="{!! old('dealer_detail_amount', $dealer_detail->dealer_detail_amount) !!}"/>
                                        {!! $errors->first('dealer_detail_amount', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_detail_brief_time', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_detail_brief_time" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_brief_time') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_detail_brief_time" name="dealer_detail_brief_time" placeholder="" type="text" class="form-control required" data-mask="99:99:99" value="{!! old('dealer_detail_brief_time', $dealer_detail->dealer_detail_brief_time) !!}"/>
                                        {!! $errors->first('dealer_detail_brief_time', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->first('dealer_detail_checkout_time', 'has-error') }}">
                                <div class="row">
                                    <label for="dealer_detail_checkout_time" class="col-sm-2 control-label">@lang('dealer_details/form.dealer_detail_checkout_time') : </label>
                                    <div class="col-sm-10">
                                        <input id="dealer_detail_checkout_time" name="dealer_detail_checkout_time" placeholder="" type="text" class="form-control required" data-mask="99:99:99" value="{!! old('dealer_detail_checkout_time', $dealer_detail->dealer_detail_checkout_time) !!}"/>
                                        {!! $errors->first('dealer_detail_checkout_time', '<span class="help-block">:message</span>') !!}

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

    <script src="{{ asset('assets/js/pages/editdealerdetail.js') }}"></script>
@stop
