@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('checkin_checkout/title.checkin_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .dot-green {
        color: #2eb35d;
    }
    .dot-gray {
        color: #c1bfc0;
    }
    .dot-orange {
        color: #FD7500;
    }
    .dot-red {
        color: #E40421;
    }
    .barcode-input{ 
        background: url(../../../assets/img/barcode_icon.png) no-repeat right 5px center;
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
                <td class="p-l-40 p-t-20" align="left">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="fs-20" href="{{ URL::to('admin/events/'.$event->id.'/checkin_checkout') }}">@lang('checkin_checkout/title.checkin_checkout_list') </a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="active fs-20" style="color: #7b7e82;">@lang('checkin_checkout/title.checkin')</a>
                </td>
            </tr>
        </table>
    
        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary">
            <div class="card-body">
                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td align="left" class="fs-26">
                               <b>@lang('checkin_checkout/title.checkin_list') : </b><b style="color: #369DE2;">{{ $event->event_name }}</b>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="ml-3 mr-3">
        
                <div class="form-group ml-3 mr-3">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="row bg-gray">
                                <div class="col-lg-5 col-md-5 text-md-right">
                                    <label class="control-label my-3">ID Sale :</label>
                                </div>
                                <div class="col-lg-3 col-md-3 my-2">
                                    <input id="sale_dealer_code" name="sale_dealer_code" type="text" placeholder="" class="form-control barcode-input" autocomplete="off" autofocus value=""/>
                                </div>
                                <div class="col-lg-4 col-md-4 text-md-right">
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>

                <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                    <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="table">
                        <thead>
                            <tr class="filters">
                                <th>#</th>
                                <th>@lang('checkin_checkout/table.date')</th>
                                <th>@lang('checkin_checkout/table.dlr')</th>
                                <th>@lang('checkin_checkout/table.name')</th>
                                <th>@lang('checkin_checkout/table.id_sale')</th>
                                <th>@lang('checkin_checkout/table.sale_name')</th>
                                <th>@lang('checkin_checkout/table.checkin')</th>
                                <th>@lang('checkin_checkout/table.status')</th>
                                <th>@lang('checkin_checkout/table.note')</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    <input type="hidden" name="event_id" id="event_id" value="{{$event->id}}">
                </div>
            </div>
        </div>    
    </div>
</section>


@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>

    <script>
        $(function() {   
            var table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'url': '{!! route('admin.checkin_checkout.post_checkin_data') !!}',
                    'data': {
                       event_id: $('#event_id').val()
                    },
                },
                columns: [
                    {
                        data: null,
                        sortable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'date', name: 'date' },
                    { data: 'dlr', name: 'dlr' },
                    { data: 'dealer_name', name: 'dealer_name' },
                    { data: 'id_sale', name: 'id_sale' },
                    { data: 'sale_dealer_name', name: 'sale_dealer_name' },
                    { data: 'checkin', name: 'checkin'},
                    { data: 'status', name: 'status'},
                    { data: 'note', name: 'note'}
                ],
                searching: false
            });  
        });

        $('#sale_dealer_code').keypress(function (e) {
            if (e.which == 13) {
                $.ajax({
                    method: "POST",
                    url: '{!! route('admin.set_checkin') !!}',
                    data: {
                       code: $('#sale_dealer_code').val()
                    },
                    success: function(response){
                        var json_data = JSON.parse(response);
         
                        if(json_data.status == "1"){
                            $('#table').DataTable().ajax.reload();

                            Swal.fire({
                                type: 'success',
                                title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">'+json_data.message+'</p>',

                                html: '<div class="row">'+
                                    '<div class="col-lg-6 col-md-3 text-md-right"><label>ID Sale : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="id_sale" style="color: #369DE2;">'+json_data.data.id_sale+'</label></div>'+
                                    '<div class="col-lg-6 col-md-3 text-md-right"><label>Sale Name : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="sale_dealer_name" style="color: #369DE2;">'+json_data.data.sale_dealer_name+'</label></div>'+
                                    '<div class="col-lg-6 col-md-3 text-md-right"><label>Nickname : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="nickname" style="color: #369DE2;">'+json_data.data.nickname+'</label></div>'+
                                    '<div class="col-lg-6 col-md-3 text-md-right"><label>Mobile : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="mobile" style="color: #369DE2;">'+json_data.data.mobile+'</label></div></div>',

                                showConfirmButton: false,
                                showCancelButton: true,
                                cancelButtonColor: '#369DE2',
                                cancelButtonText: 'ปิดหน้าต่าง'
                            });
                            setTimeout(function(){
                                var button = document.getElementsByClassName('swal2-confirm')[0]
                                button.click()
                            },2000);

                            //clear form
                            $('#sale_dealer_code').val('');
                        }
                        else{
                            if(json_data.message == 'Alreadey Checkin.'){
                                Swal.fire({
                                    type: 'info',
                                    title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">'+json_data.message+'</p>',

                                    html: '<div class="row">'+
                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>ID Sale : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="id_sale" style="color: #369DE2;">'+json_data.data.id_sale+'</label></div>'+
                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Sale Name : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="sale_dealer_name" style="color: #369DE2;">'+json_data.data.sale_dealer_name+'</label></div>'+
                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Nickname : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="nickname" style="color: #369DE2;">'+json_data.data.nickname+'</label></div>'+
                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Mobile : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="mobile" style="color: #369DE2;">'+json_data.data.mobile+'</label></div></div>',

                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#369DE2',
                                    cancelButtonText: 'ปิดหน้าต่าง'
                                });
                                setTimeout(function(){
                                    var button = document.getElementsByClassName('swal2-confirm')[0]
                                    button.click()
                                },2000);

                                //clear form
                                $('#sale_dealer_code').val('');
                            }
                            else if(json_data.message == 'Checkin Late.'){
                                Swal.fire({
                                    title: 'การ CHECK-IN',
                                    html: 'ระบุเหตุผลในการ <b><font color="blue">มาสาย</font></b>',
                                    input: 'text',
                                    inputAttributes: {
                                        autocapitalize: 'off'
                                    },
                                    showCancelButton: true,
                                    confirmButtonColor: '#369DE2',
                                    cancelButtonColor: '#E40421',
                                    confirmButtonText: 'ยืนยัน',
                                    cancelButtonText: 'ยกเลิก',
                                    showLoaderOnConfirm: true,
                                    preConfirm: () => {
                                        if($("input[type=text].swal2-input").val() == ''){
                                            return Swal.showValidationMessage(
                                              'กรุณาระบุเหตุผล'
                                            );
                                        }
                                        else{
                                            return Swal.resetValidationMessage();
                                        }
                                    },
                                    allowOutsideClick: () => !Swal.isLoading()
                                })
                                .then(function () { 
                                    $.ajax({
                                        type: "POST",
                                        url: "{!! route('admin.set_checkin') !!}",
                                        data: { 
                                            code: $('#sale_dealer_code').val(),
                                            reason: $("input[type=text].swal2-input").val()
                                        },
                                        cache: false,
                                        success: function(response) {
                                            var json_data = JSON.parse(response);

                                            if(json_data.status == "1"){
                                                $('#table').DataTable().ajax.reload();

                                                Swal.fire({
                                                    type: 'success',
                                                    title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">'+json_data.message+'</p>',

                                                    html: '<div class="row">'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>ID Sale : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="id_sale" style="color: #369DE2;">'+json_data.data.id_sale+'</label></div>'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Sale Name : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="sale_dealer_name" style="color: #369DE2;">'+json_data.data.sale_dealer_name+'</label></div>'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Nickname : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="nickname" style="color: #369DE2;">'+json_data.data.nickname+'</label></div>'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Mobile : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="mobile" style="color: #369DE2;">'+json_data.data.mobile+'</label></div></div>',

                                                    showConfirmButton: false,
                                                    showCancelButton: true,
                                                    cancelButtonColor: '#369DE2',
                                                    cancelButtonText: 'ปิดหน้าต่าง'
                                                });
                                                setTimeout(function(){
                                                    var button = document.getElementsByClassName('swal2-confirm')[0]
                                                    button.click()
                                                },2000);

                                                //clear form
                                                $('#sale_dealer_code').val('');
                                            }
                                            else{
                                                $('#sale_dealer_code').val('');
                                            }
                                            
                                        },
                                        failure: function (response) {
                                            Swal.fire({
                                                type: 'error',
                                                title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">Internal Error</p>',
                                                showConfirmButton: false,
                                                showCancelButton: true,
                                                cancelButtonColor: '#369DE2',
                                                cancelButtonText: 'ปิดหน้าต่าง'
                                            });
                                            setTimeout(function(){
                                                var button = document.getElementsByClassName('swal2-confirm')[0]
                                                button.click()
                                            },2000);

                                            //clear form
                                            $('#sale_dealer_code').val('');
                                        }
                                    }); 
                                    
                                });  
                            }
                            else if(json_data.message == 'Checkin Over Quota.'){
                                Swal.fire({
                                    title: 'การ CHECK-IN',
                                    html: 'ระบุเหตุผลในการ <b><font color="blue">CHECK-IN เกิน Quota</font></b>',
                                    input: 'text',
                                    inputAttributes: {
                                        autocapitalize: 'off'
                                    },
                                    showCancelButton: true,
                                    confirmButtonColor: '#369DE2',
                                    cancelButtonColor: '#E40421',
                                    confirmButtonText: 'ยืนยัน',
                                    cancelButtonText: 'ยกเลิก',
                                    showLoaderOnConfirm: true,
                                    preConfirm: () => {
                                        if($("input[type=text].swal2-input").val() == ''){
                                            return Swal.showValidationMessage(
                                              'กรุณาระบุเหตุผล'
                                            );
                                        }
                                        else{
                                            return Swal.resetValidationMessage();
                                        }
                                    },
                                    allowOutsideClick: () => !Swal.isLoading()
                                })
                                .then(function () { 
                                    $.ajax({
                                        type: "POST",
                                        url: "{!! route('admin.set_checkin') !!}",
                                        data: { 
                                            code: $('#sale_dealer_code').val(),
                                            over_reason: $("input[type=text].swal2-input").val()
                                        },
                                        cache: false,
                                        success: function(response) {
                                            var json_data = JSON.parse(response);

                                            if(json_data.status == "1"){
                                                $('#table').DataTable().ajax.reload();

                                                Swal.fire({
                                                    type: 'success',
                                                    title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">'+json_data.message+'</p>',

                                                    html: '<div class="row">'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>ID Sale : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="id_sale" style="color: #369DE2;">'+json_data.data.id_sale+'</label></div>'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Sale Name : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="sale_dealer_name" style="color: #369DE2;">'+json_data.data.sale_dealer_name+'</label></div>'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Nickname : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="nickname" style="color: #369DE2;">'+json_data.data.nickname+'</label></div>'+
                                                        '<div class="col-lg-6 col-md-3 text-md-right"><label>Mobile : </label></div><div class="col-lg-6 col-md-3 text-md-left"><label id="mobile" style="color: #369DE2;">'+json_data.data.mobile+'</label></div></div>',
                                        
                                                    showConfirmButton: false,
                                                    showCancelButton: true,
                                                    cancelButtonColor: '#369DE2',
                                                    cancelButtonText: 'ปิดหน้าต่าง'
                                                });
                                                setTimeout(function(){
                                                    var button = document.getElementsByClassName('swal2-confirm')[0];
                                                    button.click();
                                                },2000);

                                                //clear form
                                                $('#sale_dealer_code').val('');
                                            }
                                            else{
                                                $('#sale_dealer_code').val('');
                                            }
                                            
                                        },
                                        failure: function (response) {
                                            Swal.fire({
                                                type: 'error',
                                                title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">Internal Error</p>',
                                                showConfirmButton: false,
                                                showCancelButton: true,
                                                cancelButtonColor: '#369DE2',
                                                cancelButtonText: 'ปิดหน้าต่าง'
                                            });
                                            setTimeout(function(){
                                                var button = document.getElementsByClassName('swal2-confirm')[0]
                                                button.click()
                                            },2000);

                                            //clear form
                                            $('#sale_dealer_code').val('');
                                        }
                                    }); 
                                    
                                }); 
                            }
                            else{
                                Swal.fire({
                                    type: 'error',
                                    title: '<p class="fs-25" style="font-family: \'Kanit\', sans-serif;">'+json_data.message+'</p>',
                                    showConfirmButton: false,
                                    showCancelButton: true,
                                    cancelButtonColor: '#369DE2',
                                    cancelButtonText: 'ปิดหน้าต่าง'
                                });
                                setTimeout(function(){
                                    var button = document.getElementsByClassName('swal2-confirm')[0]
                                    button.click()
                                },2000);

                                //clear form
                                $('#sale_dealer_code').val('');
                            }
                        }
                    }
                });
            }
        });

    </script>

@stop
