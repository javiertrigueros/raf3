<?php $isCMSAdmin = isset($isCMSAdmin) ? $sf_data->getRaw('isCMSAdmin') : false; ?> 
<?php $aUserProfile = isset($aUserProfile)? $sf_data->getRaw('aUserProfile'): null ?>

<li>
    <a href="<?php echo url_for('@user_profile?username='.$sf_user->getUsername())  ?>">
       <?php echo __('My Profile', null, 'arquematics') ?>
     </a>
</li>
<li class="divider"></li>
<li>
    <a href="<?php echo url_for('@user_list') ?>">
        <?php echo __('Lists', null, 'arquematics') ?>
    </a>
</li>
<li>
    <a href="<?php echo url_for('@user_list_friends') ?>">
        <?php echo __('Subscribers', null, 'arquematics') ?>
    </a>
</li>
<li class="divider"></li>
<li>
    <a id="documents-cmd" data-sidr_name="sidr-menu-documents" class="sidr-cmd ng-binding" href="<?php echo url_for('@laverna_doc') ?>">
        <i class="fa fa-files-o"></i>
        <?php echo __('Documents', null, 'arquematics') ?>
    </a>
</li>
<li>
    <a href="<?php echo url_for('@laverna_doc') ?>#/note/f/favorite">
        <i class="fa fa-star"></i>
        <?php echo __('Favourites', null, 'arquematics') ?>
    </a>
</li>
<li class="divider"></li>
<li>
    <a  href="<?php echo url_for('@laverna_doc') ?>#/note/f/trashed">
        <i class="fa fa-trash-o"></i>
        <?php echo __('Trash', null, 'arquematics') ?>
    </a>
</li>
<li class="divider"></li>
<li>
    <a href="<?php echo url_for('@user_profile_configure') ?>" class="ng-binding">
          <i class="dropdown-icon fa fa-cog"></i>
         <?php echo __('Config', null, 'arquematics') ?>
    </a>
</li>
<?php if ($isCMSAdmin): ?>
<li>
    <a id="admin-cms" data-sidr_name="sidr-menu-admin-cms" href="#admin-cms"  class="sidr-cmd ng-isolate-scope ng-binding">
        <?php echo __('Admin CMS', null, 'arquematics') ?>
    </a>
</li>
<?php endif; ?>
<li class="divider"></li>
<li>
    <a href="<?php echo url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout')) ?>">
        <i class="dropdown-icon fa fa-power-off"></i>
        <?php echo __('Log Out', null, 'arquematics') ?>
    </a>
</li>