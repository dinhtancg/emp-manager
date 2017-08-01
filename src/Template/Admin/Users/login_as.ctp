
<div class="users content large-5 medium-7" style="margin: auto;">

    <h1>You are Admin now. Please choose role you wanna login as</h1>

    <div class=" large-6 medium-6" style="float: left;">
      <?= $this->Html->link("User", ['prefix'=> false, 'controller' => 'Users','action'=> 'me'], ['class' => 'button']) ?>
    </div>
    <div class=" large-6 medium-6" style="float: right;">
      <?= $this->Html->link("Admin", ['prefix'=> 'admin', 'controller' => 'Users','action'=> 'index'], ['class' => 'button']) ?>
    </div>

</div>
