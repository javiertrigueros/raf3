<?php $node = isset($node) ? $sf_data->getRaw('node') : null; ?>
<?php $slug = isset($slug) ? $sf_data->getRaw('slug') : false; ?>
<?php if ($slug === $node['url']): ?>
<li data-depth="<?php echo ($node['level'] -1) ?>" data-menu-type="<?php echo $node['menu_type'] ?>" data-name="<?php echo $node['name'] ?>" data-url="<?php echo $node['url'] ?>" data-id="<?php echo $node['id'] ?>" class="current_page_item" id="menu-item-<?php echo $node['id'] ?>">
    <a href="<?php echo $node['url'] ?>"><?php echo $node['name'] ?></a>
    <?php include_partial('arMenuSecundarySlot/menuChildren', 
                            array('children' => $node['children'],
                                  'slug' => $slug)) ?>
</li>
<?php else: ?>
<li data-depth="<?php echo ($node['level'] -1) ?>" data-menu-type="<?php echo $node['menu_type'] ?>" data-name="<?php echo $node['name'] ?>" data-url="<?php echo $node['url'] ?>" data-id="<?php echo $node['id'] ?>"  id="menu-item-<?php echo $node['id'] ?>">
    <a href="<?php echo $node['url'] ?>"><?php echo $node['name'] ?></a>
    <?php include_partial('arMenuSecundarySlot/menuChildren', 
                            array('children' => $node['children'],
                                  'slug' => $slug)) ?>
</li>
<?php endif; ?>
