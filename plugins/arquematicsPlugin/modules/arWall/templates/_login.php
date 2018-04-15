<?php $isBlogAdmin = isset($isBlogAdmin) ? $sf_data->getRaw('isBlogAdmin') : false; ?> 
<?php if ($sf_user->isAuthenticated()): ?>
  <div class="btn-group pull-right">
         <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
           <i class="icon-cog"></i><?php echo $sf_user->getGuardUser()->getProfile()->getFirstLast() ?>
           <span class="caret"></span>
         </a>
         <ul class="dropdown-menu">
          <?php if ($isBlogAdmin): ?>
            <li><a id="admin-cms" href="#"><?php echo __('Admin CMS', null, 'wall'); ?></a></li>
            <li class="divider"></li>
          <?php endif; ?>
           
           <li><a href="<?php echo url_for('@user_list') ?>"><?php echo __('Configure lists', null, 'wall'); ?></a></li>
           <li class="divider"></li>
           <li><a href="<?php echo url_for('@user_list_friends') ?>"><?php echo __('Configure subscribers', null, 'wall'); ?></a></li>
           <li class="divider"></li>
           <li><a href="<?php echo url_for('@user_profile?username='.$sf_user->getUsername())  ?>"><?php echo __('Profile', null, 'profile'); ?></a></li>
           <li class="divider"></li>
           <li><?php echo link_to(__('Log Out', null, 'apostrophe'), sfConfig::get('app_a_actions_logout', 'sf_guard_signout'), array()) ?></li>
         </ul>
  </div>
 <?php if ($isBlogAdmin): ?>
   <?php include_partial('arMenuAdmin/sidrAdminMenu') ?> 
 <?php endif; ?>

<?php endif ?>