<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Department'), ['controller' => 'Departments', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Reset Password'), ['controller' => 'Users', 'action' => 'password']) ?></li>
        <li><?= $this->Html->link(__('Switch role'), ['controller' => 'Users', 'action' => 'loginAs']) ?></li>

    </ul>
</nav>
<div class="users index large-9 medium-8 columns content">
    <h3><?= __('Users') ?></h3>
    <div id="recperpage">
        <?=$this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'index'],
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
    <table cellpadding="0" cellspacing="0" style="word-break:break-word">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('birthday') ?></th>
                <th><?= $this->Paginator->sort('role') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->Number->format($user->id) ?></td>
                <td><?= $this->Html->link($user->username, ['action' => 'view', $user->id]) ?></td>
                <td><?= h($user->email) ?></td>
                <td><?= h($user->birthday) ?></td>
                <?php if ($user->role): ?>
                  <td>Admin</td>
                <?php else: ?>
                  <td>User</td>
                <?php endif; ?>
                <td class="actions">

                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                    <?php if ($this->request->session()->read('Auth.User.id') != $user->id): ?>
                      <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
                    <?php endif; ?>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
