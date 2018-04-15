<?php
  // Compatible with sf_escaping_strategy: true
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $aBlogPosts = isset($aBlogPosts) ? $sf_data->getRaw('aBlogPosts') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $id = $name.$page->id.$slot;
?>
<?php use_helper('a') ?>
<?php if ($editable): ?>
	<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page, 'label' => a_get_option($options, 'editLabel', a_('Choose Posts')))) ?>
<?php endif ?>

<?php if (count($aBlogPosts) > 0): ?>

<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/flexslider.css"); ?>

<?php use_javascript("/arquematicsExtraSlotsPlugin/js/jquery.fitvids.js"); ?>
<?php use_javascript("/arquematicsExtraSlotsPlugin/js/jquery.flexslider.js"); ?>

<?php use_javascript("/apostropheBlogPlugin/js/aBlog.js"); ?> 

<div id="<?php echo $id ?>" class="featured flexslider">
    <a id="left-arrow" href="#"><?php echo a_('Previous') ?></a>
    <a id="right-arrow" href="#"><?php echo a_('Next') ?></a>
    <ul class="slides">   
	<?php foreach ($aBlogPosts as $aBlogPost): ?>
		<?php include_partial('arBlogSliderSlot/postSlide', array('options' => $options, 'aBlogPost' => $aBlogPost, 'pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page)) ?>
	<?php endforeach ?>
    </ul>
 </div>

  <div id="<?php echo $id ?>-controllers" class="clearfix controllers">
      <ul>
          <?php $active = true; ?>
          <?php foreach ($aBlogPosts as $aBlogPost): ?>
            <?php include_partial('arBlogSliderSlot/postSlideController', array('active' => $active, 'options' => $options, 'aBlogPost' => $aBlogPost)) ?>
            <?php $active = false; ?>
          <?php endforeach ?>
      </ul>
      <div id="active_item"></div>
  </div><!-- end #controllers -->

  <?php include_js_call('arBlogSliderSlot/jsArBlogSliderSlot', array('id' => $id)); ?>
  
 

<?php else: ?>
  <?php if (aTools::isPotentialEditor()): ?>
	  <h4><?php echo a_('There are no blog posts that match the criteria you have specified.') ?></h4>    
  <?php endif ?>
<?php endif ?>