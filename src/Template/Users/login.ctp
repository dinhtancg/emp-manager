<div class="users content large-5 medium-7" style="margin: auto;">
  <div class="users form">
  <?= $this->Flash->render('auth') ?>
      <?= $this->Form->create() ?>
      <fieldset>
          <legend><?= __('Please enter your username and password') ?></legend>
          <?= $this->Form->input('username') ?>
          <?= $this->Form->input('password') ?>
      </fieldset>
      <?= $this->Form->button(__('Login')); ?>
      <?= $this->Form->end() ?>
  </div>
  <?php echo $this->Html->link('Forget Password', array('controller' => 'users', 'action' => 'password')); ?>
</div>
