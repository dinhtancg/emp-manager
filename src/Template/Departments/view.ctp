<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="departments view large-9 medium-8 columns content">
    <h3><?= h($department->name) ?></h3>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($department->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>

                <th><?= __('Username') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Job') ?></th>
                <?php if ($this->request->session()->read('Auth.User.job') == 'manager' && $this->request->session()->read('Auth.User.department_id') == $department->id): ?>
                    <th class="actions"><?= __('Actions') ?></th>
                <?php endif; ?>
            </tr>
            <?php foreach ($department->users as $users): ?>
            <tr>

                <td><?= h($users->username) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->job) ?></td>

                <?php if ($this->request->session()->read('Auth.User.job') == 'manager' && $this->request->session()->read('Auth.User.department_id') == $department->id): ?>
                  <td class="actions">
                      <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                  </td>
                <?php endif; ?>

            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
</div>
