<?php $aUserProfileFilter = isset($aUserProfileFilter) ? $sf_data->getRaw('aUserProfileFilter') : false; ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/wall/arquematics.search.js"); ?>
<?php include_js_call('arWall/jsSearch'); ?>
<li>
   <form id="form-search" class="navbar-form pull-left" action="<?php echo url_for('@search_users_byname_auto?username='.$aUserProfile->getUsername()); ?>"  method="POST">
         <div class="input-group">
               <?php echo $form->renderHiddenFields() ?>
               <?php echo $form['search']->render(array('value' => !$aUserProfileFilter?'':$aUserProfileFilter->getFirstLast(), 'autocomplete' => 'off', 'placeholder' => a_('Search'), 'class' => 'form-control')); ?>
               <span class="form-search navbar-icon cmd-search">
                    <i class="fa fa-search"></i>
               </span>
      </div>
  </form>
</li>