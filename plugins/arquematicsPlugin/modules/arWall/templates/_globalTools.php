<?php use_helper('a') ?>



<?php if ($sf_user->isAuthenticated()): ?>

 <div id="header" class="navbar navbar-fixed-top admin-bar">
   <div class="navbar-inner navbar-inner-overwrite">
     <div class="container-fluid">
       <?php include_partial("arWall/login"); ?> 
       <?php include_component('arGroup','showButtomRequest'); ?> 
     </div>
   </div>
 </div>

<?php endif ?>
