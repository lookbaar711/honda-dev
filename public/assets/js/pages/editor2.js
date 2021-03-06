/*Created by lorvent on 1/20/2016.*/

$("#bootstrap-editor2").wysihtml5({
        toolbar: {
            "fa": true
        }
    }
);


$("#editor2").wysihtml5({

        toolbar: {
            "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
            "emphasis": true, //Italics, bold, etc. Default true
            "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
            "html": false, //Button which allows you to edit the generated HTML. Default false
            "link": false, //Button to insert a link. Default true
            "image": false, //Button to insert an image. Default true,
            "color": false, //Button to change color of font 
            "blockquote": false // Blockquote
            
        }
        
    }
);



$("#summernote").summernote({
    fontNames: ['Lato', 'Arial', 'Courier New']
});
$('body').on('click', '.btn-codeview', function (e) {

    if ( $('.note-editor').hasClass("fullscreen") ) {
        var windowHeight = $(window).height();
        $('.note-editable').css('min-height',windowHeight);
    }else{
        $('.note-editable').css('min-height','300px');
    }
});
$('body').on('click','.btn-fullscreen', function (e) {
    setTimeout (function(){
        if ( $('.note-editor').hasClass("fullscreen") ) {
            var windowHeight = $(window).height();
            $('.note-editable').css('min-height',windowHeight);
        }else{
            $('.note-editable').css('min-height','300px');
        }
    },500);

});
$('.note-link-url').on('keyup', function() {
    if($('.note-link-text').val() != '') {
        $('.note-link-btn').attr('disabled', false).removeClass('disabled');
    }
});
// jQuery.trumbowyg.langs.fr = {
//     _dir: "ltr", // This line is optionnal, but usefull to override the `dir` option
//
//     bold: "Gras",
//     close: "Fermer"
// };
// $.trumbowyg.svgPath = 'assets/vendors/trumbowyg/css/ui/icons.svg';
// $("textarea#split_editor").trumbowyg({
//     btnsDef: {
//         // Customizables dropdowns
//         image: {
//             dropdown: ['insertImage', 'upload', 'base64', 'noembed'],
//             ico: 'insertImage'
//         }
//     },
//     btns: [
//         ['viewHTML'],
//         ['undo', 'redo'],
//         ['formatting'],
//         'btnGrp-design',
//         ['link'],
//         ['image'],
//         'btnGrp-justify',
//         'btnGrp-lists',
//         ['foreColor', 'backColor'],
//         ['preformatted'],
//         ['horizontalRule'],
//         ['fullscreen']
//     ],
//     plugins: {
//         upload: {
//             serverPath: 'https://api.imgur.com/3/image',
//             fileFieldName: 'image',
//             headers: {
//                 'Authorization': 'Client-ID 9e57cb1c4791cea'
//             },
//             urlPropertyName: 'data.link'
//         }
//     }
//
// });

$.trumbowyg.svgPath = '../assets/vendors/trumbowyg/css/ui/icons.svg';
$("#split_editor").trumbowyg(
    {
        svgPath: false,
        hideButtonTexts: true
    }
);
jQuery.trumbowyg.langs.fr = {
    _dir: "ltr", // This line is optionnal, but usefull to override the `dir` option
    bold: "Gras",
    close: "Fermer"
};