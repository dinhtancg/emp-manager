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
            echo $this->Form->input('role', [
              'options' => ['admin' =>'Admin', 'user' => 'User']
            ]);
            echo $this->Form->input('department_id', [
              'empty'  => 'No Departments'
            ]);
            echo $this->Form->input('job', [
              'options' => ['manager' =>'Manager', 'employee' => 'Employee'],
              'empty' => 'Do not have job'
            ]);
            echo $this->Form->hidden('first_login', ['default' => 'false']);
        ?>

    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
