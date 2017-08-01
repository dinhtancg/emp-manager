<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Department'), ['action' => 'edit', $department->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Department'), ['action' => 'delete', $department->id], ['confirm' => __('Are you sure you want to delete # {0}?', $department->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Departments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('Switch role'), ['controller' => 'Users', 'action' => 'loginAs']) ?></li>
    </ul>
</nav>
<div class="departments view large-9 medium-8 columns content">
    <h3><?= h($department->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($department->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($department->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($department->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($department->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Employees') ?></h4>
        <div id="recperpage">
            <?=$this->Form->create(null, [
            'url' => ['controller' => 'Departments', 'action' => 'view', $department->id],
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
        <?php if (!empty($users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('birthday') ?></th>
                <th><?= $this->Paginator->sort('avatar') ?></th>
                <th><?= __('Position') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user->username) ?></td>
                <td><?= h($user->email) ?></td>
                <td><?= h($user->birthday) ?></td>
                <td><img src="<?= '/img/uploads/'.$user->avatar?>" alt="Avatar" width="50px" height="50px"></td>
                <?php
                if ($user->isManager($user->id, $department->id)) {
                    ?>
                <td>Manager</td>
              <?php
                } else {
                    ?>
                <td>Employees</td>
              <?php
                } ?>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $user->id]) ?>

                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $user->id]) ?>

                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
                    <?php if ($user->isManager($user->id, $department->id)) {
                    ?>
                    <?= $this->Form->postLink(__('Down'), ['prefix'=>'admin','controller' => 'Departments', 'action' => 'manager', $department->id, $user->id], ['confirm' => __('Are you sure you want to down user # {0}?', $user->username)]) ?>
                    <?php
                } else {
                    ?>
                    <?= $this->Form->postLink(__('Up'), ['prefix'=>'admin','controller' => 'Departments', 'action' => 'manager', $department->id, $user->id], ['confirm' => __('Are you sure you want to up user # {0}?', $user->username)]) ?>
                <?php
                } ?>
                </td>
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
