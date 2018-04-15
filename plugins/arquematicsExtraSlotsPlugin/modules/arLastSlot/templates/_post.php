<?php

  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $excerptLength = (sfConfig::get('app_aBlog_excerpts_length')) ? sfConfig::get('app_aBlog_excerpts_length') : 30;
?>
<?php use_helper("a") ?>
<?php use_helper("arBlog") ?>
<div class="post entry clearfix latest">
			<div class="thumb">
                            <a href="<?php echo url_for('@a_blog_show_post?slug='.$aBlogItem->getSlug()) ?>">
                                 <?php  include_component('arMedia', 'showImage', array(
                                    'mediaItem' => $aBlogItem->getImage(),
                                    'width' => 130,
                                    'height' => 130,
                                    'resizeType' => 'c'
                                )) ?>
                                <span class="overlay"></span>
                            </a>
			</div> 	<!-- end .post-thumbnail -->
			
			<h3 class="title">
                            <a href="<?php echo url_for('@a_blog_show_post?slug='.$aBlogItem->getSlug()) ?>"><?php echo $aBlogItem->getTitle() ?></a>
                        </h3>
                        <p class="meta-info">
                           <?php echo a_('Posted by %author% on %date%',
                                        array('%date%' => aDate::medium($aBlogItem['published_at']),
                                            '%author%' => getAuthor($aBlogItem, true))) ?> 
                        </p>
                        
			
                        <p><?php echo aHtml::simplify($aBlogItem->getRichTextForArea('blog-body', $excerptLength), array('allowedTags' => '<a><em><strong>'))  ?></p>
			
                        <a href="<?php echo url_for('@a_blog_show_post?slug='.$aBlogItem->getSlug()) ?>" class="more"><span><?php echo a_('Read More') ?></span></a>
 </div> <!-- end .post-->