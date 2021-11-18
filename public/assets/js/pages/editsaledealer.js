// bootstrap wizard//

$("#edit_sale_dealer_form").bootstrapValidator({
    fields: {
        sale_dealer_name: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก Sale Name'
                }
            },
            required: true,
            minlength: 3
        },
        // sale_dealer_nickname: {
        //     validators: {
        //         notEmpty: {
        //             message: 'กรุณากรอก Nickname'
        //         }
        //     },
        //     required: true,
        //     minlength: 3
        // },
        sale_dealer_tel: {
            validators: {
                // notEmpty: {
                //     message: 'กรุณากรอก เบอร์โทรศัพท์'
                // },
                regexp: {
                    regexp: /^0[0-9]{8,9}$/i,
                    message: 'กรุณากรอกเบอร์โทรศัพท์ รูปแบบประเทศไทย'
                }
            },
            required: true,
            minlength: 10
        }
    }
});

$(document).on('click','#edit_sale_dealer',function(){
    var $validator = $('#edit_sale_dealer_form').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("edit_sale_dealer_form").submit();
    }
});
