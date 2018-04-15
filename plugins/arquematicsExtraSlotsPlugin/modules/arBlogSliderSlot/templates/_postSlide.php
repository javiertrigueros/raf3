<?php

  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
?>
<?php // Yes, catching exceptions in templates is unusual, but if there is no blog page on ?>
<?php // the site it is not possible to generate some of the links. That can kill the home page, ?>
<?php // so we must address it. Someday it might be better to do less in the template and ?>
<?php // generate the various URLs in component code rather than partial code ?>
<?php try { ?>

<?php if($aBlogPost->hasMedia()): ?>
<li class="slide">
   <div class="featured-top-shadow"></div>
     <?php  include_component('arMedia', 'showImage', array(
  	  //'mediaItem' => $aBlogPost->getMediaForArea('blog-body', 'image', 1),
          'mediaItem' => $aBlogPost->getImage(),
  	  'width' => $options['slideshowOptions']['width'],
  	  'height' => $options['slideshowOptions']['height'],
          'resizeType' => $options['slideshowOptions']['resizeType']
  	  )) ?>
    
    <div class="featured-bottom-shadow"></div>
    <div class="featured-description">
        <div class="feat_desc">
            <p class="meta-info"><?php echo a_('Posted on %date%',array('%date%' => aDate::medium($aBlogPost['published_at']))) ?></p>
            <h2 class="featured-title"><?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?></h2>
            
            <p><?php echo aHtml::simplify($aBlogPost->getRichTextForArea('blog-body', $options['excerptLength']), array('allowedTags' => '<a><em><strong>'))  ?></p>
	</div>
        <?php echo link_to(a_('Read More'), 'a_blog_post', $aBlogPost, array('class' => 'readmore')) ?>
    </div> <!-- end .description -->
</li><!-- end .slide -->
<?php endif ?>
  
  
<?php } catch (Exception $e) { 
  echo '<h3>'.__('You must have a blog page somewhere on your site to use blog slots.', null, 'arquematics').'</h3>';
 } ?>