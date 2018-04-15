<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<?php use_helper('a') ?>

<div id="a-admin-filters-container" class="hide a-ui form-filters <?php echo ($filtersActive && !$filtersNameActive) ? 'a-active' : '' ?>">

	  <form action="<?php echo url_for('a_user_admin_collection', array('action' => 'filter')) ?>" method="post" id="a-admin-filters-form">
                  <h3 class="a-admin-title"><?php echo __('Find Users by', null, 'apostrophe') ?></h3>
		  <?php if ($form->hasGlobalErrors()): ?>
		    <?php echo $form->renderGlobalErrors() ?>
		  <?php endif; ?>
		
	    <div class="a-admin-filters-fields">
                
                <div class="a-form-row" id="a-admin-filters-username">
                    <label for="sf_guard_user_filters_username"><?php echo __('Name', null, 'apostrophe') ?></label>
                    <div class="a-admin-filter-field">
                        <?php echo $form['username']->renderError() ?>
                        <?php echo $form['username']->render() ?>
                    </div>
                </div>
                
                <div class="a-form-row" id="a-admin-filters-is_active">
                    <label for="sf_guard_user_filters_is_active"><?php echo __('Is active', null, 'apostrophe') ?></label>
                    <div class="a-admin-filter-field">
                        <?php echo $form['is_active']->renderError() ?>
                        <?php echo $form['is_active']->render() ?>
                    </div>
                </div>
                
                <div class="a-form-row" id="a-admin-filters-is_super_admin">
                    <label for="sf_guard_user_filters_is_super_admin"><?php echo __('Is super admin', null, 'apostrophe') ?></label>
                    <div class="a-admin-filter-field">
                        <?php echo $form['is_super_admin']->renderError() ?>
                        <?php echo $form['is_super_admin']->render() ?>
                    </div>
                </div>
                <?php /*
                <div class="a-form-row" id="a-admin-filters-last_login">
                    <label for="sf_guard_user_filters_last_login"><?php echo __('Last login', null, 'apostrophe') ?></label>
                    <div class="a-admin-filter-field">
                        <?php echo $form['last_login']->renderError() ?>
                        <?php echo $form['last_login']->render() ?>
                    </div>
                </div>
                
                <div class="a-form-row" id="a-admin-filters-created_at">
                    <label for="sf_guard_user_filters_created_at"><?php echo __('Created at', null, 'apostrophe') ?></label>
                    <div class="a-admin-filter-field">
                        <?php echo $form['created_at']->renderError() ?>
                        <?php echo $form['created_at']->render() ?>
                    </div>
                </div>*/?>
                
                <div class="a-form-row" id="a-admin-filters-groups_list">
                    <label for="sf_guard_user_filters_groups_list"><?php echo __('Groups list', null, 'apostrophe') ?></label>
                    <div class="a-admin-filter-field">
                        <?php echo $form['groups_list']->renderError() ?>
                        <?php echo $form['groups_list']->render() ?>
                    </div>
                </div> 

                <?php echo $form->renderHiddenFields() ?>
			
		<ul class="a-ui a-controls">
			<li><button class="btn btn-primary"><?php echo __('Search',null,'apostrophe') ?></button></li>
			<?php /*
                        <li><?php echo link_to('<button class="btn btn-primary">%s</button>', 'a_user_admin_collection', array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post', 'class' => 'a-btn icon a-cancel alt')) ?></li>
                        */ ?>
                        <li><button data-url="<?php echo url_for('a_user_admin_collection' , array('action' => 'filter', '_reset' => '')) ?>" class="btn btn-default cmd-cancel"><?php echo __('Delete filter',null,'apostrophe') ?></button></li>
		</ul>
			
	    </div>
	  </form>

</div>
