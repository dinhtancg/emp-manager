$(document).ready(function() {/**
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
    * Check all
    * @return {[type]} [description]
    */

     $('.checkall').click(function() {
       var checked = $(this).prop('checked');
       $('.select').find('input:checkbox').prop('checked', checked);
     });


  /**
   * [submit query limit]
   * @param  {[type]} frmid [description]
   * @return {[type]}       [description]
   */
  function onSelectSubmit(frmid)
   {
       document.getElementById(frmid).submit();
   }
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
