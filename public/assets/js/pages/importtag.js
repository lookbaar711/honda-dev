"use strict";

$("#sub_form").bootstrapValidator({
    fields: {
        import_file: {
            validators: {
                notEmpty: {
                    message: 'The file upload is required'
                }
                ,
                file: {
                    extension: 'xls,xlsx',
                    type: 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    maxSize: 5*1024*1024,   // 5 MB
                    message: 'Please select file upload type is .xls or .xlsx'
                }
                
            }
        }

    }
});


$('#import_tag').click(function () {
    document.getElementById("commentForm").submit();
});

$('#upload').click(function () {

    //alert('sub_form');

    var $validator = $('#sub_form').data('bootstrapValidator').validate();
    if ($validator.isValid()) {
        document.getElementById("sub_form").submit();
    }
});