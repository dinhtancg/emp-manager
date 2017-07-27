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
 $(document).ready(function() {
   $('.checkall').click(function() {
     var checked = $(this).prop('checked');
     $('.select').find('input:checkbox').prop('checked', checked);
   });
 })
function onSelectSubmit(frmid)
 {
     document.getElementById(frmid).submit();
 }
