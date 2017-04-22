
tinymce.init({
    selector: '#announcement',
    plugins: [
        'advlist autolink link image lists charmap preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking',
        'save table contextmenu directionality emoticons template paste textcolor autoresize code preview'
    ],
    file_browser_callback_types: 'file image media',
    toolbar: 'undo redo removeformat | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | fontsizeselect formatselect | bullist numlist blockquote \
            | link image | code',
    image_dimensions: false,
    image_advtab: true,
    a_plugin_option: true,
    a_configuration_option: 400,
//     file_picker_callback: function (callback, value, meta) {
//     if (meta.filetype == 'image') {
//         var input = document.getElementById('image');
//         input.click();
//         input.onchange = function () {
//             var file = input.files[0];
//             var reader = new FileReader();
//             reader.onload = function (e) {
//                 callback(e.target.result, {
//                     alt: file.name
//                 });
//             };
//             reader.readAsDataURL(file);
//         };
//     }
// }

});
