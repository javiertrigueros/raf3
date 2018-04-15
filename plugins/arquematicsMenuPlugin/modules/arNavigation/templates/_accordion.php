<?php
  // Compatible with sf_escaping_strategy: true
  $ulClass = isset($ulClass) ? $sf_data->getRaw('ulClass') : null;
  $active = isset($active) ? $sf_data->getRaw('active') : null;
  $class = isset($class) ? $sf_data->getRaw('class') : null;
  $dragIcon = isset($dragIcon) ? $sf_data->getRaw('dragIcon') : null;
  $draggable = isset($draggable) ? $sf_data->getRaw('draggable') : null;
  $maxDepth = isset($maxDepth) ? $sf_data->getRaw('maxDepth') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  // Safe to pass to recursive invocations
  $escNav = $nav;
  $nav = isset($nav) ? $sf_data->getRaw('nav') : null;
  $nest = isset($nest) ? $sf_data->getRaw('nest') : null;
  $tabs = isset($tabs) ? $sf_data->getRaw('tabs') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  
  $isCMSAdmin = isset($isCMSAdmin) ? $sf_data->getRaw('isCMSAdmin') : false;
  
  $id = 'a-nav-'.$name.'-'.$nest;
  $id .= ($n) ? '-'.$n : '';
?>

<div class="a-area a-normal a-area-second-menu singleton arMenuSecundary a-area-editable clearfix">
    
<?php if ($isCMSAdmin): ?>
<ul class="a-ui a-controls clearfix a-area-controls a-slot-controls-moved">   
      <li id="cmd-edit" class="hide-ui" style="display: list-item;">
          <a class="a-btn icon a-media a-inject-actual-url a-js-choose-button" href="<?php echo url_for('ar_page_admin') ?>">
              <span class="icon"></span><?php echo a_('Edit') ?>
          </a>
      </li>
</ul>
<?php endif; ?>
<?php use_javascript("/arquematicsMenuPlugin/js/superfish.js"); ?>
<?php include_js_call('arNavigation/jsMainMenu', array('id' => 'a-nav-main-0')); ?>
<a id="cmd-movile-a-nav-main-0" href="#" class="mobile_nav closed"><?php echo a_('Navigation Menu') ?><span></span></a>

<ul id="<?php echo $id ?>" class="<?php echo $class; ?>" >
  <?php $n=1; foreach($nav as $pos => $item): ?>
    <?php // extras can have a custom class attribute too ?>
    <?php $itemClass = $class ?>
    <?php if (isset($item['class'])): ?>
      <?php $itemClass .= " " . $item['class'] ?>
    <?php endif ?>
    <li class="<?php echo $itemClass;
				echo ' a-nav-item-'.$nest;
        if($item['slug'] == $active) echo ' current_page_item a-current-page a-ancestor a-ancestor-'.$nest;
        if(isset($item['ancestor'])) echo ' ancestor a-ancestor a-ancestor-'.$nest;
        //Most people probably don't want this class, lets not clutter things up too much
        //if(isset($item['ancestor-peer'])) echo ' ancestor-peer';
        if(isset($item['extra'])) echo ' a-extra-page';
        if($item['archived']) echo ' a-archived-page';
        if($item['view_is_secure']) echo ' a-secure-page';
        if($pos == 0) echo ' first';
        if($pos == 1) echo ' second';
        if($pos == count($nav) - 2) echo ' next-last';
        if($pos == count($nav)-1) echo ' last'
    ?>" id="<?php echo 'a-nav-item-'.$name.'-'.$item['id'] ?>">

      <?php if(isset($item['external']) && $item['external']): ?>
        <?php echo link_to($item['title'], $item['slug']) ?>
      <?php else: ?>
        <?php echo link_to($item['title'], aTools::urlForPage($item['slug'], array('absolute' => true)), array('class' => 'a-nav-link a-nav-link-'.$nest)) ?>
      <?php endif ?>

      <?php if(isset($item['children']) && count($item['children']) && $nest < $maxDepth): ?>
        <?php include_partial('aNavigation/accordion', array('nav' => $escNav[$pos]['children'], 'draggable' => $draggable, 'maxDepth' => $maxDepth-1, 'name' => $name, 'nest' => $nest+1, 'dragIcon' => $dragIcon, 'class' => $class, 'active' => $active, 'n' => $n)) ?>
      <?php endif ?>

      <?php if ($dragIcon && $draggable): ?>
	<span class="a-ui a-btn icon a-drag no-label alt no-bg"><span class="icon"></span><?php echo a_('Drag') ?></span>
      <?php endif ?>

    </li>
    <?php //print_r($item); ?>
  <?php $n++; endforeach ?>
  
</ul>

</div>

<?php if (($draggable) && (isset($item))): ?>
	<?php a_js_call('apostrophe.accordionEnhancements(?)', array('name' => $name, 'nest' => $nest, 'url' => a_url('a', 'sortNav', array('page' => $item['id'])))) ?>
<?php endif ?>