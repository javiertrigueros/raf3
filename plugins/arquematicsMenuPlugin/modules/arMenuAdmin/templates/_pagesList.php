
<?php $treeDataNodes = isset($treeDataNodes) ? $sf_data->getRaw('treeDataNodes') : false; ?>
<?php if ($treeDataNodes && (count($treeDataNodes) > 0)): ?>
<ul class="pagechecklist form-no-clear">
    <?php foreach($treeDataNodes as $node): ?>
         <li>
            <label class="menu-item-title">
                <input data-name="<?php echo $node['title'] ?>" data-id="<?php echo $node['id'] ?>" data-url="<?php echo $node['slug'] ?>" type="checkbox" class="menu-item-checkbox" name="page[]" value="<?php echo $node['id'] ?>" />
                <?php echo $node['title'] ?>
            </label>
         </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
