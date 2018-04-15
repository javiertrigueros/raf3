
<?php $treeMenuNodes = isset($treeMenuNodes) ? $sf_data->getRaw('treeMenuNodes') : false; ?>
<?php if ($treeMenuNodes): ?>
    <? // si es un nodo raiz no tiene tipo y no sale en la lista ?>
    <?php foreach($treeMenuNodes as $node): ?>
        <?php if ($node->getMenuType() == 'page'): ?>
            <?php include_partial("arMenuAdmin/menuPage", array('node' => $node)) ?>
        <?php elseif ($node->getMenuType() == 'link'): ?>
            <?php include_partial("arMenuAdmin/menuLink", array('node' => $node)) ?>
        <?php elseif ($node->getMenuType() == 'blog'): ?>
            <?php include_partial("arMenuAdmin/menuBlog", array('node' => $node)) ?>
        <?php elseif ($node->getMenuType() == 'event'): ?>
            <?php include_partial("arMenuAdmin/menuEvent", array('node' => $node)) ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
