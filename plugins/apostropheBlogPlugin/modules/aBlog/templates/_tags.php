<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
  $type = $aBlogItem->getType();
?>


<?php if (sfConfig::get('app_aBlog_showTagsForPosts')): ?>

    <?php if ((count($aBlogItem->getTags()) > 0)): ?>
	<span class="a-blog-item-tags-label"><?php echo a_('Tags') ?>:</span>
		<?php $i=1; foreach ($aBlogItem->getTags() as $tag): ?>
			<?php echo link_to($tag, aUrl::addParams((($type == 'post') ? 'aBlog' : 'aEvent' ).'/index', array('tag' => $tag))) ?><?php echo (($i < count($aBlogItem->getTags())) ? ', ':'')?>
		<?php $i++; endforeach ?>
    <?php endif ?>
<?php endif ?>