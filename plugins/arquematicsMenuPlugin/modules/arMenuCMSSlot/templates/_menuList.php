<?php $slug = isset($slug) ? $sf_data->getRaw('slug') : false; ?>
<?php $treeMenuNodes = isset($treeMenuNodes) ? $sf_data->getRaw('treeMenuNodes') : false; ?>
<?php if ($treeMenuNodes): ?>
<ul>
    <?php // menu items ?>
    <?php foreach($treeMenuNodes as $node): ?>
        <?php if ($node->getMenuType() == 'page'): ?>
            <?php include_partial("arMenuCMSSlot/menuPage", 
                    array('node' => $node,
                          'slug' => $slug)) ?>
        <?php elseif ($node->getMenuType() == 'link'): ?>
            <?php include_partial("arMenuCMSSlot/menuLink", 
                    array('slug' => $slug,
                          'node' => $node)) ?>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
