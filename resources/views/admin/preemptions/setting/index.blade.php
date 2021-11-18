@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('events/title.events_list')
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

    .dataTables_scrollHead {
      border-bottom: 2px solid #4b434300 !important;
    }

     .dataTables_scrollBody {
       border-bottom: 1px solid #4b434300 !important;
        overflow-x: hidden !important;
      }
</style>
@stop

{{-- Page content --}}
@section('content')
<section class="content paddingleft_right15">
    <div class="row">

        <table border="0">
            <tr>
                <td class="p-l-40 p-t-20" align="left">
                    <a class="fs-20" href="{{ URL::to('admin/events') }}">@lang('events/title.events_list')</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a href="/admin/events/{{$event->id}}/preemptions" class="active fs-20" style="color: #7b7e82;">จัดการใบจอง</a>
                    <a class="fs-20" style="color: #7b7e82;">/</a>
                    <a class="active fs-20" style="color: #7b7e82;">ตั้งค่าใบจอง</a>
                </td>
            </tr>
        </table>

        <input type="hidden" id="event_id" value="{{$event->id}}">

        <div class="col-md-12 col-sm-12 col-lg-12 my-3" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary">
            <div class="card-body">

                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td align="left" class="fs-26">
                               <b>ตั้งค่าใบจอง : </b>
                            </td>
                            <td align="right">
                                {{-- <a href="" onclick="setting_preemptions.sent()" class="btn btn-lg button-blue">ยืนยัน</a> --}}
                                <button onclick="setting_preemptions.sent()" name="button" class="btn btn-lg button-blue">ยืนยัน</button>
                                <a href="" class="btn btn-lg button-red">ยกเลิก</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <hr class="ml-3 mr-3">

                <div class="form-group">
                    <div class="col-12 row" >

                        <div class="col-8 col-sm-12 col-md-8  col-lg-8 col-xl-8 text-center" style="padding-bottom: 35px;">

                            <div class="form-inline">
                              <label style="padding-bottom: 10px;" for="input">ประเภท Turbo เลขที่ใบจอง : </label>
                            </div>
                            <table id="Turbo_detail"></table>
                        </div>
                    </div>
                </div>

                <hr class="ml-3 mr-3">

                <div class="form-group">
                    <div class="col-12 row">

                        <div class="col-8 col-sm-12 col-md-8  col-lg-8 col-xl-8 text-center" style="padding-bottom: 170px;">
                            <label style="padding-bottom: 10px;" for="input">ประเภท Normal เลขที่ใบจอง : </label>
                            <table id="Normal_detail"></table>
                            {{-- <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/> --}}
                        </div>
                    </div>
                </div>

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

    <script type="text/javascript">
      var setting_preemptions = {
        tableTurbo_detail : $('#Turbo_detail'),
        tableNormal_detail : $('#Normal_detail'),
        // tableNarmal_detail : $('#Narmal_detail'),
        Turbo_detail : [],
        Normal_detail : [],
        Turbo : [],
        Normal : [],
        Turbo_tables : function() {
          setting_preemptions.tableTurbo_detail = $('#Turbo_detail').DataTable({
            "data" : setting_preemptions.Turbo_detail,
            "destroy": true,
            paging: false,
            searching: false,
            "ordering": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "scrollY": "250px",
            "scrollX": false,
             "columns": [
               { "data": function(a,b,c,d) {
                 var str = '<div class="form-row">';
                     str += '<div class="form-group col-md-1 text-center">';
                     str += '<input type="text" class="form-control text-center" value="TB" disabled>';
                     str += '</div>';
                     str += '<div class="form-group col-md-4">';
                     if (a.start == '' && a.end  == '' ) {
                       str += '<input type="number" id="s" class="form-control" value="">';
                     }else {
                       str += '<input type="number" id="ns_'+d.row+'" onchange="setting_preemptions.change_number(0,'+d.row+',0)" class="form-control" value="'+a.start+'">';
                     }

                     str += '</div>';
                     str += '<div class="form-group col-md-1 text-center">';
                     str += '  <i class="fa fa-minus" aria-hidden="true"></i>';
                     str += '</div>';
                     str += '<div class="form-group col-md-1 text-center">';
                     str += '  <input type="text" class="form-control text-center" value="TB" disabled>';
                     str += '</div>';
                     str += '<div class="form-group col-md-4">';
                     if (a.start == '' && a.end  == '' ) {
                       str += '  <input type="number" id="e" class="form-control" value="">';
                     }else {
                       str += '  <input type="number" id="ne_'+d.row+'" onchange="setting_preemptions.change_number(0,'+d.row+',1)" class="form-control" value="'+a.end+'">';
                     }
                     str += '</div>';
                     str += '<div class="form-group col-md-1 text-center">';
                     if (a.start == '' && a.end  == '' ) {
                       str += '  <button  class="btn btn-primary " onclick="setting_preemptions.add_turbo()"><i class="fa fa-plus" aria-hidden="true"></i></button>';
                     }else {
                       str += '  <button  class="btn btn-danger " onclick="setting_preemptions.delete_turbo('+d.row+')"><i class="fa fa-minus" aria-hidden="true"></i></button>';
                     }


                     str += '</div>';
                   str += '</div>';
                         return str;
               }},


             ]
         });
        },
        Normal_tables : function() {
          setting_preemptions.tableNormal_detail = $('#Normal_detail').DataTable({
            "data" : setting_preemptions.Normal_detail,
            "destroy": true,
            paging: false,
            searching: false,
            "ordering": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "scrollY": "250px",
            "scrollX": false,
             "columns": [
               { "data":  function(a,b,c,d) {
                 var str = '<div class="form-row">';
                     str += '<div class="form-group col-md-5">';
                    if (a.start == '' && a.end  == '' ) {
                       str += '<input type="number" id="sn" class="form-control" value="">';
                     }else {
                       str += '<input type="number" id="nsn_'+d.row+'" onchange="setting_preemptions.change_number(1,'+d.row+',0)" class="form-control" value="'+a.start+'">';
                     }

                     str += '</div>';
                     str += '<div class="form-group col-md-1 text-center">';
                     str += '  <i class="fa fa-minus" aria-hidden="true"></i>';
                     str += '</div>';
                     str += '<div class="form-group col-md-5">';
                     if (a.start == '' && a.end  == '' ) {
                       str += '  <input type="number" id="en" class="form-control" value="">';
                     }else {
                       str += '  <input type="number" id="nen_'+d.row+'" class="form-control" onchange="setting_preemptions.change_number(1,'+d.row+',1)" value="'+a.end+'">';
                     }
                     str += '</div>';
                     str += '<div class="form-group col-md-1 text-center">';
                     // str += '  <button  class="btn btn-primary " onclick="setting_preemptions.add_normal()"><i class="fa fa-plus" aria-hidden="true"></i></button>';
                     if (a.start == '' && a.end  == '' ) {
                       str += '  <button  class="btn btn-primary " onclick="setting_preemptions.add_normal()"><i class="fa fa-plus" aria-hidden="true"></i></button>';
                     }else {
                       str += '  <button  class="btn btn-danger " onclick="setting_preemptions.delete_normal('+d.row+')"><i class="fa fa-minus" aria-hidden="true"></i></button>';
                     }
                     str += '</div>';
                   str += '</div>';
                         return str;
               }},

             ]
         });
       },
       change_number : function(type,index,position) {
         if (type == 0) { //Turbo
           if (position == 0) { //start
             console.log($('#ns_'+index).val());
             setting_preemptions.Turbo_detail[index].start = setting_preemptions.setRunning($('#ns_'+index).val()*1);
           }else {
             setting_preemptions.Turbo_detail[index].end = setting_preemptions.setRunning($('#ne_'+index).val()*1);
           }
           setting_preemptions.Turbo_tables();
         }else { //Normal
           if (position == 0) { //start
             setting_preemptions.Normal_detail[index].start = setting_preemptions.setRunning($('#nsn_'+index).val()*1);
           }else {
             setting_preemptions.Normal_detail[index].end = setting_preemptions.setRunning($('#nen_'+index).val()*1);
           }
            setting_preemptions.Normal_tables();
         }
       },
        add_turbo : function() {
          if ($('#s').val() != '' &&  $('#e').val() != '' && ($('#s').val()*1) <= ($('#e').val()*1)) {
            var Turbo_detail = {"start" : setting_preemptions.setRunning(($('#s').val()*1)) , "end" : setting_preemptions.setRunning($('#e').val()*1)};
            setting_preemptions.Turbo_detail.push(Turbo_detail);
            setting_preemptions.Turbo_tables();
          }else {
            swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณาตรวจสอบข้อมูล Turbo',
              })
          }
        },
        add_normal : function() {
          if ($('#sn').val() != '' &&  $('#en').val() != '' && ($('#sn').val()*1) <= ($('#en').val()*1)) {
            var Normal_detail = {"start" : setting_preemptions.setRunning($('#sn').val()*1) , "end" : setting_preemptions.setRunning($('#en').val()*1)};
            setting_preemptions.Normal_detail.push(Normal_detail);
            setting_preemptions.Normal_tables();
          }else {
            swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณาตรวจสอบข้อมูล ',
              })
          }
        },
        delete_turbo : function(index) {
          setting_preemptions.Turbo_detail.splice(index, 1);
          setting_preemptions.Turbo_tables();
        },
        delete_normal : function(index) {
          setting_preemptions.Normal_detail.splice(index, 1);
          setting_preemptions.Normal_tables();
        },
        sent : function() {
          var arr_tur = [];
          var arr_nor = [];
          var check_turbo = [];
          var check_normal = [];

          if(setting_preemptions.Turbo_detail.length != 1  ){
            for (var i = 0; i < setting_preemptions.Turbo_detail.length; i++) {
              if (i != 0) {
                for (var z = (setting_preemptions.Turbo_detail[i].start*1); z <= (setting_preemptions.Turbo_detail[i].end*1); z++) {
                  // console.log(z);
                  arr_tur.push(setting_preemptions.setRunning(z*1));
                }
              }
            }
            check_turbo = setting_preemptions.find_duplicate_in_array(arr_tur);
            if (check_turbo.length != 0) {
              swal.fire({
                  type: 'error',
                  title: 'Oops...',
                  text: 'กรุณาตรวจสอบข้อมูล Turbo',
                })
            }else {
              setting_preemptions.Turbo=arr_tur;
            }
          }else {
            swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณากรอกข้อมูล Normal',
              })
          }

          if(setting_preemptions.Normal_detail.length != 1  ){
            for (var i = 0; i < setting_preemptions.Normal_detail.length; i++) {
              if (i != 0) {
                for (var z = (setting_preemptions.Normal_detail[i].start*1); z <= (setting_preemptions.Normal_detail[i].end*1); z++) {
                  // console.log(z);
                  arr_nor.push(setting_preemptions.setRunning(z*1));
                }
              }
            }
            check_normal = setting_preemptions.find_duplicate_in_array(arr_nor);
            if (check_normal.length != 0) {
              swal.fire({
                  type: 'error',
                  title: 'Oops...',
                  text: 'กรุณาตรวจสอบข้อมูล Normal',
                })
            }else {
              setting_preemptions.Normal=arr_nor;
            }
          }else {
            swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณากรอกข้อมูล Normal',
              })
          }

          if (check_turbo.length == 0 && check_normal.length == 0) {
            setting_preemptions.sent_data();
          }


        },
        sent_data : function () {
          swal.showLoading();
          $.ajax({
                  url: "/admin/preemptions/InsertSettingPreemption",
                  headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
                  data: {
                           'event_id' : $('#event_id').val(),
                           // 'Turbo' : setting_preemptions.Turbo,
                           // 'Normal' : setting_preemptions.Normal,
                           'Turbo_detail' : setting_preemptions.Turbo_detail,
                           'Normal_detail' : setting_preemptions.Normal_detail,
                         },
                  type: 'post',
                  dataType: "json",
                  success: function (data) {
                    // console.log(data);
                    window.location = "/admin/events/"+$('#event_id').val()+"/preemptions";

                  }
              });
        },
        getData : function () {
            $.ajax({
                    url: "/admin/preemptions/getSettingPreemptionData/"+$('#event_id').val(),
                    type: "get",
                    dataType: "json",
                    success: function(response) {
                      setting_preemptions.Turbo_detail=response.data.Turbo;
                      setting_preemptions.Normal_detail=response.data.Normal;
                      setting_preemptions.Turbo_tables();
                      setting_preemptions.Normal_tables();
                    },
              });
        },
        setRunning : function(num) {
          var pattern = "00000000";
          return (pattern + (num*1)).slice(-8);
        },
        find_duplicate_in_array : function(data) {
          var object = {};
          var result = [];

          data.forEach(function (item) {
            if(!object[item])
                object[item] = 0;
                object[item] += 1;
              })

            for (var prop in object) {
               if(object[prop] >= 2) {
                   result.push(prop);
               }
            }

          return result;
        },
        start : function() {
          setting_preemptions.getData();

        }
      }

      $(document).ready(function() {
        setting_preemptions.start();
      });

    </script>
@stop
