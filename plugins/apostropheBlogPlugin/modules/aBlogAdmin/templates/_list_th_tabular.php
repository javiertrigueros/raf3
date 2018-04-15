	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-column-title">
      <span class="a-simple-title">
          <?php echo __('Title', array(), 'apostrophe') ?>
      </span>
  
    
  <?php if ('title' == $sort[0]): ?>

		<?php ($sort[1] == 'asc')? $sortLabel = '<span class="icon">'.__('Descending', array(), 'apostrophe').'</span>': $sortLabel = '<span class="icon">'.__('Ascending', array(), 'apostrophe').'</span>'; ?>
    <?php ($sort[1] == 'asc')? $miniLabel = __('Descending', array(), 'apostrophe'): $miniLabel = __('Ascending', array(), 'apostrophe'); ?>
    <?php echo link_to(
			$sortLabel,
			'aBlogAdmin/index?sort=title&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'), 
			array('class' => 'a-btn flag flag-right a-sort-arrow no-bg no-label icon alt sorting '.$sort[1].(($sort[1] == 'asc') ? ' a-arrow-up' : ' a-arrow-down'), 'title' => $miniLabel)) 
		?>
		
    <?php else: ?>

    <?php echo link_to(
            '<span class="icon"></span>'.__('Ascending', array(), 'apostrophe'),
            'aBlogAdmin/index?sort=title&sort_type=asc', 
            array('class' => 'a-btn flag flag-right a-sort-arrow not-sorting no-bg no-label icon a-arrow-up alt asc', 'title' => __('Ascending', array(), 'a-admin')))
    ?>
		
  <?php endif; ?>

  	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-foreignkey a-column-author_id">
          <ul class="a-multi-title">
      <li><a href="#" class="a-btn a-sort-label alt"><?php echo __('Author', array(), 'apostrophe') ?></a>
        <div class="filternav author_id">
          <hr/>
          <?php include_partial('aBlogAdmin/list_th_dropdown', array('filters' => $filters, 'name' => 'author_id'  )) ?>    
            </div>
      </li>
    </ul>
  
    
  <?php if ('author_id' == $sort[0]): ?>

		<?php ($sort[1] == 'asc')? $sortLabel = '<span class="icon">'.__('Descending', array(), 'apostrophe').'</span>': $sortLabel = '<span class="icon">'.__('Ascending', array(), 'apostrophe').'</span>'; ?>
    <?php ($sort[1] == 'asc')? $miniLabel = __('Descending', array(), 'apostrophe'): $miniLabel = __('Ascending', array(), 'apostrophe'); ?>
    <?php echo link_to(
			$sortLabel,
			'aBlogAdmin/index?sort=author_id&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'), 
			array('class' => 'a-btn flag flag-right a-sort-arrow no-bg no-label icon alt sorting '.$sort[1].(($sort[1] == 'asc') ? ' a-arrow-up' : ' a-arrow-down'), 'title' => $miniLabel)) 
		?>
		
    <?php else: ?>

    <?php echo link_to(
        '<span class="icon"></span>'.__('Ascending', array(), 'apostrophe'),
        'aBlogAdmin/index?sort=author_id&sort_type=asc', 
	array('class' => 'a-btn flag flag-right a-sort-arrow not-sorting no-bg no-label icon a-arrow-up alt asc', 'title' => __('Ascending', array(), 'a-admin')))
    ?>
		
  <?php endif; ?>

  	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-column-tags_list">
          <ul class="a-multi-title">
            <li>
                <a href="#" class="a-btn a-sort-label alt"><?php echo __('Tags', array(), 'apostrophe') ?></a>
                <div class="filternav tags_list">
                    <hr/>
                    <?php include_partial('aBlogAdmin/list_th_tags_list_dropdown', array('filters' => $filters, 'name' => 'tags_list'  )) ?>
                </div>
            </li>
          </ul>
  	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-manykey a-column-categories_list">
          <ul class="a-multi-title">
      <li><a href="#" class="a-btn a-sort-label alt"><?php echo __('Categories', array(), 'apostrophe') ?></a>
        <div class="filternav categories_list">
          <hr/>
          <?php include_partial('aBlogAdmin/list_th_dropdown', array('filters' => $filters, 'name' => 'categories_list'  )) ?>    
            </div>
      </li>
    </ul>
  
  	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-column-status">
          <ul class="a-multi-title">
      <li><a href="#" class="a-btn a-sort-label alt"><?php echo __('Status', array(), 'apostrophe') ?></a>
        <div class="filternav status">
          <hr/>
          <br />
        </div>
      </li>
    </ul>
  
  	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-date a-column-published_at">
      <span class="a-simple-title"><?php echo __('Date', array(), 'apostrophe') ?></span>
  
    
  <?php if ('published_at' == $sort[0]): ?>

		<?php ($sort[1] == 'asc')? $sortLabel = '<span class="icon">'.__('Descending', array(), 'apostrophe').'</span>': $sortLabel = '<span class="icon">'.__('Ascending', array(), 'apostrophe').'</span>'; ?>
    <?php ($sort[1] == 'asc')? $miniLabel = __('Descending', array(), 'apostrophe'): $miniLabel = __('Ascending', array(), 'apostrophe'); ?>
    <?php echo link_to(
			$sortLabel,
			'aBlogAdmin/index?sort=published_at&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'), 
			array('class' => 'a-btn flag flag-right a-sort-arrow no-bg no-label icon alt sorting '.$sort[1].(($sort[1] == 'asc') ? ' a-arrow-up' : ' a-arrow-down'), 'title' => $miniLabel)) 
		?>
		
    <?php else: ?>

    <?php echo link_to(
        '<span class="icon"></span>'.__('Ascending', array(), 'apostrophe'),
        'aBlogAdmin/index?sort=published_at&sort_type=asc', 
	array('class' => 'a-btn flag flag-right a-sort-arrow not-sorting no-bg no-label icon a-arrow-up alt asc', 'title' => __('Ascending', array(), 'a-admin')))
    ?>
		
  <?php endif; ?>

  	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
