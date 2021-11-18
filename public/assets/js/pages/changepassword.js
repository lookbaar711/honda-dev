// bootstrap wizard//

$("#change_password_form").bootstrapValidator({
    fields: {
        password: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกรหัสผ่านปัจจุบัน'
                }
            }
        },
        new_password: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกรหัสผ่านใหม่'
                }
            }
        },
        confirm_password: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกยืนยันรหัสผ่าน'
                },
                identical: {
                    field: 'new_password',
                    message: 'รหัสผ่านใหม่ ไม่ตรงกับ ยืนยันรหัสผ่าน'
                }
            }
        }
    }
});


$(document).on('click','#change_password',function(){
    var $validator = $('#change_password_form').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("change_password_form").submit();
    }
});