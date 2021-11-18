// bootstrap wizard//

$("#edit_dealer_detail_form").bootstrapValidator({
    fields: {
        dealer_detail_amount: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก QUOTA'
                },
                integer: {
                    message: 'กรุณากรอกตัวเลขเท่านั้น'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_detail_brief_time: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก BRIEF TIME'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_detail_checkout_time: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก CHECK-OUT'
                }
            },
            required: true,
            minlength: 3
        }
    }
});

$(document).on('click','#edit_dealer_detail',function(){
    var $validator = $('#edit_dealer_detail_form').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("edit_dealer_detail_form").submit();
    }
});
