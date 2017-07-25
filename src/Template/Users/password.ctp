<?php $this->assign('title', 'Request Password Restart'); ?>
<div class="users content large-5 medium-7" >
  <h3><?php echo __('Forgot Password'); ?></h3>
  <?php
   echo $this->Form->create();
   echo $this->Form->input('email', ['autofocus'=>true, 'label'=>'Your Email address','require'=>true]);
   echo $this->Form->button('Request reset password');
   echo $this->Form->end();
   ?>
</div>
