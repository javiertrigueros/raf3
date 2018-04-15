<?php
  // Compatible with sf_escaping_strategy: true
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $aBlogPosts = isset($aBlogPosts) ? $sf_data->getRaw('aBlogPosts') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $aCategory = isset($aCategory) ? $sf_data->getRaw('aCategory') : false;
  
  $handSelected = isset($handSelected) ? $sf_data->getRaw('handSelected') : false;
  
  $title_head = isset($title_head) ? $sf_data->getRaw('title_head') : false;
?>
<?php use_helper('a') ?>
<?php use_helper('arBlog') ?>
<?php use_javascript("/apostropheBlogPlugin/js/aBlog.js"); ?>
<?php if ($editable): ?>
	<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page, 'label' => a_get_option($options, 'editLabel', a_('Configure Recent')))) ?>
<?php endif ?>

<?php if (count($aBlogPosts) > 0): ?>

  <?php if ($title_head): ?>
  <h4 class="main-title"><?php echo $title_head ?></h4>
  <?php else: ?>
  <h4 class="main-title"><?php echo a_('Most Recent') ?></h4>
  <?php endif ?>
  
  
  <div id="entries">
      <?php foreach ($aBlogPosts as $aBlogItem): ?>
        <?php include_partial('arLastSlot/'.$aBlogItem->getType(), array('options' => $options, 'aBlogItem' => $aBlogItem)) ?>
      <?php endforeach ?>
  </div>

<?php else: ?>
  
  <?php if ($title_head): ?>
  <h4 class="main-title"><?php echo $title_head ?></h4>
  <?php else: ?>
  <h4 class="main-title"><?php echo a_('Most Recent') ?></h4>
  <?php endif ?>
  
  <div id="entries">
      
      <div class="post entry clearfix latest">
       <?php if (aTools::isPotentialEditor()): ?>
          <h3 class="title"><?php echo a_('There are no blog posts that match the criteria you have specified.') ?></h3>       
       <?php endif ?>
	
      </div> <!-- end .post-->
  </div>
<?php endif ?>