<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Departments'), ['controller' => 'Departments', 'action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="users view large-5 medium-7 columns content" id="form">
    <h3><?= 'Profile of '.h($user->username) ?></h3>
    <table class="vertical-table">
        <tr>
          <th><?= __('Avatar')?></th>
          <th><img src="<?= '/img/uploads/'.$user->avatar?>" alt="Avatar" width="50px" height="50px" style="float:right"></th>
        </tr>

        <tr>
            <th><?= __('Username') ?></th>
            <td><?= h($user->username) ?></td>
        </tr>
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Day Of Birth') ?></th>
            <td><?= h($user->dob) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Departments') ?></h4>
        <div id="recperpage">
            <?=$this->Form->create(null, [
            'url' => ['controller' => 'Users', 'action' => '$user', $user->id],
            'id'  => 'recordsPerPage',
            ])?>
        	  <?= $this->Form->select('recperpageval',
                    [5=>5, 25=>25, 50=>50],
                    ['default' => 5, 'onchange'=>'onSelectSubmit("recordsPerPage")']
                )
            ?>
            <?=$this->Form->end()?>
        </div>
        <hr>
        <?php if (!empty($departments)): ?>
          <table cellpadding="0" cellspacing="0">
              <tr>
                  <th><?= __('Name') ?></th>
              </tr>
              <?php foreach ($departments as $department): ?>
              <tr>
                  <td><?= $this->Html->link($department->name, ['controller' => 'Departments', 'action' => 'view', $department->id]) ?></td>
              </tr>
              <?php endforeach; ?>
          </table>
    <?php endif; ?>
    </div>
</div>
