@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('sale_dealers/title.sale_dealers_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
{{-- <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" /> https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css --}}
{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" /> --}}
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





    /* th, td{
      white-space: nowrap;
    } */

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
                    <a class="active fs-20" style="color: #7b7e82;">จัดการใบจอง</a>
                </td>
            </tr>
        </table>

        <div class="col-12 p-t-40" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary">
            <div class="card-body">

                <div class="card-body" style="padding-bottom: 1px;">
                    <table border="0">
                        <tr>
                            <td align="right">
                              @if (Sentinel::getUser()->status_process == 0)
                                <a href="{{ url('admin/preemptions/setting_preemptions/'.$event->id) }}" class="btn btn-lg button-red">ตั้งค่าใบจอง</a>
                              @endif
                                <a href="{{ url('admin/preemptions/expose_preemptions/'.$event->id) }}" class="btn btn-lg button-blue">เบิกใบจอง</a>
                                <a href="{{ url('admin/preemptions/return_preemptions/'.$event->id) }}" class="btn btn-lg button-black">คืนใบจอง</a>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="card-body">
                    <table border="0">
                        <tr>
                            <td align="left" class="fs-26">
                               <b>จัดการใบจอง : <span class="color-blue">{{ $event->event_name }}</span></b>
                            </td>
                        </tr>
                    </table>
                </div>

                <hr class="ml-3 mr-3">

                <div class="form-group">
                    <div class="col-12 row">

                        <div class="col-6 col-sm-6 col-md-3  col-lg-3">
                            <label for="input">@lang('sale_dealers/form.text_search') : </label>
                            <input type="text" class="form-control" name="text_search" id="text_search" placeholder="ค้นหาจาก" value=""/>
                        </div>

                        <div class="col-2">
                            <label for="input">Type : </label>
                            <select class="form-control required column_filter1" name="status_search_type"   data-column="2" id="col2_filter">
                                <option value="">ทั้งหมด</option>
                                @foreach ($preemption_type as $value)
                                  <option value="{{$value['preemption_type']}}">{{$value['preemption_type']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-2">
                            <label for="input">DLR : </label>
                            <select class="form-control required column_filter1" name="status_search_dlr"  data-column="3" id="col3_filter">
                                <option value="">ทั้งหมด</option>
                                @foreach ($dealer_dlr as $value)
                                  <option value="{{$value['dealer_dlr']}}">{{$value['dealer_dlr']}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-2">
                            <label for="input">Zone : </label>
                            <select class="form-control required column_filter1" name="status_search_zone"  data-column="4" id="col4_filter">
                                <option value="">ทั้งหมด</option>
                                @foreach ($dealer_zone as $value)
                                  <option value="{{$value['dealer_zone']}}">{{$value['dealer_zone']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="input">Area : </label>
                            <select class="form-control required column_filter1" name="status_search_area"  data-column="5" id="col5_filter">
                                <option value="">ทั้งหมด</option>
                                @foreach ($dealer_area as $value)
                                  <option value="{{$value['dealer_area']}}">{{$value['dealer_area']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="input">สถานะ : </label>
                            <select class="form-control required column_filter1" name="status_search_status"  data-column="11" id="col11_filter">
                                <option value="">ทั้งหมด</option>
                                <option value="1">เบิกใบจอง</option>
                                <option value="3">ยกเลิกใบจอง</option>
                                <option value="2">คืนใบจอง</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-dark" id="reset_button" style="margin-left: 5px; height: 37px; margin-top: 19px;" onclick="preemptions.reDate()">
                                <span class="fa fa-refresh"></span>
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <!-- <div class="form-group">
                        <div class="row">
                            <label for="event_name" class="col-sm-1 control-label bg-gray">ID Sale : </label>
                            <div class="col-sm-10">
                                <input id="event_name" name="event_name" type="text" placeholder="" class="form-control required col-sm-4" value="{!! old('event_name') !!}"/>

                                {!! $errors->first('event_name', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div> -->
                    <input type="hidden" id="event_id" value="{{$event->id}}">
                    <div class=”table-responsive”>
                      <table id="table_pre" class="table table-striped table-hover dataTable no-footer "></table>
                    </div>
                    {{-- <div class="table-responsive-lg table-responsive-sm table-responsive-md text-center"> --}}
                      {{-- <input type="hidden" id="event_id" value="{{$event->id}}"> --}}
                      {{-- <input type="hidden" id="total_preemptions" value="{{$event->total_preemptions}}"> --}}
                    {{-- <table class="table display nowrap" id="table_pre"></table> --}}
                     {{-- table-striped table-hover dataTable no-footer dtr-inline --}}
                    {{-- </div> --}}
                </div>

            </div>
        </div>
    </div>
    </div>
</section>

@include('admin.preemptions.modal.modal_setting')

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    {{-- <script src="/js/jquery-3.3.1.js"></script> --}}
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script src="/js/modal/modal_setting_preemtions.js"></script>

    <script type="text/javascript">
      var preemptions = {
        tableList_detail : $('#table_pre'),
        tableList: function () {
          preemptions.tableList_detail = $('#table_pre').DataTable({
            processing: true,
            serverSide: true,
            // bLengthChange: false,
            // bInfo: false,
            // "bFilter": false,
            // fixedHeader: true,
            // "scrollX": true,
            // "sScrollX": "110%",
            // "sScrollXInner": "100%",
            // "bScrollCollapse": true,
            "initComplete": function( settings, json ) {
              // $('table#table_pre')[0].style.width=null;
              // $('thead tr')[1].style.display="none";
            },
            "searching": true,
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
                  return 0;
                },
              },
              headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
              }
            },
            columns:
            [
              { title : '#' ,data: (a,b,c,d,e) => {
                var total_row = (preemptions.tableList_detail.page.info().start+d.row+1);
                return total_row;
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
                  return e.updated_at;
              }, name: 'updated_at' },

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

          $('.column_filter1').on('change', function () {
              preemptions.filterColumn($(this).attr('data-column'))
          });

          // $('#col2_filter').on('change', function () {
          //     preemptions.filterColumn( $(this).attr('data-column') );
          // });

        },


        filterColumn : function(i){
          console.log($('#col'+i+'_filter').val());
          $('#table_pre').DataTable().column(i).search(
              $('#col'+i+'_filter').val()

            ).draw();
        },
        reDate : function() {
          preemptions.tableList_detail.ajax.reload();
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
                  preemptions.tableList_detail.ajax.reload();
                }
              }
            });
        },
        start : function() {
          document.getElementById('confirm_setting_preemtions').setAttribute( "onClick", "preemptions.manage_status()" );
          preemptions.tableList();
          check_running.hidden_fil_dataTable('table_pre');
          $('#text_search').on( 'keyup', function () {
            preemptions.tableList_detail.search( this.value ).draw();
          });
          $( "form" ).submit(function( event ) {
            event.preventDefault();
          })
        }
      }

      $(document).ready(function() {
        preemptions.start();
      });
    </script>




@stop
