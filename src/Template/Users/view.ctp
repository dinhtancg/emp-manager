<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <h2>Welcome</h2>
        <li><?=  $this->Html->link('My Profile', array('controller' => 'users', 'action' => 'index')); ?></li>

    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= 'Profile Of '.h($user->username) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Department') ?></th>
            <td><?= $user->has('department_id') ? $this->Html->link($user->department->name, ['controller' => 'Departments', 'action' => 'view', $user->department->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Job')?></th>
            <td><?= h($user->job)?></td>
        </tr>
    </table>
</div>
