var modal_setting_preemtions = {
  change_status_modal : function(id,e) {
    var row = $(e);
    console.log(row);
    $('#manage_setting_preemption').modal();
    modal_setting_id.value = id;
    running_model.value = $(row.parent().parent().find('td')[1]).html();
    type_model.value = $(row.parent().parent().find('td')[2]).html();
    dlr_model.value = $(row.parent().parent().find('td')[3]).html();
    area_model.value = $(row.parent().parent().find('td')[5]).html();
    id_sale_model.value = $(row.parent().parent().find('td')[6]).html();
    $('#status_model').html($(row.parent().parent().find('td')[9]).html());
    zone_model.value = $(row.parent().parent().find('td')[4]).html();
    sale_name_model.value = $(row.parent().parent().find('td')[7]).html();
    modal_setting_preemtions.cancel_model();
  },
  cancel_model : function() {
    $('#modal_set_status').val("");
  }
}
