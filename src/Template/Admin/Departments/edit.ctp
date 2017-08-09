<nav class="large-3 medium-4 columns" id="actions-sidebar">
  <ul class="side-nav">
      <li class="heading"><?= __('Actions') ?></li>
      <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?></li>
  </ul>
</nav>
<div class="departments form large-5 medium-7 columns content" id="form">
    <?= $this->Form->create($department) ?>
    <fieldset>
        <legend><?= __('Edit Department') ?></legend>
        <?php
            echo $this->Form->input('name');
        ?>
        <h5>Search</h5>
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for usernames..">
        <div id="" style="overflow:scroll; height:400px;">
          <?php echo $this->Form->input('users._ids', ['options' => $users,'multiple'=>'checkbox']); ?>
        </div>
        <input type="checkbox" class="checkall" />
        <label for="checkall" style="color:red">Sellect All Users</label>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
