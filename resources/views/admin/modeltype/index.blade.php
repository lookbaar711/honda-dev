@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@lang('sale_dealers/title.sale_dealers_list')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap4.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">
    .border-right{
      min-height: 160px;
    }
    .color-blue {
        color: #369DE2
    }

    tr.group,
    tr.group:hover {
        background-color: #f8f8f8  !important;
    }

    th.text-left.text-th-draw {
      padding-left: 5% !important;
    }
    td.text-left.text-th-draw {
      padding-left: 5% !important;
    }

    .table td {
      padding: 0.25rem !important;
    }

    th.text-right.sorting {
      padding-right: 2% !important;
    }
</style>
@stop




{{-- Page content --}}
@section('content')
<section class="content">
    <div class="row">


        <div class="col-12 p-t-40" style="margin-left: 25px; padding-right: 70px;">
        <div class="card panel-primary">
            <div class="card-body">

              <div class="card-body">
                  <table border="0">
                      <tr>
                          <td align="left" class="fs-26">
                             <b>จัดการ Model และ Type</b>
                          </td>
                          <td align="right">

                            <button type="button" name="button" class="btn btn-lg button-red" data-toggle="modal" data-target="#add_manage_model_type"><span class="fa fa-plus"></span> เพิ่ม Model</button>

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


                        <button class="btn btn-outline-dark" id="reset_button" style="margin-left: 5px; height: 37px; margin-top: 19px;" onclick="modelCars.tableList_detail.ajax.reload();">
                                <span class="fa fa-refresh"></span>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <div class="table-responsive-lg table-responsive-sm table-responsive-md text-center">
                    <table class="table " id="table_model"></table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</section>

{{-- @include('admin.preemptions.modal.modal_setting') --}}

<!-- Modal Manage Expose -->
<div class="modal fade" id="add_manage_model_type" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="add_title">Add Model</h3>
            </div>
            <div class="modal-body">

              <div class="col-12 form-row">

                <div class="col-12 ">
                  <div class="form-group row">
                    <label for="staticEmail" class="col-4 col-form-label text-right" id="add_title_sub">Model Name :</label>
                    <div class="col-8">
                      <input type="text" class="form-control"  id="add_model_name" value="">
                    </div>
                  </div>
                </div>

                <div class="col-12" style="overflow-x: hidden;height: 250px;" id="model_content">
                  <div>
                    <input type="hidden" id="set_model" value="">
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">Type Name : </label>
                    <div class="col-6">
                          <input type="text" class="form-control" placeholder="" id="add_type_name_s" >
                    </div>
                      <button type="button" onclick="modelCars.add_mondelandtype('add')" id="input_add_mondelandtype" class="buttonplus btn btn-dark input_add_mondelandtype" title="View">
                        <i class="fa fa-plus icon_plus"></i>
                      </button>
                    </div>
                    <div class="action_add_mondelandtype"></div>
                  </div>
                </div>

              </div>
              <br>

            </div>
            <div class="modal-footer">
                <button onclick="modelCars.sent_add()" id="confirm_model" class="btn btn-lg button-blue">ยืนยัน</button>
                <button type="button" class="btn btn-lg button-red" onclick="modelCars.clear_model()">ยกเลิก</button>
            </div>
        </div>

    </div>
</div>


@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script src="/js/modal/modal_setting_preemtions.js"></script>

    <script type="text/javascript">
      var modelCars = {
        tableList_detail : $('#table_model'),
        wrapper_tel   : $(".action_add_mondelandtype"),
        add_button_tel :$(".input_add_mondelandtype"),
        x : 1, //initlal text box count,
        sub_model_cars_id : 0,
        model_cars_id : 0,
        check_index : '',
        start : function() {
          modelCars.tableList();
          $(".stopTagA").on('click', function(event) {
               event.preventDefault();
          });

          $('#add_manage_model_type').on('hidden.bs.modal', function () {
            modelCars.clear_model();
          });
          $(modelCars.wrapper_tel).on("click",".remove_fieldtel", function(e){ //user click on remove text
            $(this).parent('div').remove();
            // x--;
          })
          check_running.hidden_fil_dataTable('table_model');

          $('#text_search').on( 'keyup', function () {
              modelCars.tableList_detail.search( this.value ).draw();
          } );
        },
        tableList: function () {
          modelCars.tableList_detail = $('#table_model').DataTable({
            "columnDefs": [
              { "visible": false, "targets": 1 }
            ],
            // destroy : true,
              processing: true,
              serverSide: true,
              "initComplete": function(settings, json) {
                $('th')[0].style.width='10%';
                $('th')[2].style.width='13%';
              },
            // "order": [[ 1, 'asc' ]],
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;



                api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                  var res = group.split("_");
                    if ( last !== group ) {
                        $(rows).eq( i ).before(

                            '<tr class="group "><td class="text-left" colspan="2">'+res[1]+'</td><td class="pull-right"><a href="#" class="btn btn-xs stopTagA" onClick="modelCars.edit_model(this,'+"'model',"+res[0]+')"><span class="fa fa-pencil"></span></a><a href="#" class="btn btn-xs stopTagA" onClick="modelCars.delete_model(this,'+"'model',"+res[0]+')"><span class="fa fa-trash"></span></a></td></tr>'
                        );

                        last = group;
                    }
                } );
                modelCars.check_index='';

            },
            ajax: {
              "url": window.location.origin+'/admin/ManagementModalAndType/ShowModelType',
              "type": "post",
              headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
              }
            },

            columns:
            [
              { title : '#' ,data: (a,b,c,d,e) => {

                if (modelCars.check_index == '') {
                  modelCars.check_index = a.set_name+'_1';
                  return 1;
                }else {
                  var res = modelCars.check_index.split("_");
                  if ((res[0]+'_'+res[1]) == a.set_name) {
                    modelCars.check_index = a.set_name+'_'+((res[2]*1)+1);
                    return ((res[2]*1)+1);
                  }else {
                    modelCars.check_index = a.set_name+'_1';
                    return 1;
                  }
                }
                // return a.set_name;
                // return (modelCars.tableList_detail.page.info().start+d.row+1);
                // if (modelCars.check_index ==) {
                //
                // }
              }, name: 'model_car_name' , className:'text-left' },

              { title : 'Model Name' ,data: (e) => {
                return e.set_name;
              }, name: 'set_name' },

              { title : 'Type Name' ,data: (e) => {
                  return e.sub_model_car_name;
              }, name: 'sub_model_car_name' , className:'text-left text-th-draw'},

              { title : 'จัดการ' ,data: (e) => {
                  return '<div class="pull-right"><a href="#" class="btn btn-xs stopTagA" onClick="modelCars.edit_model(this,'+"'type',"+e.id+')"><span class="fa fa-pencil"></span></a><a href="#" class="btn btn-xs stopTagA" onClick="modelCars.delete_model(this,'+"'type',"+e.id+')"><span class="fa fa-trash"></span></a></div>';
              }, name: 'id', className:'text-right' },
            ],

          });

        },
        add_mondelandtype: function(type) {
              // var wrapper_tel         = $(".action_add_mondelandtype"); //Fields wrapper
              // var add_button_tel      = $(".input_add_mondelandtype"); //Add button ID text-th-draw
              // var x = 1; //initlal text box count
              // x++;
              let r = $('#add_type_name_s').val();
              $('#add_type_name_s').val("");

              if (r == "") {
                Swal.fire({
                  type: 'error',
                  title: 'Oops...',
                  text: 'กรุณากรอก Type Name',
                })
                return false;
              }


              var check_arr = [];
              for (var i = 0; i < $('.add_type_name').length; i++) {
                check_arr.push($('.add_type_name')[i].value);
              }
              check_arr.push(r);
              var check = check_running.find_duplicate_in_array(check_arr);

              if (check.length != 0) {
                Swal.fire({
                  type: 'error',
                  title: 'Oops...',
                  text: 'Type Name ซ้ำ',
                })
                return false;
              }

              if (type == 'add') {
                var str = '<div class="form-group row remove_all">';
                      str +='<label for="staticEmail" class="col-4 col-form-label text-right"> </label>';
                            str +='<div class="col-6">';
                              str +='<input type="text" class="form-control add_type_name" value="'+r+'" onchange="modelCars.change_mondelandtype(this)">';
                            str +='</div>';
                            str +='<button type="button" class="buttonplus btn btn-danger remove_fieldtel" title="View">';
                              str +='<i class="fa fa-trash-o"></i>';
                            str +='</button>';
                          str +='</div>';
              }else {
                var str = '<div class="form-group row remove_all">';
                      str +='<label for="staticEmail" class="col-4 col-form-label text-right"> </label>';
                            str +='<div class="col-6">';
                              str +='<input type="text" class="form-control add_type_name" value="'+r+'" onchange="modelCars.change_mondelandtype(this)">';
                              str +='<input type="hidden" class="form-control edit_type_name" value="'+'new_'+modelCars.model_cars_id+'_'+modelCars.sub_model_cars_id+'">';
                            str +='</div>';
                            str +='<button type="button" class="buttonplus btn btn-danger remove_fieldtel" title="View">';
                              str +='<i class="fa fa-trash-o"></i>';
                            str +='</button>';
                          str +='</div>';
              }



                $(modelCars.wrapper_tel).append(str); //add input box


        },
        change_mondelandtype : function(e) {
          // console.log(e.value);
          var check_arr = [];
          for (var i = 0; i < $('.add_type_name').length; i++) {
            check_arr.push($('.add_type_name')[i].value);
          }
          var check = check_running.find_duplicate_in_array(check_arr);
          if (check.length != 0) {
            e.value="";
            Swal.fire({
              type: 'error',
              title: 'Oops...',
              text: 'Type Name ซ้ำ',
            })
            return false;
          }
        },
        sent_add : function() {
          if ($('#add_model_name').val() == '' ) {
            Swal.fire({
              type: 'error',
              title: 'Oops...',
              text: 'กรุณากรอก Model Name',
            })
            return false;
          }

          if ($('.add_type_name').length == 0) {
            Swal.fire({
              type: 'error',
              title: 'Oops...',
              text: 'กรุณาเพิ่ม Type Name',
            })
            return false;
          }

          var check_arr = [];
          for (var i = 0; i < $('.add_type_name').length; i++) {
            check_arr.push($('.add_type_name')[i].value);
          }

          Swal.fire({
            title: 'loading ....',
            onOpen: () => {swal.showLoading() }
          })

          $.ajax({
              url: window.location.origin+'/admin/ManagementModalAndType/InsertModelType',
              headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
              data: {
                       'modelName' : $('#add_model_name').val(),
                       'typeName' : check_arr,
                     },
              type: 'post',
              dataType: "json",
              success: function (data) {
                swal.close();
                if (data.status == 'success') {
                  modelCars.clear_model();
                 modelCars.tableList_detail.ajax.reload();
                }else {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: data.messages,
                  })
                }
              }
            });
        },
        clear_model : function() {
          $('#add_manage_model_type').modal("hide");
          $('#add_model_name').val("");
          $('#add_type_name_s').val("");
          $('.remove_all').remove();
          $('#add_title').text("Add Model");
          $('#add_title_sub').text("Model Name :");
          // add_title_sub Model Name :
          $("#model_content").show();
          modelCars.sub_model_cars_id=0;
          modelCars.model_cars_id=0;
        },
        edit_model : function(e,type,id) {
          console.log(type);

          var row = $(e);
          var title = $(row.parent().parent().find('td')[0]).html();
          if (type == 'model') {
            $('#add_title').text("Edie Model");
            $('#add_model_name').val(title);
            // input_add_mondelandtype
            $.ajax({
                url: window.location.origin+'/admin/ManagementModalAndType/GetModelType',
                headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
                data: {
                         'type' : type,
                         'id' : id
                       },
                type: 'post',
                dataType: "json",
                success: function (data) {
                    window.scrollTo(0,document.body.scrollHeight);
                    modelCars.model_cars_id = id;
                  for (var i = 0; i < data.data.length; i++) {
                    $('#add_manage_model_type').modal("show");
                    var str = '<div class="form-group row remove_all">';
                          str +='<label for="staticEmail" class="col-4 col-form-label text-right"> </label>';
                                str +='<div class="col-6">';
                                  str +='<input type="text" class="form-control add_type_name" value="'+data.data[i].sub_model_car_name+'" onchange="modelCars.change_mondelandtype(this)">';
                                  str +='<input type="hidden" class="form-control edit_type_name" value="'+'old_'+data.data[i].id+'_'+data.data[i].sub_model_cars_id+'">';
                                str +='</div>';
                                str +='<button type="button" class="buttonplus btn btn-danger remove_fieldtel" title="View">';
                                  str +='<i class="fa fa-trash-o"></i>';
                                str +='</button>';
                              str +='</div>';

                      $(modelCars.wrapper_tel).append(str); //add input box
                    // console.log(data.data[i]);
                    // array[i]
                  }
                }
              });

            // $('#add_manage_model_type').modal("show");
            // $("#add_manage_model_type").on('show.bs.modal', function(){
            //    alert("dddd");
            // });
            $("#add_manage_model_type").on('shown.bs.modal', function(){
              document.getElementById('confirm_model').setAttribute( "onClick", "modelCars.update('model')" );
              document.getElementById('input_add_mondelandtype').setAttribute( "onClick", "modelCars.add_mondelandtype('edit')" );
            });
          }else {
            window.scrollTo(0,document.body.scrollHeight);
            $('#add_title').text("Edie Type");
            $('#add_title_sub').text("Type Name :");
              modelCars.sub_model_cars_id=id;
              title = $(row.parent().parent().parent().find('td')[1]).html();
              // console.log($(row.parent().parent().find('td')[1]).html());
            $('#add_model_name').val(title)
             $("#model_content").hide();
            $('#add_manage_model_type').modal("show");
            $("#add_manage_model_type").on('shown.bs.modal', function(){
              document.getElementById('confirm_model').setAttribute( "onClick", "modelCars.update('type')" );
            });

            // $("#add_manage_model_type").on('shown.bs.modal', function(){
            //   document.getElementById('confirm_model').setAttribute( "onClick", "modelCars.update('model')" );
            //   document.getElementById('input_add_mondelandtype').setAttribute( "onClick", "modelCars.add_mondelandtype('edit')" );
            // });
            // $('#add_model_name').val(title);
          }

        },
        update : function(type) {
          var data = [];

// console.log(type);

          if (type == 'model') {

            if ($('.remove_all').length == 0) {
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณากรอก Type Name',
              })
              return false;
            }

            for (var i = 0; i < $('.remove_all').length; i++) {
              var details = $('.remove_all .edit_type_name')[i].value;
              var res = details.split("_");

              var detail = {
                type : res[0],
                model_id : res[1],
                sub_model_id : res[2],
                sub_model_car_name : $('.remove_all .add_type_name')[i].value,
              };

              data.push(detail);
            }
            var modelName =  $('#add_model_name').val();
            $.ajax({
                url: window.location.origin+'/admin/ManagementModalAndType/UpdateModelType',
                headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
                data: {
                         'type' : type,
                         'type_process' : 'update',
                         'data' : data,
                         'model_car_name' : modelName,
                         'model_id' : modelCars.model_cars_id,
                       },
                type: 'post',
                dataType: "json",
                success: function (data) {
                  modelCars.clear_model();
                  modelCars.tableList_detail.clear().draw();
                  modelCars.tableList_detail.ajax.reload();
                  Swal.fire(
                    'Update',
                    "Model : "+modelName+" สำเร็จ",
                    'success'
                  )

                }
              });
          }else {
            var modelName =  $('#add_model_name').val();
            if ($('#add_model_name').val() == '') {
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'กรุณากรอก Type Name',
              })
              return false;
            }

            $.ajax({
                url: window.location.origin+'/admin/ManagementModalAndType/UpdateModelType',
                headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
                data: {
                         'type' : type,
                         'type_process' : 'update',
                         'sub_model_car_name' : modelName,
                         'sub_model_id' : modelCars.sub_model_cars_id,
                       },
                type: 'post',
                dataType: "json",
                success: function (data) {
                  modelCars.clear_model();
                  modelCars.tableList_detail.clear().draw();
                  modelCars.tableList_detail.ajax.reload();
                  Swal.fire(
                    'Update',
                    "Type Name : "+modelName+" สำเร็จ",
                    'success'
                  )

                }
              });


          }



          // console.log(data);
        },
        delete_model : function (e,type,id) {
          var title = 'Model Name : ';
          var row = $(e);
          title += $(row.parent().parent().find('td')[0]).html();
          if (type == 'type') {
            title = 'Type Name : ';
            title += $(row.parent().parent().parent().find('td')[1]).html();
          }

          Swal.fire({
            title: 'คุณต้องการลบข้อมูล',
            text: title,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                  url: window.location.origin+'/admin/ManagementModalAndType/UpdateModelType',
                  headers: {"X-CSRF-TOKEN":$('[name="_token"]').val()},
                  data: {
                           'type' : type,
                           'type_process' : 'delete',
                           'model_id' : id,
                           'sub_model_id' : id,
                         },
                  type: 'post',
                  dataType: "json",
                  success: function (data) {
                    Swal.fire(
                      'ลบข้อมูล!',
                      title,
                      'success'
                    )
                    modelCars.tableList_detail.ajax.reload();
                  }
                });

            }
          });
        }

      }

      $(document).ready(function() {
        modelCars.start();
      });
    </script>





@stop
