@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('dealers/title.dealers_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
  <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />


<style type="text/css">
    .border-right{
      /* border-right: 1px solid #f1f1f2; */
      min-height: 160px;
      /* margin-top: 8px; */
    }

    .color-blue {
        color: #369DE2
    }

    .card-body {
      -webkit-box-flex: 1;
      -ms-flex: 1 1 auto;
      flex: 1 1 auto;
      padding: 0.25%;
    }

    .m_buttom{
          margin-bottom: 0rem;
    }

    hr.ml-1.mr-1 {
    margin-top: 0.25rem;
    margin-bottom: 0.25rem;
    }

    /* .text-color{
      color: #369DE2;
    } */
</style>

@stop


{{-- Page content --}}
@section('content')
<!--
<section class="content-header">
    <ol class="breadcrumb">
        <li>@lang('dealers/title.dealers_list')</li>
    </ol>
</section>
-->

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20" align="left">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a href="/admin/events/{{$event->id}}/preemptions" class="active fs-20" style="color: #7b7e82;">จัดการใบจอง</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="active fs-20" style="color: #7b7e82;">เบิกใบจอง</a>
                </td>
            </tr>
        </table>

        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary ">

            <div class="card-body">


                <div class="card-body" style="padding-bottom: 1px;">
                    <table border="0">
                        <tr>
                            <td align="right">
                              <button type="button" class="btn btn-lg button-blue" onclick="expose_preemptions.sent_data()">ยืนยัน</button>
                              <button type="button" class="btn btn-lg button-red" onclick="expose_preemptions.cancel_all()">ยกเลิก</button>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td align="left" class="fs-26">
                               <b>เบิกใบจอง : <span class="color-blue">{{ $event->event_name }}</span></b>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="ml-1 mr-1">

                <div class="card-body">

                  <div class="alert alert-secondary" style="padding-bottom: 0.01rem;">
                    <div class="form-group row">
                      <label for="staticEmail" class="col-2 col-form-label text-right">ID Sale :</label>
                      <div class="col-6">
                        {{-- <input type="text" class="form-control"> --}}
                        <form action="#" onsubmit="return expose_preemptions.scan()" id="stop_scan">
                          <input type="text" class="form-control" placeholder="Sale ID" id="sale_code" autofocus>
                          <input type="hidden" id="event_id" value="{{$event->id}}">
                        </form>

                      </div>
                    </div>
                  </div>

                  <div class="col-12 form-row">
                    <div class="col-4 border-right" >
                      <div>
                        <div class="form-group row m_buttom">
                          <label for="staticEmail" class="col-4 col-form-label text-right">ID Sale :</label>
                          <div class="col-6">
                            <input type="text" readonly class="form-control-plaintext" id="id_sale" value="">
                          </div>
                        </div>
                        <div class="form-group row m_buttom">
                          <label for="inputPassword" class="col-4 col-form-label text-right">Sale Name :</label>
                          <div class="col-6">
                            <input type="text" readonly class="form-control-plaintext" id="sale_name" value="">
                          </div>
                        </div>
                        <div class="form-group row m_buttom">
                          <label for="inputPassword" class="col-4 col-form-label text-right">Nick Name :</label>
                          <div class="col-6">
                            <input type="text" readonly class="form-control-plaintext" id="nick_name" value="">
                          </div>
                        </div>
                        <div class="form-group row m_buttom">
                          <label for="inputPassword" class="col-4 col-form-label text-right">Mobile :</label>
                          <div class="col-6">
                            <input type="text" readonly class="form-control-plaintext" id="mabile" value="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-8" style="overflow-x: hidden;height: 160px;">
                      <div>
                        <form action="#" onsubmit="return expose_preemptions.add_Preemption()" id="stop_scan">
                          <div class="form-group row">
                            <label for="staticEmail" class="col-2 col-form-label text-right">เลขที่ใบจอง : </label>
                            <div class="col-3">
                                <input type="text" class="form-control" placeholder="Running" id="running_s" >
                            </div>
                            <div class="col-3">
                                <select class="form-control model_car" name="model_cars_s" id="model_cars_s" data_model="model_cars_s" data_sub_model="sub_model_cars_s">
                                    <option value="">- Model Name -</option>
                                      @foreach($model_cars as $m)
                                    <option value="{{ $m->id }}">{{ $m->model_car_name}}</option>
                                      @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <select class="form-control" name="sub_model_cars_s" id="sub_model_cars_s">
                                    <option value="">- Type Name -</option>
                                </select>
                            </div>
                            <button type="button" onclick="expose_preemptions.add_Preemption()" id="input_add_Preemption" class="buttonplus btn btn-dark input_add_Preemption" title="View">
                              <i class="fa fa-plus icon_plus"></i>
                            </button>
                          </div>
                        </form>
                        <div class="action_add_Preemption"></div>
                      </div>
                    </div>
                  </div>
                {{-- <br> --}}

                    <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                        <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="tablePreemption"></table>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div><!-- row-->
</section>

@include('admin.preemptions.modal.modal_setting')
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script src="/js/modal/modal_setting_preemtions.js"></script>
    {{-- <script src="/js/add_preemption/add_preemption.js"></script> --}}

    <script type="text/javascript">

    var expose_preemptions = {
      sale_detail : [],
      tableList_detail : $('#tablePreemption'),
      scan : function (e) {
        let sale_code = $('#sale_code').val();
        console.log(sale_code);
        $('#sale_code').val("");
        if (sale_code == '') {
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'กรุณาตรวจสอบข้อมูล ID Sale',
          })
        }else {
          Swal.fire({
            title: 'loading ....',
            onOpen: () => {swal.showLoading() }
          })
          $.ajax({
              url: window.location.origin+'/admin/preemptions/getSale',
              headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
              data: {
                       'event_id' : $('#event_id').val(),
                       'sale_code' : sale_code,
                     },
              type: 'post',
              dataType: "json",
              success: function (data) {
                console.log(data);
                if (data.status == 0) {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'กรุณาตรวจสอบข้อมูล ID Sale',
                  })
                  expose_preemptions.sale_detail=[];
                  $('#id_sale').val('');
                  $('#sale_name').val('');
                  $('#nick_name').val('');
                  $('#mabile').val('');
                }else {
                  swal.close();
                  expose_preemptions.sale_detail=data.data;
                  $('#id_sale').val(data.data.sale_dealer_code);
                  $('#sale_name').val(data.data.sale_dealer_name);
                  $('#nick_name').val(data.data.sale_dealer_nickname);
                  $('#mabile').val(data.data.sale_dealer_tel);
                  document.getElementById('running_s').focus();
                }
              }
            });
        }

      },
      add_Preemption: function() {
            var wrapper_tel         = $(".action_add_Preemption"); //Fields wrapper
            var add_button_tel      = $(".input_add_Preemption"); //Add button ID
            var x = 1; //initlal text box count
            x++;
            let r = $('#running_s').val();
            let rr = $('#model_cars_s').val();
            let rrr = $('#sub_model_cars_s').val();
            let model_cars_option = $('#model_cars_s').html();





            if ($('#id_sale').val() != "") {
              var check = check_running.check_running(r);

              if (check == true ) {

                if ($('#model_cars_s').val() == '') {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'กรุณาตรวจสอบข้อมูล Model Name',
                  })
                  return false;
                }

                if ($('#sub_model_cars_s').val() == '') {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'กรุณาตรวจสอบข้อมูล Type Name',
                  })
                  return false;
                }

                $('#running_s').val("");
                $('#model_cars_s').val("");
                $('#sub_model_cars_s').html('<option value="">- Type Name -</option>');

                $(".int_model_car option[value='']").each(function() {
                    $(this).remove();
                });

                $(".int_sub_model_car option[value='']").each(function() {
                    $(this).remove();
                });

                var dup = [];
                dup.push(r);
                for (var i = 0; i < $('.int_running').length; i++) {
                  if ($('.int_running')[i].value != '') {
                    dup.push($('.int_running')[i].value);
                  }
                }
                var dups = check_running.find_duplicate_in_array(dup);
                if (dups.length != 0) {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'กรุณาตรวจสอบข้อมูล ID Sale',
                  })
                  return false;
                }



                var no_row = $('.int_running').length;

                var str = '<div class="form-group row remove_all">';
                      str +='<label for="staticEmail" class="col-2 col-form-label text-right"> </label>';
                      str +='<div class="col-3">';
                      str +='<input type="text" class="form-control int_running" value="'+r+'" onchange="expose_preemptions.change_running(this)"></div>';

                      str +='<div class="col-3">';
                      str +='<select class="form-control model_car int_model_car" name="model_cars_'+no_row+'" id="model_cars_'+no_row+'" data_model="model_cars_'+no_row+'" data_sub_model="sub_model_cars_'+no_row+'" onchange="expose_preemptions.change_sub_model(this)">'+model_cars_option+'</select></div>';

                      str +='<div class="col-3">';
                      str +='<select class="form-control sub_model_car int_sub_model_car" name="sub_model_cars_'+no_row+'" id="sub_model_cars_'+no_row+'"></select></div>';

                      str +='<button type="button" class="buttonplus btn btn-danger remove_fieldtel" title="View">';
                      str +='<i class="fa fa-trash-o"></i>';
                      str +='</button>';
                      str +='</div>';


                  $(wrapper_tel).append(str); //add input box

                  $(wrapper_tel).on("click",".remove_fieldtel", function(e){ //user click on remove text
                    // console.log(e);
                    $(this).parent('div').remove();
                    x--;
                  });

                  $('#model_cars_'+no_row).val(rr).trigger('chosen:updated');

                  $.ajax({
                      url:"{{ route('admin.preemptions.getsubmodel') }}",
                      method:"POST",
                      data:{
                        model_car_id: $('#model_cars_'+no_row).val(),
                        _token: $('input[name="_token"]').val()
                      },
                      success:function(result){
                        $('#sub_model_cars_'+no_row).html(result);
                        $('#sub_model_cars_'+no_row).val(rrr).trigger('chosen:updated');
                      }
                  });

              }
            }else {
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณาตรวจสอบข้อมูล ID Sale',
              })
            }



      },
      change_running : function(e) {
        var check = check_running.check_running((e).value);
        if (check != true) {
          (e).value = "";
        }
      },
      change_sub_model : function(e) {
        var data_model = $(e).attr('data_model');
        var data_sub_model = $(e).attr('data_sub_model');

        // console.log(data_model+' : '+data_sub_model);

        $.ajax({
            url:"{{ route('admin.preemptions.getsubmodel') }}",
            method:"POST",
            data:{
              model_car_id: $('#'+data_model).val(),
              _token: $('input[name="_token"]').val()
            },
            success:function(result){
              console.log(result);
              $('#'+data_sub_model).html(result);
            }
        });
      },
      cancel_all : function() {
        $('.remove_all').remove();
        expose_preemptions.sale_detail=[];
        $('#id_sale').val("");
        $('#sale_name').val("");
        $('#nick_name').val("");
        $('#mabile').val("");
      },
      sent_data : function() {
        console.log(expose_preemptions.sale_detail);
        var data = [];
        var data2 = [];
        var data3 = [];

        for (var i = 0; i < $('.int_running').length; i++) {
            console.log($('.int_running')[i].value+" | "+$('.int_model_car')[i].value+" | "+$('.int_sub_model_car')[i].value);
          if (($('.int_running')[i].value != '') && ($('.int_model_car')[i].value != '') && ($('.int_sub_model_car')[i].value != '')) {
            data.push($('.int_running')[i].value);
            data2.push($('.int_model_car')[i].value);
            data3.push($('.int_sub_model_car')[i].value);
          }else {

            if ($('.int_running')[i].value == '') {
              console.log($('.int_running')[i].value);
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณาตรวจสอบข้อมูลเลขที่ใบจอง',
              })
              return false;
            }else if ($('.int_model_car')[i].value == '') {
              console.log($('.int_model_car')[i].value);
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณาตรวจสอบข้อมูล Model Name',
              })
              return false;
            }else {
              console.log($('.int_sub_model_car')[i].value);
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณาตรวจสอบข้อมูล Type Name',
              })
              return false;
            }

          }

        }

        var check = check_running.find_duplicate_array(data);
        //console.log(data);
        // console.log(check);

        if (check.length != 0) {
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'กรุณาตรวจสอบข้อมูล',
          })
          return false;
        }else {

          //alert(data2);
          //console.log(data2);

          $.ajax({
              url: window.location.origin+'/admin/preemptions/UpdatePreemption',
              headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
              data: {
                       'event_id' : $('#event_id').val(),
                       'sale' : expose_preemptions.sale_detail,
                       'preemption_status' : 1,
                       'running' : data,
                       'model_car' : data2,
                       'sub_model_car' : data3

                     },
              type: 'post',
              dataType: "json",
              success: function (data) {
                if (data.status == 'success') {
                  expose_preemptions.tableList_detail.ajax.reload();
                  expose_preemptions.cancel_all();
                  document.getElementById('sale_code').focus();
                }else {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: data.messages,
                  })
                }
              }
            });
        }


      },
      tableList: function () {
        expose_preemptions.tableList_detail = $('#tablePreemption').DataTable({
          processing: true,
          serverSide: true,
          bLengthChange: false,
          bInfo: false,
          "searching": false,
          order: [[ 8, "desc" ]],
          language:{paginate:{previous: "«", next: "»",}},
          ajax: {
            "url": window.location.origin+'/admin/preemptions/getPreAll',
            "type": "post",
            data:{
              event_id : function() {
                return $('#event_id').val();
              },
              preemption_status : function() {
                return 1;
              },
            },
            headers: {
              'X-CSRF-TOKEN': $('input[name=_token]').val()
            }
          },
          columns:
          [
            { title : '#' ,data: (a,b,c,d,e) => {
              return (expose_preemptions.tableList_detail.page.info().start+d.row+1);
            }, name: 'id' },

            { title : 'เลขที่จอง' ,data: (e) => {
              return e.preemption_no;
            }, name: 'preemption_no' },

            { title : 'Model Name' ,data: (e) => {
              return e.model_car_name;
            }, name: 'model_car_name' },

            { title : 'Type Name' ,data: (e) => {
              return e.sub_model_car_name;
            }, name: 'sub_model_car_name' },

            { title : 'Type' ,data: (e) => {
                return e.preemption_type;
            }, name: 'preemption_type' },

            { title : 'DLR' ,data: (e) => {
                return e.dealer_dlr;
            }, name: 'dealer_dlr' },

            { title : 'Zone' ,data: (e) => {
                return e.dealer_zone;
            }, name: 'dealer_zone' },

            { title : 'Area' ,data: (e) => {
                return e.dealer_area;
            }, name: 'dealer_area' },

            { title : 'ID Sale' ,data: (e) => {
                return e.sale_dealer_code;
            }, name: 'sale_dealer_code' },

            { title : 'Sale Name' ,data: (e) => {
                return e.sale_dealer_name;
            }, name: 'sale_dealer_name' },

            { title : 'Time' ,data: (e) => {
                return e.request_at;
            }, name: 'request_at' },

            { title : 'Status' ,data: (e) => {
              // return e.preemption_status;
              switch (e.preemption_status) {
                case '1':
                    return '<i class="fa fa-circle" style="color: #27B35F;"></i> เบิกใบจอง';
                  break;
                case '2':
                    return '<i class="fa fa-circle" style="color: #369DE2;"></i> คืนใบจอง';
                  break;
                case '3':
                    return '<i class="fa fa-circle" style="color: #E40421;"></i> ยกเลิกใบจอง';
                  break;
                default:
                return ' ';
              break;
              }
            }, name: 'preemption_status' },
            //
            { title : 'จัดการ' ,data: (e) => {
              if (e.preemption_status != 0) {
                return '<a onclick="modal_setting_preemtions.change_status_modal('+e.id+',this)" ><i class="fa fa-cog" aria-hidden="true" style="color: #000;"></i></a>';
              }else {
                return '';
              }

            }, name: 'id' },

          ]
        });

      },
      manage_status : function() {
        if ($('#modal_set_status').val() == '') {
          Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'กรุณาเลือกสถานะใบจอง',
          })
          return false;
        }

        $('#manage_setting_preemption').modal("hide");
        Swal.fire({
          title: 'loading ....',
          onOpen: () => {swal.showLoading() }
        })
        $.ajax({
            url: window.location.origin+'/admin/preemptions/UpdatePreemption',
            headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
            data: {
                     'event_id' : $('#event_id').val(),
                     'id' : $('#modal_setting_id').val(),
                     'preemption_status' : '',
                     'set_preemption_status' : $('#modal_set_status').val()
                   },
            type: 'post',
            dataType: "json",
            success: function (data) {
              if (data.status == 'success') {
                swal.close();
                expose_preemptions.tableList_detail.ajax.reload();
              }
            }
          });
      },
      start : function() {
        document.getElementById('confirm_setting_preemtions').setAttribute( "onClick", "expose_preemptions.manage_status()" );
        $("select[id='modal_set_status'] option[value='3']").hide();
        $("select[id='modal_set_status'] option[value='2']").hide();
        expose_preemptions.tableList();
        $( "form" ).submit(function( event ) {
          event.preventDefault();
        });
      }
    }

    $(document).ready(function() {
      expose_preemptions.start();

    });

    $('.model_car').change(function(){
        var data_model = $(this).attr('data_model');
        var data_sub_model = $(this).attr('data_sub_model');

        $.ajax({
            url:"{{ route('admin.preemptions.getsubmodel') }}",
            method:"POST",
            data:{
              model_car_id: $('#'+data_model).val(),
              _token: $('input[name="_token"]').val()
            },
            success:function(result){
              $('#'+data_sub_model).html(result);
            }
        });
    });



    </script>

<!-- Modal Manage Expose -->
{{-- <div class="modal fade" id="manage_expose_preemption" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">จัดการใบจอง</h3>
            </div>
            <div class="modal-body">
              <div class="col-12 form-row">
                <div class="col-6 border-right">
                  <div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">Type :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="id_sale" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">DLR :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="sale_name" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">Area :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="nick_name" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">ID Sale :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="mabile" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">สถานะ :</label>
                      <div class="col-6">
                        <span class="form-control-plaintext" readonly> <i class="fa fa-circle" style="color: #27B35F;"></i> เบิกใบจอง</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">เลขที่ใบจอง : </label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="mabile" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">Zone : </label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="mabile" value="">
                      </div>
                    </div>
                    <div class="form-group row">

                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">Sale name : </label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="mabile" value="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="alert alert-secondary" style="background-color: #F8F8F8;">
                <div class="form-group row" style="margin-top: 14px; ">
                  <label for="colFormLabel" class="col-5 col-form-label text-right">แก้ไขสถานะใบจอง </label>
                  <div class="col-5">
                    <select class="form-control" >
                      <option>- เลือก - </option>
                      <option>2</option>
                      <option>3</option>
                      <option>4</option>
                      <option>5</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
                <a href="#" id="del_event" class="btn btn-lg button-blue">ยืนยัน</a>
                <button type="button" class="btn btn-lg button-red" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>

    </div>
</div> --}}

 @stop
