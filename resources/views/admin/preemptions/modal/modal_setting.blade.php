<!-- Modal Manage Expose -->
<div class="modal fade" id="manage_setting_preemption" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">คืนใบจอง</h3>
            </div>
            <div class="modal-body">

              <div class="col-12 form-row">
                <div class="col-6 border-right">
                  <div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">Type :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="type_model" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">DLR :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="dlr_model" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">Area :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="area_model" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">ID Sale :</label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="id_sale_model" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputPassword" class="col-4 col-form-label text-right">สถานะ :</label>
                      <div class="col-6">
                        <span class="form-control-plaintext" readonly id="status_model"> <i class="fa fa-circle" style="color: #369DE2;"></i> เบิกใบจอง</span>
                      </div>
                    </div>
                  </div>
                </div>
                <input type="hidden" id='modal_setting_id' value="">
                <div class="col-6">
                  <div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">เลขที่ใบจอง : </label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="running_model" value="">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">Zone : </label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="zone_model" value="">
                      </div>
                    </div>
                    <div class="form-group row">

                    </div>
                    <div class="form-group row">
                      <label for="staticEmail" class="col-4 col-form-label text-right">Sale name : </label>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext text-color" id="sale_name_model" value="">
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
                    <select class="form-control" id="modal_set_status">
                      <option value="">- เลือก - </option>
                      <option value="0">New</option>
                      <option value="1">เบิกใบจอง</option>
                      <option value="2">คืนใบจอง</option>
                      <option value="3">ยกเลิกใบจอง</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
                <button onclick="" id="confirm_setting_preemtions" class="btn btn-lg button-blue">ยืนยัน</button>
                <button type="button" class="btn btn-lg button-red" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>

    </div>
</div>
