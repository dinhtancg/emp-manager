<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Welcome') ?></li>
    </ul>
</nav>
<div class="departments index large-5 medium-7 columns content" id="form">
    <h3><?= __('Departments') ?></h3>
    <div id="recperpage">
        <?=$this->Form->create(null, [
          'type' => 'get',
        'url' => ['controller' => 'Departments', 'action' => 'index'],
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
    <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= __('Number Employees')?></th>
                <th><?= __('Manager')?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
            </tr>
            <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= $this->Html->link($department->name, ['action' => 'view', $department->id]) ?></td>
                <td><?= $department->countNumberEmp($department->id) ?></td>
                <td><?= $department->listManager($department->id)?></td>
                <td><?= h($department->created) ?></td>
            </tr>
            <?php endforeach; ?>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <p><?= $this->Paginator->counter() ?></p>
        </ul>

    </div>
</div>
