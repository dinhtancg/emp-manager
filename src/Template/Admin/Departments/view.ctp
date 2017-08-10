<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="departments view large-9 medium-8 columns content">
    <h3><?= h($department->name) ?></h3>
    <table class="vertical-table large-6 medium-6">
        <tr>
            <th><?= __('Name') ?></th>
            <td><?= h($department->name) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($department->id) ?></td>
        </tr>
        <tr>
          <th><?= __('Number Employees')?></th>
          <td><?= $department->countNumberEmp($department->id) ?></td>
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
            'type' => 'get',
            'url' => ['controller' => 'Departments', 'action' => 'view', $department->id],
            'id'  => 'recordsPerPage',
            ])?>
        	  <?= $this->Form->select('limit',
                    [10=>10, 20=>20, 50=>50],
                    ['default' => $sessionLimit, 'onchange'=>'onSelectSubmit("recordsPerPage")']
                )
            ?>
            <?=$this->Form->end()?>
        </div>
        <hr>
        <?php if (!empty($users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th><?= $this->Paginator->sort('full_name') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('birthday') ?></th>
                <th><?= $this->Paginator->sort('avatar') ?></th>
                <th><?= __('Position') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $this->Html->link($user->username, ['controller' => 'Users', 'action' => 'view', $user->id]) ?></td>
                <td><?= h($user->full_name)?></td>
                <td><?= h($user->email) ?></td>
                <td><?= h($user->birthday)?></td>
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
                    <?php if ($user->isManager($user->id, $department->id)) {
                    ?>
                      <?= $this->Form->postLink(__('Down'), ['prefix'=>'admin','controller' => 'Departments', 'action' => 'manager', $department->id, $user->id], ['confirm' => __('Are you sure you want to DOWN user # {0} became EMPLOYEE ?', $user->username)]) ?>
                    <?php
                } else {
                    ?>
                    <?= $this->Form->postLink(__('Up'), ['prefix'=>'admin','controller' => 'Departments', 'action' => 'manager', $department->id, $user->id], ['confirm' => __('Are you sure you want to UP user # {0} became MANAGER?', $user->username)]) ?>
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
