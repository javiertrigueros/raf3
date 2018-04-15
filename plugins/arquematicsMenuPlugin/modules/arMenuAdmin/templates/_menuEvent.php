<?php $node = isset($node) ? $sf_data->getRaw('node') : null; ?>

<li data-depth="<?php echo ($node->getLevel() -1) ?>" data-menu-type="event" data-name="<?php echo $node->getName() ?>" data-url="<?php echo $node->getUrl() ?>" data-id="<?php echo $node->getCategoryId() ?>" class="menu-item menu-item-depth-<?php echo ($node->getLevel() -1) ?> menu-item-page menu-item-edit-inactive" id="menu-item-<?php echo $node->getId() ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title">
                                            <span class="menu-item-title"><?php echo $node->getName() ?></span>
                                            <span style="display: none;" class="is-submenu">
                                                <?php echo __('subelement', null, 'configure') ?>
                                            </span>
                                        </span>
					<span class="item-controls">
						<span class="item-type"><?php echo __('Event Category', null, 'configure') ?></span>
						<span class="item-order hide-if-js">
							<a class="item-move-up" href="#"><abbr title="<?php echo __('Move up', null, 'configure') ?>">↑</abbr></a>
							|
							<a class="item-move-down" href="#"><abbr title="<?php echo __('Move down', null, 'configure') ?>">↓</abbr></a>
						</span>
						<a href="#" title="<?php echo __('Edit menu item', null, 'configure') ?>" id="edit-${id}" class="item-edit"><?php echo __('Edit menu item', null, 'configure') ?></a>
					</span>
				</dt>
			</dl>

			<div id="menu-item-settings-<?php echo $node->getId() ?>" class="menu-item-settings" style="display: none;">
				<p class="description-wide">
					<label for="edit-menu-item-title-<?php echo $node->getId() ?>">
						<?php echo __('Navigation label', null, 'configure') ?><br>
						<input type="text" value="<?php echo $node->getName() ?>" name="menu-item-title" class="widefat code edit-menu-item-title span12 nav-label" id="edit-menu-item-title-<?php echo $node->getId() ?>">
					</label>
				</p>
				
				<div class="menu-item-actions description-wide submitbox">
                                    <p class="link-to-original">
                                        <?php echo __('Original',null,'configure') ?>: <a href="<?php echo $node->getUrl() ?>"><?php echo getOriginalMenuName($node) ?></a>
                                    </p>
                                    <a href="#" id="delete-<?php echo $node->getId() ?>" class="item-delete submitdelete deletion"><?php echo __('Remove',null,'configure') ?></a>          
				</div>

				
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
</li>