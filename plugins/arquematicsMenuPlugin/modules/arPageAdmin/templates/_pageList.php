<?php use_helper('arMenu'); ?>
<?php $pages = isset($pages) ? $sf_data->getRaw('pages') : false; ?>
<?php if ($pages && (count($pages) > 0)): ?>
    <? // si es un nodo raiz no tiene tipo y no sale en la lista ?>
    <?php foreach($pages as $node): ?>
        <?php include_partial("arPageAdmin/menuPage", array('node' => $node)) ?>
       

        
    <?php endforeach; ?>
<?php endif; ?>
