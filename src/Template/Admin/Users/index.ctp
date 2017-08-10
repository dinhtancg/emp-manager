<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users index large-9 medium-8 columns content">
    <h3><?= __('Users') ?></h3>
    <?= $this->Form->create(null, [
           'type' => 'get',
           'url' => ['controller' => 'Users', 'action' => 'index'],
           'id'  => 'recordsPerPage',
         ]); ?>
     <?= $this->Form->input('search', ['placeholder' => 'Search by username, full_name, email', 'value' => ($search_key) ? $search_key : '']); ?>
     <?= $this->Form->submit('Search'); ?>
    <div id="recperpage">
      	<?= $this->Form->select('limit',
                  [10=>10, 20=>20, 50=>50],
                  ['default' => $sessionLimit,
                   'onchange'=>'onSelectSubmit("recordsPerPage")'
                  ])
        ?>

      </div>
    <?=$this->Form->end()?>
    <hr>
    <?=$this->Form->create(null, [
    'url' => ['controller' => 'Users', 'action' => 'password'],
    'id'  => 'resetPasswod',
    ])?>
    <?= $this->Form->button(__('Reset Password'), ['id' => 'reset_button']) ?>
    <table cellpadding="0" cellspacing="0" style="word-break:break-word">
        <thead>
            <tr>
                <th width="5%">
                  <input type="checkbox" class="checkall"  name="checkall" value="all" />
                </th>
                <th width="5%"><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th><?= $this->Paginator->sort('full_name') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('birthday') ?></th>
                <th><?= $this->Paginator->sort('role') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td class="select"><?= $this->Form->checkbox($user->id) ?></td>
                <td><?= $this->Number->format($user->id) ?></td>
                <td><?= $this->Html->link($user->username, ['action' => 'view', $user->id]) ?></td>
                <td><?= h($user->full_name) ?></td>
                <td><?= h($user->email) ?></td>
                <td><?= h($user->birthday) ?></td>
                <?php if ($user->role): ?>
                  <td>Admin</td>
                <?php else: ?>
                  <td>User</td>
                <?php endif; ?>
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                    &nbsp;&nbsp;
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

    <?=$this->Form->end()?>
</div>
