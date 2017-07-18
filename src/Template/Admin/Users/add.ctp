<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user) ?>

        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('confirm_password', ['type'=>'password']);
            echo $this->Form->input('dob', ['type'=>'date']);
            echo $this->Form->input('role', [
              'options' => ['1' =>'Admin', '0' => 'User']
            ]);
            echo $this->Form->input('base64-avatar', ['type' => 'hidden']);
            echo $this->Form->input('avatar', ['type' =>'file', 'onchange' => 'previewFile()']);
            echo '<img id="pre_img" src="" height="100px" width ="100px" alt="Image preview...">';
            echo $this->Form->hidden('first_login', ['default' => 'false']);
        ?>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script type="text/javascript">
    function previewFile() {
      var preview = document.querySelector('img');
      var file    = document.querySelector('input[type=file]').files[0];
      var reader  = new FileReader();

      //  reader.onload = function (e) {
      //     $('#pre_img').attr('src',e.target.result);
      //     var data=e.target.result;
      //     var extension=data.substring(data.indexOf('/')+1,data.indexOf(';base64'));
      //     $('#base64-avatar').val(extension+"-"+data);
      //     console.log($('#base64-avatar').val());
      //  };
      reader.addEventListener("load", function () {
          preview.src = reader.result;
          $('#base64-avatar').val(reader.result);
          console.log($('#base64-avatar').val());
        }, false);

        if (file) {
          reader.readAsDataURL(file);
        }
      //  reader.readAsDataURL(file);
     }
  </script>
