<?php
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $pageid = isset($pageid) ? $sf_data->getRaw('pageid') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
  $slug = isset($slug) ? $sf_data->getRaw('slug') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  
  $id = isset($id) ? $sf_data->getRaw('id') : false;
  
  $title = isset($title) ? $sf_data->getRaw('title') : false;
  
  $form = isset($form) ? $sf_data->getRaw('form') : false;
  
  $formMenu = isset($formMenu) ? $sf_data->getRaw('formMenu') : false;
  
  $rootNode = isset($rootNode) ? $sf_data->getRaw('rootNode') : false;
  $treeMenuNodes = isset($treeMenuNodes) ? $sf_data->getRaw('treeMenuNodes') : false;
  
  $editable = isset($editable) ? $sf_data->getRaw('editable') : false;
  
  $showTitle = isset($showTitle) ? $sf_data->getRaw('showTitle') : false;
?>
<?php use_helper('a','ar') ?>

<?php slot("a-slot-controls-$pageid-$name-$permid") ?>
     <?php include_partial('arMenuCMSSlot/choose', array('form' => $form, 'formMenu' => $formMenu,  'class' => 'a-btn icon a-media', 'id' => $id, 'options' => $options, 'name' => $name, 'slug' => $slug, 'permid' => $permid)) ?>
     <?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page, 'controlsSlot' => false, 'label' => a_get_option($options, 'editLabel', __('New menu title',null,'configure')))) ?>     
 <?php end_slot() ?>


<?php if ($rootNode && $showTitle): ?>
    <?php if (isset($options['class'])): ?>
        <h4 class="<?php echo $options['class'] ?>"><?php echo $rootNode->getName(); ?></h4>
    <?php else: ?>
        <h4><?php echo $rootNode->getName(); ?></h4>
    <?php endif ?>
<?php endif ?>
        
<?php if ($rootNode && $editable): ?>
   <?php include_js_call('arMenuCMSSlot/jsEnableEditControl', array('id' => $id)) ?>
<?php endif ?>
    
<?php include_partial('arMenuCMSSlot/menuList', 
        array('slug' => $slug,
              'treeMenuNodes' => $treeMenuNodes)) ?>
        
<?php if ($sf_request->isXmlHttpRequest()): ?>
  <?php echo ar_get_js_calls() ?>
<?php endif; ?>
