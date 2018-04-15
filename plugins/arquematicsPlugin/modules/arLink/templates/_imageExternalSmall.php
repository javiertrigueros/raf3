<?php $arWallLink = isset($arWallLink) ? $sf_data->getRaw('arWallLink') : false; ?>
<?php $class = isset($class) ? $sf_data->getRaw('class') : false; ?> 

<?php if ($arWallLink): ?>
    <?php if ($class): ?>
         <img class="<?php echo $class ?>"  src='<?php echo url_for("@user_resource?type=arWallLink&name=".$arWallLink->getBaseName()."&format=".$arWallLink->getExtension()."&size=external_small") ?>'  />
    <?php else: ?>
         <img src='<?php echo url_for("@user_resource?type=arWallLink&name=".$arWallLink->getBaseName()."&format=".$arWallLink->getExtension()."&size=external_small") ?>'  />
    <?php endif; ?>
<?php endif; ?>