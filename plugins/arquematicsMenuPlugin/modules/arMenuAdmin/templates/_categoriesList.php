
<?php $categories = isset($categories) ? $sf_data->getRaw('categories') : false; ?>
<?php $type = isset($type) ? $sf_data->getRaw('type') : false; ?>

<?php if ($categories && (count($categories) > 0)): ?>
    <ul class="categorychecklist form-no-clear">
    <?php foreach($categories as $category): ?>
        <?php try { ?>
        
        <?php $args = array('cat' => $category->slug) ?>
        <?php $url = url_for(aUrl::addParams((($type == 'post') ? 'aBlog' : 'aEvent' ).'/index', $args)) ?>
        <li>
            <label class="menu-item-title">
                <input type="checkbox" value="<?php echo $category->getId(); ?> " name="category[]" class="menu-item-checkbox" data-item-type="<?php echo $type ?>" data-url="<?php echo $url; ?>" data-id="<?php echo $category->getId(); ?>" data-name="<?php echo $category->getName(); ?>">
                <?php echo $category->getName(); ?> 
            </label>
        </li>
        
        <?php } catch (EntityNotFoundException $e) {} ?>
        
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
