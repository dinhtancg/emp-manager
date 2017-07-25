<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Welcome') ?></li>
    </ul>
</nav>
<div class="users form large-5 medium-7 columns content" id="form">
<?= $this->Form->create($user) ?>
<fieldset>
    <legend><?= __('Change password') ?></legend>
    <?= $this->Form->input('old_password', ['type' => 'password' , 'label'=>'Old password'])?>
    <?= $this->Form->input('password1', ['type'=>'password' ,'label'=>'Password']) ?>
    <?= $this->Form->input('password2', ['type' => 'password' , 'label'=>'Repeat password'])?>
</fieldset>
<?= $this->Form->button(__('Save')) ?>
<?= $this->Form->end() ?>
