<?php if ($image['type'] == 'diagram'): ?>
<a id='<?php echo $image['id']?>' href="<?php echo url_for("@user_wall_diagram?slug=".$image['slug']."&size=big") ?>" class='diagram <?php echo $class ?>' rel="<?php echo $class?>">
    <img alt='' src='<?php echo url_for("@user_wall_diagram?slug=".$image['slug']."&size=small") ?>' />
</a>
<?php elseif ($image['type'] == 'wall'): ?>
<a id='<?php echo $image['id']?>' href='<?php echo url_for("@user_wall_image?slug=".$image['slug']."&size=big") ?>' class='wall <?php echo $class ?>' rel="<?php echo $class?>">
    <img alt='' src='<?php echo url_for("@user_wall_image?slug=".$image['slug']."&size=small") ?>' />
</a>
<?php endif; ?>