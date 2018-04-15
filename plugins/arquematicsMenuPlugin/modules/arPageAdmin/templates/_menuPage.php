<?php $node = isset($node) ? $sf_data->getRaw('node') : null; ?>
<li data-text_event="<?php echo __('Events', null, 'configure') ?>" data-text_blog="<?php echo __('Blog', null, 'configure') ?>" data-text_page="<?php echo __('Page', null, 'configure') ?>" data-depth="<?php echo ($node['level'] -1) ?>" data-menu-type="<?php echo getPageType($node) ?>" data-name="<?php echo $node['title'] ?>" data-url="<?php echo $node['slug'] ?>" data-id="<?php echo $node['id'] ?>" class="menu-item menu-item-depth-<?php echo ($node['level'] -1) ?> menu-item-page menu-item-edit-inactive" id="menu-item-<?php echo $node['id'] ?>">
    <dl class="menu-item-bar">
        <dt class="menu-item-handle">
					<span class="item-title">
                                            <span id="menu-title-label" class="menu-item-title"><?php echo $node['title'] ?></span>
                                            <span style="display: none;" class="is-submenu">
                                                <?php echo __('subelement', null, 'configure') ?>
                                            </span>
                                        </span>
					<span class="item-controls">
						<span class="item-type">
                                                   <?php echo getPageTypeText($node) ?>
                                                </span>
						<span class="item-order hide-if-js">
							<a class="item-move-up" href="#"><abbr title="<?php echo __('Move up', null, 'configure') ?>">↑</abbr></a>
							|
							<a class="item-move-down" href="#"><abbr title="<?php echo __('Move down', null, 'configure') ?>">↓</abbr></a>
						</span>
						<a href="#" title="<?php echo __('Edit menu item', null, 'configure') ?>" id="edit-${id}" class="item-edit"><?php echo __('Edit menu item', null, 'configure') ?></a>
					</span>
	</dt>
    </dl>

			<div id="menu-item-settings-<?php echo $node['id'] ?>" class="menu-item-settings" style="display: none;">
				<p class="description-wide">
                                        <?php include_component('arPageAdmin','showFormById', array('pageId' => $node['id'])) ?>
				</p>
				
				<div class="menu-item-actions description-wide submitbox">
                                    <a href="#" id="cmd-send-page<?php echo $node['id'] ?>" class="btn btn-primary span4 update-page"  data-loading-text="<?php echo __("send...",array(),'wall') ?>"><?php echo __('Save',null,'configure') ?></a>
                                    <a href="#" id="delete-<?php echo $node['id'] ?>" class="item-delete submitdelete deletion"><?php echo __('Remove',null,'configure') ?></a>          
				</div>

				
			</div><!-- .menu-item-settings-->
			
</li>


<?php if ($node && isset($node['children'])): ?>
            <?php foreach($node['children'] as $node): ?>
                <?php include_partial("arPageAdmin/menuPage", array('node' => $node)) ?>
            <?php endforeach; ?>
<?php endif; ?>