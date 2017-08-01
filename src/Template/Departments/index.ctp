<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('My profile'), ['controller' => 'Users', 'action' => 'me']) ?></li>
        <?php if ($this->request->session()->read('Auth.User.role')): ?>
          <li><?= $this->Html->link(__('Switch role'), ['prefix'=>'admin', 'controller' => 'Users', 'action' => 'loginAs']) ?></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="departments index large-5 medium-7 columns content" id="form">
    <h3><?= __('Departments') ?></h3>
    <div id="recperpage">
        <?=$this->Form->create(null, [
        'url' => ['controller' => 'Departments', 'action' => 'index'],
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
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('name') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= $this->Html->link($department->name, ['action' => 'view', $department->id]) ?></td>

            </tr>
            <?php endforeach; ?>
        </tbody>
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
