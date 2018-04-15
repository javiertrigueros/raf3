<?php $realPage = aTools::getCurrentPage() ?>
<?php $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<div id="breadcrumbs">
    
	<a href="<?php echo $realPage->getUrl() ?>"><?php echo ucfirst($realPage->getTitle()) ?></a>
       
        <?php if (strlen($sf_params->get('cat'))): ?>
	  <?php $category = Doctrine::getTable('aCategory')->findOneBySlug($sf_params->get('cat')) ?>
	  <?php if ($category): ?>
            <span class="raquo">&nbsp;»&nbsp;</span>
            <?php echo ucfirst($category->getName()) ?>
	  <?php endif ?>
	<?php endif ?>
        <?php if ($aBlogItem): ?>
            <span class="raquo">&nbsp;»&nbsp;</span>
            <?php echo $aBlogItem->getTitle() ?>
        <?php endif ?>  
 </div> <!-- end #breadcrumbs -->