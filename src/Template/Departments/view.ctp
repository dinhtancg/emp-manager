<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Export'), ['controller' => 'Departments', 'action' => 'export', $department->id]) ?></li>
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
    </table>
    <div class="related">
        <h4><?= __('Related Employees') ?></h4>
        <?php if (!empty($department->users)): ?>
        <table cellpadding="0" cellspacing="0" style="word-break:break-word">
            <tr>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('DayOfBirth') ?></th>
                <th><?= $this->Paginator->sort('avatar') ?></th>
                <th><?= $this->Paginator->sort('position') ?></th>
            </tr>
            <?php foreach ($department->users as $users): ?>
            <tr>
                <?php if ($loggedUser->isManager($loggedUser->id, $department->id)): ?>
                  <td><?= $this->Html->link($users->username, ['controller' => 'Users', 'action' => 'view', $users->id]) ?></td>
                <?php else: ?>
                  <td><?= h($users->username) ?></td>
                <?php endif; ?>

                <td><?= h($users->email) ?></td>
                <td><?= h($users->dob) ?></td>
                <td><img src="<?= '/img/uploads/'.$users->avatar?>" alt="Avatar" width="50px" height="50px"></td>
                <?php if ($users->isManager($users->id, $department->id)): ?>
                  <td>Manager</td>
                <?php else: ?>
                  <td>Employees</td>
                <?php endif; ?>
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
