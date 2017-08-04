<nav class="large-3 medium-4 columns" id="actions-sidebar">
  <ul class="side-nav">
      <li class="heading"><?= __('Actions') ?></li>
      <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?></li>
  </ul>
</nav>
<div class="users form large-5 medium-7 columns content" id="form">
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('email');
            echo $this->Form->input('full_name');
            echo $this->Form->input('password');
            echo $this->Form->input('birthday', [
              'minYear' => date('Y') - RANGE_birthday,
              'maxYear' => date('Y')
              ]);
            echo $this->Form->input('gender', [
              'options' => ['men' =>'Men', 'women' => 'Women','other'=>'Other']
            ]);
            echo $this->Form->input('role', [
              'options' => ['0' => 'User','1' =>'Admin' ],
              'required'=> true
            ]);
            echo $this->Form->input('departments._ids', ['options' => $departments ,'multiple'=>'checkbox']);
        ?>
        <hr>
        <input type="checkbox" class="checkall" />
        <label for="checkall" style="color:red">Sellect All Departments</label>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
