<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
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
            echo $this->Form->input('birthday', [
              'minYear' => date('Y') - RANGE_birthday,
              'maxYear' => date('Y'),
              'required'=> true
              ]);
            echo "<br>";
            echo $this->Html->image('uploads/'.$user->avatar, ['alt' => 'Avatar']);
            echo $this->Form->input('base64-avatar', ['type' => 'hidden']);
            echo $this->Form->input('avatar', ['type' =>'file', 'onchange' => 'previewFile()']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
