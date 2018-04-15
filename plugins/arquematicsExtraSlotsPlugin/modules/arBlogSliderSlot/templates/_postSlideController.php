<?php

  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $active = isset($active) ? $sf_data->getRaw('active') : false;
?>
<?php // Yes, catching exceptions in templates is unusual, but if there is no blog page on ?>
<?php // the site it is not possible to generate some of the links. That can kill the home page, ?>
<?php // so we must address it. Someday it might be better to do less in the template and ?>
<?php // generate the various URLs in component code rather than partial code ?>
<?php try { ?>

<?php if($aBlogPost->hasMedia()): ?>
<li>
    <div class="controller">
        <a href="#" <?php if($active){ echo 'class="active"'; } ?>>
          <?php  include_component('arMedia', 'showImage', array(
  	  //'mediaItem' => $aBlogPost->getMediaForArea('blog-body', 'image', 1),
          'mediaItem' => $aBlogPost->getImage(),
  	  'width' => 95,
  	  'height' => 54,
          'resizeType' => $options['slideshowOptions']['resizeType']
  	  )) ?>
          <span class="overlay"></span>
	</a>
    </div>
</li>

<?php endif ?>

<?php } catch (Exception $e) { 
  echo '<h3>'.__('You must have a blog page somewhere on your site to use blog slots.', null, 'arquematics').'</h3>';
 } ?>