$(document).ready(function() {

   /**
    * Check all
    * @return {[type]} [description]
    */

     $('.checkall').click(function() {
       var checked = $(this).prop('checked');
       $('.select').find('input:checkbox').prop('checked', checked);
     });



   /**
    * resetPassword description confirm
    */
   $('#reset_button').click(function () {
     if (confirm('Are you sure reset password this user???')) {
       return true;
     }
     return false;
   })

})
/**
* image to base-64
* @return {[type]} [description]
*/
function previewFile() {
 var preview = document.querySelector('img');
 var file    = document.querySelector('input[type=file]').files[0];
 var reader  = new FileReader();
 reader.addEventListener("load", function () {
     preview.src = reader.result;
     $('#base64-avatar').val(reader.result);
     console.log($('#base64-avatar').val());
   }, false);

   if (file) {
     reader.readAsDataURL(file);
   }
}
/**
 * [submit query limit]
 * @param  {[type]} frmid [description]
 * @return {[type]}       [description]
 */
function onSelectSubmit(frmid)
 {
     document.getElementById(frmid).submit();
 }
 function myFunction() {
     // Declare variables
     var input, filter, checkbox, a, i;
     input = document.getElementById('myInput');
     filter = input.value.toUpperCase();

     checkbox = document.getElementsByClassName('checkbox');

     // Loop through all list items, and hide those who don't match the search query
     for (i = 0; i < li.length; i++) {
         a = checkbox[i].getElementsByTagName("label")[0];
         if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
             checkbox[i].style.display = "";
         } else {
             checkbox[i].style.display = "none";
         }
     }
 }
