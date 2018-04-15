<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
?>

<?php use_helper("a") ?>

<?php $catClass = ""; foreach ($a_event->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>

<div class="post entry clearfix latest">
   
    <?php include_component('arMedia','showBlogItemImage',
            array('hasToPopulate' => false,
                  'aBlogItem' => $a_event)); ?> 
    
    <h3 class="title">
       
         <?php echo link_to($a_event->getTitle(), 'a_event_post', $a_event) ?>
         <?php if (!$a_event['is_publish']): ?>
            <span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
         <?php endif ?>
    </h3>
    
    <p class="meta-info">
        <?php include_partial('aBlog/author', array('a_blog_post' => $a_event)) ?>
        <?php echo aDate::pretty($a_event['published_at']); ?>
        <?php include_partial('aBlog/categories', array('aBlogItem' => $a_event)) ?>
        <?php include_partial('aBlog/tags', array('aBlogItem' => $a_event)) ?>
    </p>
    
    <?php if (sfConfig::get('app_aBlog_excerpts_show')): ?>
        <?php include_partial('aBlog/excerptTemplate', array('a_blog_post' => $a_event, 'edit' => false )) ?>
    <?php else: ?>
        <?php // Standard slot choices, minus aBlog and aEvent. Pass in the options to edit the right virtual page ?>
        <?php // Events cannot have blog slots and vice versa, otherwise they could recursively point to each other ?>
        <?php include_component('a', 'standardArea', array('name' => 'blog-body', 'edit' => false, 'toolbar' => 'main', 'slug' => $a_event->Page->slug, 'width' => sfConfig::get('app_aBlog_media_width', 480), 'minusSlots' => array('aBlog', 'aEvent'))) ?>
     <?php endif ?>
    
    <?php echo link_to('<span>'.__('Read More',null,'blog').'</span>', 'a_event_post', $a_event, array('class' => 'more')) ?>
    

</div> <!-- end .post-->

<?php /*
<div class="a-blog-item event <?php echo $a_event->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?> clearfix">
  <?php if($a_event->userHasPrivilege('edit')): ?>
    <ul class="a-ui a-controls a-blog-post-controls">
        <li>                                         
            <?php echo a_button(a_('Edit'), url_for('@ar_blog_event_edit?id='.$a_event->getId()), array('icon','a-edit','lite','alt','no-label')) ?>
	</li>
    </ul>
  <?php endif ?>
 <?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event, 'edit' => false)) ?>
</div> */ ?>