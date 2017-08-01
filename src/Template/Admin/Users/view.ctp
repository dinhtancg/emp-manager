<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <?php if ($this->request->session()->read('Auth.User.id') != $user->id): ?>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <?php endif; ?>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('Switch role'), ['controller' => 'Users', 'action' => 'loginAs']) ?></li>

    </ul>
</nav>
<div class="users view large-5 medium-7 columns content" id="form">
    <h3><?= 'Profile of '.h($user->username) ?></h3>
    <table class="vertical-table">
        <tr>
          <th><?= __('Avatar')?></th>
          <th><img src="<?= '/img/uploads/'.$user->avatar?>" alt="Avatar" width="50px" height="50px" style="float:right"></th>
        </tr>

        <tr>
            <th><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Day Of Birth') ?></th>
            <td><?= h($user->birthday) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Departments') ?></h4>
        <div id="recperpage">
            <?=$this->Form->create(null, [
            'url' => ['controller' => 'Users', 'action' => 'view', $user->id],
            'id'  => 'recordsPerPage',
            ])?>
        	  <?= $this->Form->select('recperpageval',
                    [10=>10, 20=>20, 50=>50],
                    ['default' => 10, 'onchange'=>'onSelectSubmit("recordsPerPage")']
                )
            ?>
            <?=$this->Form->end()?>
        </div>
        <hr>
        <?php
        if (!empty($departments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Name') ?></th>
            </tr>
            <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= $this->Html->link($department->name, ['controller' => 'Departments', 'action' => 'view', $department->id]) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
            </ul>
            <p><?= $this->Paginator->counter() ?></p>
        </div>
    <?php endif; ?>
    </div>
</div>
