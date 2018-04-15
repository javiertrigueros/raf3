<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>
<?php use_helper("a") ?>

<div class="post entry clearfix latest">
   
    <?php include_component('arMedia','showBlogItemImage',
            array('hasToPopulate' => false,
                  'aBlogItem' => $a_blog_post)); ?> 
    
    <h3 class="title">
       
         <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
         <?php if (!$a_blog_post['is_publish']): ?>
            <span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
         <?php endif ?>
    </h3>
    
    <p class="meta-info">
        <?php include_partial('aBlog/author', array('a_blog_post' => $a_blog_post)) ?>
        <?php echo aDate::pretty($a_blog_post['published_at']); ?>
        <?php include_partial('aBlog/categories', array('aBlogItem' => $a_blog_post)) ?>
        <?php include_partial('aBlog/tags', array('aBlogItem' => $a_blog_post)) ?>
    </p>
    
    <?php if (sfConfig::get('app_aBlog_excerpts_show')): ?>
        <?php include_partial('aBlog/excerptTemplate', array('a_blog_post' => $a_blog_post, 'edit' => false )) ?>
    <?php else: ?>
        <?php // Standard slot choices, minus aBlog and aEvent. Pass in the options to edit the right virtual page ?>
        <?php // Events cannot have blog slots and vice versa, otherwise they could recursively point to each other ?>
        <?php include_component('a', 'standardArea', array('name' => 'blog-body', 'edit' => false, 'toolbar' => 'main', 'slug' => $a_blog_post->Page->slug, 'width' => sfConfig::get('app_aBlog_media_width', 480), 'minusSlots' => array('aBlog', 'aEvent'))) ?>
     <?php endif ?>
    
    <?php echo link_to('<span>'.__('Read More',null,'blog').'</span>', 'a_blog_post', $a_blog_post, array('class' => 'more')) ?>
    

</div> <!-- end .post-->


