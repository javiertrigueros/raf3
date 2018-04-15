<?php slot('sf_admin.current_header') ?>
<th class="a-admin-text a-column-title">
    <span class="a-simple-title">
          <?php echo __('Author', array(), 'blog') ?>
    </span>
    <?php if ('author' === $sort[0]): ?>
	<?php ($sort[1] == 'asc')? $sortLabel = '<span class="icon">'.__('Descending', array(), 'apostrophe').'</span>': $sortLabel = '<span class="icon">'.__('Ascending', array(), 'apostrophe').'</span>'; ?>
        <?php ($sort[1] == 'asc')? $miniLabel = __('Descending', array(), 'apostrophe'): $miniLabel = __('Ascending', array(), 'apostrophe'); ?>
        <?php echo link_to(
			$sortLabel,
			'arCommentAdmin/index?sort=author&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'), 
			array('class' => 'a-btn flag flag-right a-sort-arrow no-bg no-label icon alt sorting '.$sort[1].(($sort[1] == 'asc') ? ' a-arrow-up' : ' a-arrow-down'), 'title' => $miniLabel)) 
		?>
        <?php else: ?>
        <?php echo link_to(
            '<span class="icon"></span>'.__('Ascending', array(), 'apostrophe'),
            'arCommentAdmin/index?sort=author&sort_type=asc', 
            array('class' => 'a-btn flag flag-right a-sort-arrow not-sorting no-bg no-label icon a-arrow-up alt asc', 'title' => __('Ascending', array(), 'a-admin')))
        ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
<?php slot('sf_admin.current_header') ?>
<th class="a-admin-text a-column-comment">
  <span class="a-simple-title">
          <?php echo __('Comment', array(), 'blog') ?>
  </span>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
<?php slot('sf_admin.current_header') ?>
<th class="a-admin-text a-column-comment-approved">
    <span class="a-simple-title">
        <?php echo __('Status', array(), 'blog') ?>
    </span>
    <?php if ('comment_approved' === $sort[0]): ?>
        <?php ($sort[1] == 'asc')? $sortLabel = '<span class="icon">'.__('Descending', array(), 'apostrophe').'</span>': $sortLabel = '<span class="icon">'.__('Ascending', array(), 'apostrophe').'</span>'; ?>
        <?php ($sort[1] == 'asc')? $miniLabel = __('Descending', array(), 'apostrophe'): $miniLabel = __('Ascending', array(), 'apostrophe'); ?>
        <?php echo link_to(
			$sortLabel,
			'arCommentAdmin/index?sort=comment_approved&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'), 
			array('class' => 'a-btn flag flag-right a-sort-arrow no-bg no-label icon alt sorting '.$sort[1].(($sort[1] == 'asc') ? ' a-arrow-up' : ' a-arrow-down'), 'title' => $miniLabel)) 
		?>
    <?php else: ?>
        <?php echo link_to(
            '<span class="icon"></span>'.__('Ascending', array(), 'apostrophe'),
            'arCommentAdmin/index?sort=comment_approved&sort_type=asc', 
            array('class' => 'a-btn flag flag-right a-sort-arrow not-sorting no-bg no-label icon a-arrow-up alt asc', 'title' => __('Ascending', array(), 'a-admin')))
        ?>
    <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>
<?php slot('sf_admin.current_header') ?>
<th class="a-admin-text a-column-created-at">
    <span class="a-simple-title">
        <?php echo __('Created at', array(), 'blog') ?>
    </span>
    <?php if ('created_at' === $sort[0]): ?>
        <?php ($sort[1] == 'asc')? $sortLabel = '<span class="icon">'.__('Descending', array(), 'apostrophe').'</span>': $sortLabel = '<span class="icon">'.__('Ascending', array(), 'apostrophe').'</span>'; ?>
        <?php ($sort[1] == 'asc')? $miniLabel = __('Descending', array(), 'apostrophe'): $miniLabel = __('Ascending', array(), 'apostrophe'); ?>
        <?php echo link_to(
			$sortLabel,
			'arCommentAdmin/index?sort=created_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'), 
			array('class' => 'a-btn flag flag-right a-sort-arrow no-bg no-label icon alt sorting '.$sort[1].(($sort[1] == 'asc') ? ' a-arrow-up' : ' a-arrow-down'), 'title' => $miniLabel)) 
		?>
    <?php else: ?>
        <?php echo link_to(
            '<span class="icon"></span>'.__('Ascending', array(), 'apostrophe'),
            'arCommentAdmin/index?sort=created_at&sort_type=asc', 
            array('class' => 'a-btn flag flag-right a-sort-arrow not-sorting no-bg no-label icon a-arrow-up alt asc', 'title' => __('Ascending', array(), 'a-admin')))
        ?>
    <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>