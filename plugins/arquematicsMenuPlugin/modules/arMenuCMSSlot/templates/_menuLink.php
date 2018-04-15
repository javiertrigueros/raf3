<?php $slug = isset($slug) ? $sf_data->getRaw('slug') : false; ?>
<?php $node = isset($node) ? $sf_data->getRaw('node') : null; ?>

<?php if ($slug === $node->getUrl()): ?>
<li data-depth="<?php echo ($node->getLevel() -1) ?>" data-menu-type="link" data-name="<?php echo $node->getName() ?>" data-url="<?php echo $node->getUrl() ?>" data-pageid="" class="current_page_item menu-item menu-item-depth-<?php echo ($node->getLevel() -1) ?>" id="menu-item-<?php echo $node->getId() ?>">
    <a href="<?php echo $node->getUrl() ?>"><?php echo $node->getName() ?></a>
</li>
<?php else: ?>
<li data-depth="<?php echo ($node->getLevel() -1) ?>" data-menu-type="link" data-name="<?php echo $node->getName() ?>" data-url="<?php echo $node->getUrl() ?>" data-pageid="" class="menu-item menu-item-depth-<?php echo ($node->getLevel() -1) ?>" id="menu-item-<?php echo $node->getId() ?>">
    <a href="<?php echo $node->getUrl() ?>"><?php echo $node->getName() ?></a>
</li>
<?php endif; ?>
