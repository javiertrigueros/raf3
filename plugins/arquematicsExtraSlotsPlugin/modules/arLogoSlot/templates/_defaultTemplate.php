<?php
  // Compatible with sf_escaping_strategy: true
  $dimensions = isset($dimensions) ? $sf_data->getRaw('dimensions') : null;
  $constraints = isset($constraints) ? $sf_data->getRaw('constraints') : null;
  $editable = isset($editable) ? $sf_data->getRaw('editable') : null;
  $item = isset($item) ? $sf_data->getRaw('item') : null;
  $itemId = isset($itemId) ? $sf_data->getRaw('itemId') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
  $embed = isset($embed) ? $sf_data->getRaw('embed') : null;
?>

<?php if ($item): ?>
	    <?php if ($options['url']): ?>
              <?php //echo $embed ?>
	      <?php echo content_tag('a', $embed, array('href' => $options['url'], 'class' => "a-button-link")) ?>
            <?php else: ?>
              <?php echo $embed ?>			
	    <?php endif ?>

    <?php if ($options['title']): ?>
     	
      	<?php if ($options['url']): ?>
            <?php echo content_tag('a', aHtml::entities($options['title']), array('href' => $options['url'], 'class' => "a-button-link")) ?>
	<?php else: ?>
            <?php echo aHtml::entities($options['title']) ?>
      	<?php endif ?>
     
    <?php endif ?>

    <?php if ($options['description']): ?>
      <p id="slogan"><?php echo $options['description'] ?></p>
    <?php endif ?>
      
<?php else: ?>
	
	<?php if ($options['image'] && (!strlen($options['url']))): ?>
		<?php include_partial('aImageSlot/placeholder', array('placeholderText' => a_("Create a Button"), 'options' => $options)) ?>
	<?php endif ?>
	
  <?php if ($options['defaultImage']): ?>
  	
            <?php // Corner case: they've set the link but are still using the default image ?>
            <?php $img = image_tag($options['defaultImage'], array('alt' => (($options['title']) ? aHtml::entities($options['title']) : ''))) ?>
            <?php if ($options['link']): ?>
                <?php echo content_tag('a', $img, array('href' => $options['url'])) ?>
            <?php else: ?>
		<?php echo $img ?>
            <?php endif ?>
      
            <?php if ($options['description']): ?>
                        <p id="slogan"><?php echo $options['description'] ?></p>
            <?php endif ?>
           
      
	<?php else: ?>
		<?php if ($options['link'] || $options['url']): ?>
                    <?php echo content_tag('a', (($options['title']) ? aHtml::entities($options['title']) : aHtml::entities($options['url'])), 
                                array('href' => $options['url'], 'class' => 'a-button-link')) ?>
                    
		    <?php if ($options['description']): ?>
                        <p id="slogan"><?php echo $options['description'] ?></p>
                    <?php endif ?>
                	
		<?php endif ?>
  <?php endif ?>

<?php endif ?>