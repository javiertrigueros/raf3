
<?php $treeMenuNodes = isset($treeMenuNodes) ? $sf_data->getRaw('treeMenuNodes') : false; ?>
<?php $slug = isset($slug) ? $sf_data->getRaw('slug') : false; ?>
<?php $class = isset($class) ? $sf_data->getRaw('class') : ''; ?>
<?php $rootNode = isset($rootNode) ? $sf_data->getRaw('rootNode') : false; ?>
<?php if (($treeMenuNodes) 
        && (count($treeMenuNodes) > 0)): ?>

<?php include_js_call('arMenuSecundarySlot/jsSecundaryMenu', array('id' => $rootNode->getRootId())); ?>

<?php use_javascript("/arquematicsMenuPlugin/js/superfish.js"); ?>

<a id="cmd-movile-<?php echo $rootNode->getRootId() ?>" href="#" class="mobile_nav closed"><?php echo __('Navigation Menu', null, 'adminMenu') ?><span></span></a>

<ul id="<?php echo $rootNode->getRootId() ?>" class="<?php echo $class ?>">
    <?php // menu items ?>
    <?php foreach($treeMenuNodes as $node): ?>
         <?php include_partial("arMenuSecundarySlot/menuItem", array(
                        'slug' => $slug,
                        'node' => $node)) ?>
 
    <?php endforeach; ?>
</ul>
<?php endif; ?>


                    
