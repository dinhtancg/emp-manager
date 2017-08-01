<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Employees Management System ';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>
    <?php echo $this->Html->script('https://code.jquery.com/jquery-3.2.1.min.js') ?>
    <?= $this->Html->script('ems.js') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('ems.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
              <h1><a href="">EMS</a></h1>
            </li>

        </ul>
        <?php if (!$this->request->session()->read('Auth.User.id')): ?>
        <?php else: ?>
          <?php if ($this->request->session()->read('Auth.User.role') == true): ?>
            <ul class="large-2 medium-3 columns">
              <li class="name">
                  <h1> <?= $this->Html->link(__('Employees'),
                   ['prefix'=>'admin', 'controller'=> 'Users', 'action'=>'index']) ?></h1>
              </li>
            </ul>
            <ul class="large-2 medium-3 columns">
              <li class="name">
                  <h1> <?= $this->Html->link(__('Departments'),
                   ['prefix'=>'admin', 'controller'=> 'Departments', 'action'=>'index']) ?></h1>
              </li>
            </ul>
          <?php else: ?>
            <ul class="large-2 medium-3 columns">
              <li class="name">
                  <h1> <?= $this->Html->link(__('Departments'),
                   ['prefix'=>false, 'controller'=> 'Departments', 'action'=>'index']) ?></h1>
              </li>
            </ul>
          <?php endif; ?>
        <?php endif; ?>
        <section class="top-bar-section">
            <ul class="right">
              <?php if ($this->request->session()->read('Auth.User.id')): ?>
                <li>
                <div class="dropdown">
                    <?= $this->Html->link($this->request->session()->read('Auth.User.username'), ['prefix'=> false, 'controller' => 'Users','action'=> 'me'], ['class' => 'dropbtn']) ?>
                    <div class="dropdown-content">
                      <?= $this->Html->link('Edit Profile', ['controller'=>'Users', 'action'=>'edit', 'prefix' => false]) ?>
                      <?= $this->Html->link('Change Password', ['controller'=>'Users', 'action'=>'changePassword', 'prefix' => false]) ?>
                    </div>
                  </div>
                </li>
                <li><?= $this->Html->link('Logout', ['controller'=>'users', 'action'=>'logout', 'prefix' => false]) ?></li>
              <?php else: ?>
                <li><a target="_blank" href="/users/login">Login</a></li>
              <?php endif; ?>
            </ul>
        </section>
    </nav>
    <?= $this->Flash->render() ?>
    <section class="container clearfix">

        <?= $this->fetch('content') ?>
    </section>
    <footer>
    </footer>
</body>
</html>
