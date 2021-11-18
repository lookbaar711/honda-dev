@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('events/title.edit_event')
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
<style type="text/css">
    .daterange-input {
        background: url(../../../assets/img/calendar_icon.png) no-repeat right 5px center;
        background-color: #FFFFFF;
    }
</style>
@stop


{{-- Page content --}}
@section('content')
<section class="content">
    <div class="row">
        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}"
                        style="color: #369DE2;">@lang('events/title.events_list')</a>
                </td>
                <td class="p-t-20">
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                </td>
                <td class="p-t-20">
                    <a class="active fs-20" style="color: #7b7e82;">@lang('events/title.edit_event')</a>
                </td>
            </tr>
        </table>
        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
            <div class="card panel-primary">

                <div class="card-body">
                    <div class="card-body">
                        <table border="0" class="col-sm-12">
                            <tr>
                                <td align="left" class="fs-26">
                                    <b>@lang('events/title.edit_event')</b>
                                </td>
                                <td align="right">
                                    <button type="submit" class="btn btn-lg button-blue" id="edit_event">
                                        @lang('events/form.save_button')
                                    </button>

                                    <a class="btn btn-lg button-red" href="{{ route('admin.events.index') }}">
                                        @lang('events/form.cancel_button')
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <hr class="ml-3 mr-3"><br>

                    <!--main content-->
                    {!! Form::model($event, ['url' => URL::to('admin/events/'.$event->id.''), 'method' => 'put', 'class'
                    => 'form-horizontal','id'=>'commentForm']) !!}
                    {{ csrf_field() }}
                    <!-- CSRF Token -->

                    <div class="col-12 row p-b-10">
                        <div class="col-sm-7">
                            <div class="form-group {{ $errors->first('event_name', 'has-error') }}">
                                <label for="event_name" class="p-b-5">@lang('events/form.event_name') * : </label>
                                <input id="event_name" name="event_name" type="text" placeholder="กรอกชื่องานอีเว้นท์"
                                    class="form-control required"
                                    value="{!! old('event_name', $event->event_name) !!}" />
                                {!! $errors->first('event_name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row p-b-10">
                        <div class="col-sm-7">
                            <div class="form-group {{ $errors->first('event_location', 'has-error') }}">
                                <label for="event_location" class="p-b-5">@lang('events/form.event_location') * :
                                </label>
                                <input id="event_location" name="event_location" type="text"
                                    placeholder="กรอกชื่อสถานที่ตั้ง เช่น IMPACT Challenger 1-3" class="form-control"
                                    value="{!! old('event_location', $event->event_location) !!}" />
                                {!! $errors->first('event_location', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 row p-b-120">
                        <div class="col-sm-5">
                            <div class="form-group {{ $errors->first('event_daterange', 'has-error') }}">
                                <label for="event_daterange" class="p-b-5">@lang('events/form.event_daterange') * :
                                </label>
                                <input id="event_daterange" name="event_daterange"
                                    placeholder="วัน/เดือน/ปี - วัน/เดือน/ปี เช่น 01/12/2562 - 07/12/2562" type="text"
                                    class="form-control daterange-input required"
                                    value="{!! old('event_daterange', $event->event_daterange) !!}" />
                                {!! $errors->first('event_daterange', '<span class="help-block">:message</span>') !!}
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
<script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/select2/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript">
</script>

<script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/js/pages/editevent.js') }}"></script>
@stop