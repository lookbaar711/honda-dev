// bootstrap wizard//

$("#commentForm").bootstrapValidator({
    fields: {
        /*
        groups: {
            validators:{
                notEmpty:{
                    message: 'You must select a group'
                }
            }
        },
        myClass: {
            selector: '.edit-group',
            validators: {
                notEmpty: {
                    message: 'Please select a group'
                }
            }
        },
        */
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
        }
    }
});

$('#edit_user').click(function () {
    var $validator = $('#commentForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("commentForm").submit();
    }
});
