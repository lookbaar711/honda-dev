@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('dealers/title.dealers_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

@stop


{{-- Page content --}}
@section('content')

<style>
    .form-search {
        padding: 15px;
        margin-top: 15px;
        margin-left: 0px;
        margin-right: 0px;
        background: #F8F8F8;
    }

    .col-c {
        padding-right: 0px !important;
        padding-left: 10px !important;
    }

    .f-16 {
        font-size: 16px !important;
    }

    .f-18 {
        font-size: 18px !important;
    }

    .color-blue {
        color: #369DE2
    }

    /* enable absolute positioning */
    .inner-addon {
        position: relative;
    }

    /* style icon */
    .inner-addon .fa-barcode {
        position: absolute;
        padding: 10px;
        pointer-events: none;
    }

    .right-addon .fa-barcode {
        right: 0px;
    }

    .right-addon input {
        padding-right: 30px;
    }
</style>
<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20" align="left">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a href="/admin/events/{{$event->id}}/preemptions" class="active fs-20"
                        style="color: #7b7e82;">จัดการใบจอง</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="active fs-20" style="color: #7b7e82;">คืนใบจอง</a>
                </td>
            </tr>
        </table>

        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
            <div class="card panel-primary ">

                <div class="card-body">
                    <div class="card-body" style="padding-bottom: 1px;">
                        <div class="row">
                            <div class="col fs-26">
                                <b>คืนใบจอง : <span class="color-blue">{{ $event->event_name }}</span></b>
                            </div>
                            <div class="col text-right">
                                {{-- <a href="#" class="btn btn-lg button-blue">ยืนยัน</a>
                                <a href="#" class="btn btn-lg button-red">ยกเลิก</a> --}}
                            </div>
                        </div>
                        <div class="row form-search" style="padding-right: 0px;">
                            <div class="col">
                                <div class="row">
                                    <label class="col-2 col-form-label text-right">ID Sale :</label>
                                    <div class="col-3">
                                        <div class="inner-addon right-addon">
                                          <form action="#" onsubmit="return return_pre.scan()" >
                                            <i class="fa fa-barcode"></i>
                                            <input type="text" class="form-control" placeholder="Sale ID" id="sale_code" >
                                            <input type="hidden" id="event_id" value="{{$event->id}}">
                                          </form>

                                            {{-- <input type="text" class="form-control" /> --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                            <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="tableReturnPree"></table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- row-->
    <!-- Modal Manage Return -->
    <div class="modal fade" id="manage_return_preemption" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h3 class="modal-title"><b>คืนใบจอง</b></h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-c col-4">
                            <div class="row">
                                <span class="col-c col-6 col-form-label text-right f-18 m-auto"><b>ID Sale :</b></span>
                                <span class="col-c col-6 col-form-label color-blue f-16 m-auto" id="id_sale"></span>
                            </div>
                        </div>
                        <div class="col-c col-8">
                            <div class="row">
                                <span class="col-c col-3 col-form-label text-right f-18 m-auto"><b>Sale Name
                                        :</b></span>
                                <span class="col-c col-8 col-form-label color-blue f-16 m-auto" id="sale_name"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-c col-4">
                            <div class="row">
                                <span class="col-c col-6 col-form-label text-right f-18 m-auto"><b>Nickname :</b></span>
                                <span class="col-c col-6 col-form-label color-blue f-16 m-auto" id="sale_nickname"></span>
                            </div>
                        </div>
                        <div class="col-c col-8">
                            <div class="row">
                                <span class="col-c col-3 col-form-label text-right f-18 m-auto"><b>Mobile :</b></span>
                                <span class="col-c col-8 col-form-label color-blue f-16 m-auto" id="sale_mobile"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="table-responsive-lg table-responsive-sm table-responsive-md">
                                <table class="table table-striped table-hover dataTable no-footer dtr-inline" id="tableReturnPreeBySale"></table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button href="#" id="del_event" class="btn btn-lg button-blue" onclick="return_pre.sent()">ยืนยัน</button>
                    <button type="button" class="btn btn-lg button-red" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>

        </div>
    </div>

    @include('admin.preemptions.modal.modal_setting')

    {{-- <!-- Modal Manage Return -->
    <div class="modal fade" id="manage_return_preemption" role="dialog">
        <div class="modal-dialog modal-md">

            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">จัดการใบจอง</h3>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <a href="#" id="del_event" class="btn btn-lg button-blue">ยืนยัน</a>
                    <button type="button" class="btn btn-lg button-red" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>

        </div>
    </div>
</section> --}}
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}"></script>
<script src="/js/modal/modal_setting_preemtions.js"></script>

<script type="text/javascript">
  var return_pre = {
    returnPreBySale : [],
    Sale : [],
    tableList_detail : $('#tableReturnPreeBySale'),
    tableList_detail_sale : $('#tableReturnPree'),
    tableList: function () {
      return_pre.tableList_detail = $('#tableReturnPree').DataTable({
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
              return 2;
            },
          },
          headers: {
            'X-CSRF-TOKEN': $('input[name=_token]').val()
          }
        },
        columns:
        [
          { title : '#' ,data: (a,b,c,d,e) => {
            return (return_pre.tableList_detail.page.info().start+d.row+1);
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
              return e.response_at;
          }, name: 'response_at' },

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
    tableSaleList: function () {
    return_pre.tableList_detail_sale =  $('#tableReturnPreeBySale').DataTable({
        "data" : return_pre.returnPreBySale,
        "destroy": true,
        paging: false,
        searching: false,
        "ordering": false,
        "bPaginate": false,
        "bLengthChange": false,
        // "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        // "scrollY": "340px",
        // "scrollX": true,
         "columns": [
           { title : '#' ,data: (a,b,c,d,e) => {
                 return (return_pre.tableList_detail.page.info().start+d.row+1);
               }, name: 'id' },

               { title : 'DATE' ,data: (e) => {
                   return e.request_at;
               }, name: 'request_at' },

               { title : 'Type' ,data: (e) => {
                   return e.preemption_type;
               }, name: 'preemption_type' },

               { title : 'เลขที่จอง' ,data: (e) => {
                 return e.preemption_no;
               }, name: 'preemption_no' },

               { title : 'เลือกสถานะ' ,data: (e) => {
                 var str = '<button type="button" class="btn" style="color: white;background: #076EB3;padding: 4px 15px;" onclick="return_pre.change_status('+e.id+',2,this)"><i class="fa fa-reply" aria-hidden="true" ></i> คืน</button>';
                 // str += '<input type="hidden" id="status_'+e.id+'" value="">';
                  str += '<button type="button" class="btn" style="color: white;background: #373636;padding: 4px 15px;" onclick="return_pre.change_status('+e.id+',3,this)"><i class="fa fa-times-circle" aria-hidden="true"  ></i> ยกเลิก</button>';
                  return str;
               }, name: 'id' },

               { title : 'สถานะ' ,data: (e) => {
                 switch (e.preemption_status) {
                     case 2:
                         return '<i class="fa fa-circle" style="color: #369DE2;"></i> คืนใบจอง';
                       break;
                     case 3:
                         return '<i class="fa fa-circle" style="color: #E40421;"></i> ยกเลิกใบจอง';
                       break;
                     default:
                     return ' ';
                   break;
                   }
               }, name: 'id' },

         ]
     });
    },
    scan : function (e) {
      let sale_code = $('#sale_code').val();
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
            url: window.location.origin+'/admin/preemptions/getSaleAndPre',
            headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
            data: {
                     'event_id' : $('#event_id').val(),
                     'sale_code' : sale_code,
                   },
            type: 'post',
            dataType: "json",
            success: function (data) {
              if (data.status == 0) {
                Swal.fire({
                  type: 'error',
                  title: 'Oops...',
                  text: 'กรุณาตรวจสอบข้อมูล ID Sale',
                })
              }else {
                swal.close();
                return_pre.Sale = data.data[0];
                return_pre.returnPreBySale = data.data;
                return_pre.tableSaleList();
                var element = document.getElementById('tableReturnPreeBySale');
                element.style.width = null;
                // return_pre.tableList_detail_sale.ajax.reload();
                $('#manage_return_preemption').modal();
                $('#id_sale').text(return_pre.Sale.sale_dealer_code);
                $('#sale_name').text(return_pre.Sale.sale_dealer_name);
                $('#sale_nickname').text(return_pre.Sale.sale_dealer_nickname);
                $('#sale_mobile').text(return_pre.Sale.sale_dealer_tel);
              }
            }
          });
      }

    },
    change_status : function(id,status,e) {
      // console.log(e);
      for (var i = 0; i < return_pre.returnPreBySale.length; i++) {
        if (return_pre.returnPreBySale[i].id == id) {
          if ((return_pre.returnPreBySale[i].preemption_status*1)  != (status*1)) {
            return_pre.returnPreBySale[i].preemption_status = (status*1);
          }else {
            return_pre.returnPreBySale[i].preemption_status = 1;
          }
        }
      }
      return_pre.tableSaleList();
    },
    sent : function () {
      $('#manage_return_preemption').modal("hide")
      Swal.fire({
        title: 'loading ....',
        onOpen: () => {swal.showLoading() }
      })
      $.ajax({
          url: window.location.origin+'/admin/preemptions/UpdatePreemption',
          headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
          data: {
                   'event_id' : $('#event_id').val(),
                   'data' : return_pre.returnPreBySale,
                   'preemption_status' : 2,
                 },
          type: 'post',
          dataType: "json",
          success: function (data) {
            if (data.status == 'success') {
              swal.close();
              return_pre.tableList_detail.ajax.reload();
            }
          }
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
      $('#manage_setting_preemption').modal("hide")
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
              return_pre.tableList_detail.ajax.reload();
            }
          }
        });
    },
    start : function() {
      document.getElementById('confirm_setting_preemtions').setAttribute( "onClick", "return_pre.manage_status()" );
      $("select[id='modal_set_status'] option[value='0']").hide();
      return_pre.tableList();
      $( "form" ).submit(function( event ) {
        event.preventDefault();
      });
    }
  }

  $(document).ready(function() {
    return_pre.start();

  });
</script>
@stop
