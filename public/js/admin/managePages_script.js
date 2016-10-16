// $(document).ready(function(){
//     // if manage pages tab is clicked display the manage home images view
//     $('#pages-home-images').click(function(e){
//     e.preventDefault();
//       $('#admin-content-panel-header').text('Edit Home Images');
//       $('#main-content').empty();
//       $('#main-content').append(
//           '<div class="row">'+
//             '<div class="col s12">'+
//                 '<h5>Replace Image</h5>'+
//             '</div>'+
//
//             '<div class="col s12">'+
//                 '<form">'+
//                     '<div class="col s12">'+
//                     '<label>Preview</label>'+
//                     '</div>'+
//                     '<div class="col s12">'+
//                         '<img class="materialboxed" width="100%" height="300" src="http://placehold.it/1000x500">'+
//                     '</div>'+
//                     '<div class="input-field col s6">'+
//                         '<select id="app" class="browser-default">'+
//                             '<option value="" disabled selected>Choose image to change</option>'+
//                             '<option value="1">Image 1</option>'+
//                             '<option value="2">Image 2</option>'+
//                             '<option value="3">Image 3</option>'+
//                         '</select>'+
//
//                     '</div>'+
//                     '<div class="file-field input-field col s6"> '+
//                         '<div class="btn">'+
//                             '<span>File</span>'+
//                             '<input type="file">'+
//                         '</div>'+
//                         '<div class="file-path-wrapper">'+
//                             '<input class="file-path validate" type="text">'+
//                         '</div>'+
//                     '</div>'+
//
//                     '<div class="col s12">'+
//                         '<div class="valign-wrapper">'+
//                         '<button class="btn waves-effect waves-light valign center-block" type="submit" name="action">Submit'+
//                             '<i class="material-icons right">send</i>'+
//                         '</button>'+
//                         '</div>'+
//                     '</div">'+
//                 '</form>'+
//             '</div>'+
//
//
//             '<div class="col s12">'+
//                 '<h5>Add New Image</h5>'+
//             '</div>'+
//             '<div class="col s12">'+
//                 '<form">'+
//                     '<div class="file-field input-field col s12"> '+
//                         '<div class="btn">'+
//                             '<span>File</span>'+
//                             '<input type="file">'+
//                         '</div>'+
//                         '<div class="file-path-wrapper">'+
//                             '<input class="file-path validate" type="text">'+
//                         '</div>'+
//                     '</div>'+
//                     '<div class="col s12">'+
//                         '<div class="valign-wrapper">'+
//                             '<button class="btn waves-effect waves-light valign center-block" type="submit" name="action">Submit'+
//                             '<i class="material-icons right">send</i>'+
//                             '</button>'+
//                         '</div>'+
//                     '</div>'+
//
//                 '</form>'+
//             '</div>'+
//
//
//             '<div class="col s12">'+
//                 '<h5>Delete Image</h5>'+
//             '</div>'+
//
//
//             '<div class="col s12">'+
//                 '<form">'+
//                     '<div class="col s12">'+
//                     '<label>Preview</label>'+
//                     '</div>'+
//                     '<div class="col s12">'+
//                         '<img class="materialboxed" width="100%" height="300" src="http://placehold.it/1000x500">'+
//                     '</div>'+
//                     '<div class="input-field col s6">'+
//                         '<select class="browser-default">'+
//                             '<option value="" disabled selected>Choose image to change</option>'+
//                             '<option value="1">Image 1</option>'+
//                             '<option value="2">Image 2</option>'+
//                             '<option value="3">Image 3</option>'+
//                         '</select>'+
//                     '</div>'+
//
//                     '<div class="col s6">'+
//                         '<div class="input-field">'+
//                         '<button class="btn waves-effect waves-light" type="submit" name="action">Delete'+
//                         '</button>'+
//                         '</div>'+
//                     '</div">'+
//
//                 '</form>'+
//             '</div>'+
//
//           '</div>'+
//           '<div id="app">'+
//             '{{ message }}'+
//           '</div>'
//       );
//
//       var app = new Vue({
//           el: '#app',
//           data: {
//             message: 'Hello Vue!'
//           }
//         })
//
//     });
//
//
//
//     // if manage pages tab is clicked display the manage home text view
//     $('#pages-home-text').click(function(e){
//     e.preventDefault();
//       $('#admin-content-panel-header').text('Edit Home Text');
//       $('#main-content').empty();
//       $('#main-content').append(
        //   '<div class="row">'+
        //     '<div class="col s12">'+
        //         '<form">'+
        //             '<div class="row">'+
        //                 '<div class="input-field col s12">'+
        //                     '<select class="browser-default">'+
        //                         '<option value="" disabled selected>Choose slide to change</option>'+
        //                         '<option value="1">Slide 1</option>'+
        //                         '<option value="2">Slide 2</option>'+
        //                         '<option value="3">Slide 3</option>'+
        //                     '</select>'+
        //                 '</div>'+
        //             '</div>'+
          //
        //             '<div class="row">'+
        //                 '<div class="input-field col s12">'+
        //                     '<input id="input_text" type="text" length="10">'+
        //                     '<label for="input_text">Header</label>'+
        //                 '</div>'+
          //
        //             '</div>'+
        //             '<div class="row">'+
        //                 '<div class="input-field col s12">'+
        //                     '<textarea id="textarea1" class="materialize-textarea" length="120"></textarea>'+
        //                     '<label for="textarea1">Textarea</label>'+
        //                 '</div>'+
        //             '</div>'+
          //
        //             '<div class="row">'+
        //                 '<div class="col s12">'+
        //                     '<div class="valign-wrapper">'+
        //                         '<button class="btn waves-effect waves-light valign center-block" type="submit" name="action">Submit'+
        //                         '<i class="material-icons right">send</i>'+
        //                         '</button>'+
        //                     '</div>'+
        //                 '</div>'+
        //             '</div>'+
          //
        //         '</form>'+
        //     '</div>'+
        //   '</div>'
//       );
//     });
//
// });// end of $(document).ready()
