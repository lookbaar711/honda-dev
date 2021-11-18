// bootstrap wizard//

$("#edit_dealer_form").bootstrapValidator({
    fields: {
        dealer_zone: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก Zone'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_area: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก Area'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_dlr: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก DLR'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_name: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก DLR Name'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_vip: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก VIP'
                },
                integer: {
                    message: 'กรุณากรอกตัวเลขเท่านั้น'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_press: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก PRESS'
                },
                integer: {
                    message: 'กรุณากรอกตัวเลขเท่านั้น'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_weekday: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก Weekday'
                },
                integer: {
                    message: 'กรุณากรอกตัวเลขเท่านั้น'
                }
            },
            required: true,
            minlength: 3
        },
        dealer_weekend: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอก Weekend'
                },
                integer: {
                    message: 'กรุณากรอกตัวเลขเท่านั้น'
                }
            },
            required: true,
            minlength: 3
        }
    }
});

$(document).on('click','#edit_dealer',function(){
    var $validator = $('#edit_dealer_form').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("edit_dealer_form").submit();
    }
});