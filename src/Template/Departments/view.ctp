<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Welcome') ?></li>
    </ul>
</nav>
<div class="departments view large-9 medium-8 columns content">
    <h3><?= h($department->name) .' Department'?></h3>
    <table class="vertical-table large-6 medium-6">

          <th><?= __('Number Employees')?></th>
          <td><?= $department->countNumberEmp($department->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($department->created) ?></td>
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
        <?=$this->Form->create(null, [
        'url' => ['controller' => 'Departments', 'action' => 'export-user', $department->id],
        'id'  => 'exportEmp',
        ])?>
        <table cellpadding="0" cellspacing="0" style="word-break:break-word">
            <tr>
                <th width="5%">
                  <input type="checkbox" class="checkall"  name="checkall" value="all" />
                </th>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th><?= $this->Paginator->sort('full_name') ?></th>
                <th><?= $this->Paginator->sort('email') ?></th>
                <th><?= $this->Paginator->sort('birthday') ?></th>
                <th><?= $this->Paginator->sort('avatar') ?></th>
                <th><?= __('Position') ?></th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td class="select"><?= $this->Form->checkbox($user->id) ?></td>
                <?php if ($loggedUser->isManager($loggedUser->id, $department->id)): ?>
                  <td><?= $this->Html->link($user->username, ['controller' => 'Users', 'action' => 'view', $user->id]) ?></td>
                <?php else: ?>
                  <td><?= h($user->username) ?></td>
                <?php endif; ?>
                <td><?= h($user->full_name) ?></td>
                <td><?= h($user->email) ?></td>
                <td><?= h($user->birthday) ?></td>
                <td><img src="<?= '/img/uploads/'.$user->avatar?>" alt="Avatar" width="50px" height="50px"></td>
                <?php if ($user->isManager($user->id, $department->id)): ?>
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
        <?= $this->Form->button(__('Export to csv')) ?>
        <?=$this->Form->end()?>
    <?php endif; ?>
    </div>

</div>
