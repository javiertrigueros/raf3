
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-name">
		  <?php if ('name' == $sort[0]): ?>
	    <?php echo link_to(__('Tag', array(), 'apostrophe'), '@a_tag_admin?sort=name&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?>
	    <?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'apostrophe'), 'title' => __($sort[1], array(), 'apostrophe'))) ?>
	  <?php else: ?>
	    <?php echo link_to(__('Tag', array(), 'apostrophe'), '@a_tag_admin?sort=name&sort_type=asc') ?>
	  <?php endif; ?>
		</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>

	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-tag a-admin-list-th-tag_aBlogPost">
		  <?php if ('tag_aBlogPost' == $sort[0]): ?>
	    <?php echo link_to(__('Blog', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aBlogPost&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?>
	    <?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'apostrophe'), 'title' => __($sort[1], array(), 'apostrophe'))) ?>
	  <?php else: ?>
	    <?php echo link_to(__('Blog', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aBlogPost&sort_type=desc') ?>
	  <?php endif; ?>
		</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>

	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-tag a-admin-list-th-tag_aEvent">
		  <?php if ('tag_aEvent' == $sort[0]): ?>
	    <?php echo link_to(__('Events', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aEvent&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?>
	    <?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'apostrophe'), 'title' => __($sort[1], array(), 'apostrophe'))) ?>
	  <?php else: ?>
	    <?php echo link_to(__('Events', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aEvent&sort_type=desc') ?>
	  <?php endif; ?>
		</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>

	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-tag a-admin-list-th-tag_aMediaItem">
		  <?php if ('tag_aMediaItem' == $sort[0]): ?>
	    <?php echo link_to(__('Media', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aMediaItem&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?>
	    <?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'apostrophe'), 'title' => __($sort[1], array(), 'apostrophe'))) ?>
	  <?php else: ?>
	    <?php echo link_to(__('Media', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aMediaItem&sort_type=desc') ?>
	  <?php endif; ?>
		</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>

	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-tag a-admin-list-th-tag_aPage">
		  <?php if ('tag_aPage' == $sort[0]): ?>
	    <?php echo link_to(__('Pages', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aPage&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc')) ?>
	    <?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'apostrophe'), 'title' => __($sort[1], array(), 'apostrophe'))) ?>
	  <?php else: ?>
	    <?php echo link_to(__('Pages', array(), 'apostrophe'), '@a_tag_admin?sort=tag_aPage&sort_type=desc') ?>
	  <?php endif; ?>
		</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
