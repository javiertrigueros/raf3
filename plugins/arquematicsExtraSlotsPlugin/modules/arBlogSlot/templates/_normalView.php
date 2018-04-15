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
  
  $title_head = isset($title_head) ? $sf_data->getRaw('title_head') : '';
?>
<?php use_helper('a') ?>
<?php use_helper('arBlog') ?>
<?php use_javascript("/apostropheBlogPlugin/js/aBlog.js"); ?>
<?php if ($editable): ?>
	<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page, 'label' => a_get_option($options, 'editLabel', a_('Choose Posts')))) ?>
<?php endif ?>

<?php if (count($aBlogPosts) > 0): ?>

  <div class="recent-from et-recent-top <?php echo $options['class'] ?>">
      <h4 class="main-title"><?php echo $title_head ?></h4>
      <div class="recent-content">
          
          <?php foreach ($aBlogPosts as $aBlogPost): ?>
          <div class="block-post clearfix">
            <div class="thumb">
                <a href="<?php echo url_for('@a_blog_show_post?slug='.$aBlogPost->getSlug()) ?>">
                    <?php  include_component('arMedia', 'showImage', array(
                        'mediaItem' => $aBlogPost->getImage(),
                        'width' => 40,
                        'height' => 40,
                        'resizeType' => 'c'
                    )) ?>
                   <span class="overlay"></span>
		</a>
            </div> <!-- end .post-thumbnail -->
				
            <h3 class="title">
                <a href="<?php echo url_for('@a_blog_show_post?slug='.$aBlogPost->getSlug()) ?>"><?php echo $aBlogPost->getTitle() ?></a>
            </h3>
            <p class="meta-info">
                <?php echo a_('Posted on %date%',array('%date%' => aDate::medium($aBlogPost['published_at']))); ?>
            </p>
         </div> <!-- end .block-post -->
         <?php endforeach ?>
          
        
        <?php if ((!$handSelected) && $aCategory): ?>

            <?php echo link_to('<span>'.a_('More From %cat%',array('%cat%' => ucfirst($aCategory->getName()))).'</span>', aUrl::addParams('aBlog'.'/index', array('cat' => $aCategory->slug)),array('class' => 'more')) ?>
        
        <?php endif; ?>
	
    </div> <!-- end .recent-content -->
  </div> <!-- end .recent-from -->

<?php else: ?>
  <div class="recent-from et-recent-top <?php echo $options['class'] ?>">
      <h4 class="main-title"><?php echo $title_head ?></h4>
      <div class="recent-content">
          <?php if (aTools::isPotentialEditor()): ?>
          
          <div class="block-post clearfix">	
            <h3 class="title"><?php echo a_('There are no blog posts that match the criteria you have specified.') ?></h3>
         </div> <!-- end .block-post -->
          
          <?php endif ?>
      </div> <!-- end .recent-content -->
  </div> <!-- end .recent-from -->
  
<?php endif ?>