<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
  $type = $aBlogItem->getType();
?>
  <?php $categoriesCount = count($aBlogItem->getCategories()); ?>
  <?php if ($categoriesCount > 0): ?>
  <div id="a-blog-item-categories-list" class="a-blog-item-tags tags">
      <span class="a-blog-item-categories-label"><?php echo __('Categories:',array(),'blog') ?></span>
      <span id="a-blog-item-categories">
           <?php foreach ($aBlogItem->getCategories() as $cat): ?>
                <?php if ($categoriesCount == 1): ?>
                    <?php echo $cat->getName(); ?>
                <?php else: ?>
                    <?php echo $cat->getName(); ?>,&nbsp;
                <?php endif ?>
                <?php $categoriesCount--; ?>
           <?php endforeach ?>
      </span>
  </div>
  <?php else: ?>
  <div id="a-blog-item-categories-list" class="a-blog-item-tags tags hide">
      <span class="a-blog-item-categories-label"><?php echo __('Categories:',array(),'blog') ?></span>
      <span id="a-blog-item-categories"></span>
  </div>
  <?php endif ?>
 <?php $tagCount = count($aBlogItem->getTagObjects()); ?>
  <?php if ($tagCount > 0): ?>
  <div id="a-blog-item-tag-list" class="a-blog-item-tags tags">
	<span id="a-blog-item-tags-label"><?php echo __('Tags:',array(),'blog') ?></span>
	<span id="a-blog-item-tags">
            <?php foreach ($aBlogItem->getTagObjects() as $tag): ?>
                <?php if ($tagCount == 1): ?>
                    <?php echo $tag->getName(); ?>
                <?php else: ?>
                    <?php echo $tag->getName(); ?>,&nbsp;
                <?php endif ?>
                <?php $tagCount--; ?>
            <?php endforeach ?>
        </span>
  </div>
  <?php else: ?>
  <div id="a-blog-item-tag-list" class="a-blog-item-tags tags hide">
	<span id="a-blog-item-tags-label"><?php echo __('Tags:',array(),'blog') ?></span>
        <span id="a-blog-item-tags"></span>
  </div>
  <?php endif ?>