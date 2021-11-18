// bootstrap wizard//

$("#commentForm").bootstrapValidator({
    fields: {
        event_name: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกชื่อ Event'
                }
            },
            required: true,
            minlength: 3
        },
        event_location: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกสถานที่จัดงาน'
                }
            },
            required: true,
            minlength: 3
        },
        /*
        event_start_date: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกวันที่เริ่มต้น'
                }
            },
            required: true,
            minlength: 3
        },
        event_end_date: {
            validators: {
                notEmpty: {
                    message: 'กรุณากรอกวันที่สิ้นสุด'
                }
            },
            required: true,
            minlength: 3
        }
        */
        event_daterange: {
            validators: {
                notEmpty: {
                    message: 'กรุณาเลือกวันที่จัดงาน'
                }
            },
            required: true,
            minlength: 3
        }
    }
});

$('#edit_event').click(function () {
    var $validator = $('#commentForm').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("commentForm").submit();
    }
});

$("#event_daterange").daterangepicker({
    locale: {
        format: 'DD/MM/YYYY'
    }
});

//var event_start = flatpickr("#event_start_date", {dateFormat: "d/m/Y"});
//var event_end = flatpickr("#event_end_date", {dateFormat: "d/m/Y"});
