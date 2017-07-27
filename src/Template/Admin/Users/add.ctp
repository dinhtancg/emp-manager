<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users form large-5 medium-7 columns content" id="form">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            echo $this->Form->input('confirm_password', ['type'=>'password']);
            echo $this->Form->input('dob', [
              'minYear' => date('Y') - 100,
              'maxYear' => date('Y')
              ]);
            echo $this->Form->input('gender', [
              'options' => ['men' =>'Men', 'women' => 'Women','other'=>'Other']
            ]);
            echo $this->Form->input('base64-avatar', ['type' => 'hidden']);
            echo $this->Form->input('avatar', ['type' =>'file', 'onchange' => 'previewFile()']);
            echo '<img id="pre_img" src="" height="100px" width ="100px" alt="Image preview...">';
            echo $this->Form->input('role', [
              'options' => ['1' =>'Admin', '0' => 'User'],
              'required'=> true
            ]);
            echo $this->Form->hidden('first_login', ['default' => 'false']);
            echo $this->Form->hidden('flag_delete', ['default' => 'false']);
            echo $this->Form->input('departments._ids', ['options' => $departments,'multiple'=>'checkbox']);
        ?>
        <input type="checkbox" class="checkall" />
        <label for="checkall" style="color:red">Sellect All Departments</label>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
