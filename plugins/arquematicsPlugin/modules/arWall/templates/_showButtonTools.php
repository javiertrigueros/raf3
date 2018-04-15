<?php $enabledTools = isset($enabledTools) ? $sf_data->getRaw('enabledTools') : false; ?>

<?php if ($hasEnabledTools): ?>
<ul id="tool-buttons"  class="control-icons col-xs-12 col-sm-12 col-md-4 col-lg-4">
    <?php foreach ($enabledTools as $tool): ?>
     <?php if ((!$tool['merge']) && $tool['show-icon']): ?>
        <li id="<?php echo $tool['name'] ?>">
            <i class="<?php echo $tool['icon'] ?>"></i>
        </li>
     <?php elseif ($tool['merge'] && $tool['show-icon']): ?>
        <li id="<?php echo $tool['merge-alias'] ?>">
            <i class="<?php echo $tool['merge-icon'] ?>"></i>
        </li>
     <?php endif; ?>
    <?php endforeach ?>
</ul>
<?php endif ?>