<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Department'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="departments index large-9 medium-8 columns content">
    <h3><?= __('Departments') ?></h3>
    <?= $this->Form->create(null, [
           'type' => 'get',
           'url' => ['controller' => 'Departments', 'action' => 'index'],
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
    <table cellpadding="0" cellspacing="0">
            <tr>
                <th width="5%"><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= __('Number Employees')?></th>
                <th><?= __('Manager')?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= $this->Number->format($department->id) ?></td>
                <td><?= $this->Html->link($department->name, ['action' => 'view', $department->id]) ?></td>
                <td><?= $department->countNumberEmp($department->id)?></td>
                <td><?= $department->listManager($department->id)?></td>
                <td><?= h($department->created) ?></td>
                <td><?= h($department->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $department->id]) ?>
                    &nbsp;&nbsp;
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $department->id], ['confirm' => __('Are you sure you want to delete # {0}?', $department->id)]) ?>
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
</div>
