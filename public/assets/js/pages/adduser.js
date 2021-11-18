"use strict";
// bootstrap wizard//

$("#commentForm").bootstrapValidator({
    fields: {
        status_process: {
            validators:{
                notEmpty:{
                    message: 'กรุณาเลือกสิทธิ์'
                }
            }
        },
        full_name: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกชื่อ - นามสกุล'
                }
            },
            required: true,
            minlength: 3
        },
        email_info: {
            validators: {
                emailAddress: {
                    message: 'กรุณากรอก Email'
                }
            }
        },
        tel_info: {
            validators: {
                regexp: {
                    regexp: /^0[0-9]{8,9}$/i,
                    message: 'กรุณากรอกเบอร์โทรศัพท์ รูปแบบประเทศไทย'
                }
            }
        },
        email: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกบัญชีผู้ใช้งาน'
                },
                regexp: {
                    regexp: /[a-zA-Z0-9"]+$/,
                    message: 'กรุณากรอกบัญชีผู้ใช้งาน เป็นภาษาอังกฤษ หรือ ตัวเลขเท่านั้น'
                }

                // emailAddress: {
                //     message: 'The input is not a valid email address'
                // } 
            }
        },
        password: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกรหัสผ่าน'
                }
            }
        }
    }
});

$('#add_user').click(function () {
    var $validator = $('#commentForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("commentForm").submit();
    }
});
