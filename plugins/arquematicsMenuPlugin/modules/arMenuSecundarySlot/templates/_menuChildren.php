
<?php $children = isset($children) ? $sf_data->getRaw('children') : false; ?>
<?php $slug = isset($slug) ? $sf_data->getRaw('slug') : false; ?>

<?php if (($children) 
        && (count($children) > 0)): ?>

<ul class='sub-menu-secundary'>
    <?php // menu items ?>
    <?php foreach($children as $node): ?>
         <?php include_partial("arMenuSecundarySlot/menuItem", array(
                        'slug' => $slug,
                        'node' => $node)) ?>
    <?php endforeach; ?>
</ul>
<?php endif; ?>


                    
