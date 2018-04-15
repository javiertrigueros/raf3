<?php $hasMediaImages = isset($hasMediaImages) ? $sf_data->getRaw('hasMediaImages') : false; ?>
<?php $mediaQueries = isset($mediaQueries) ? $sf_data->getRaw('mediaQueries') : false; ?>
<?php $mediaItem = isset($mediaItem) ? $sf_data->getRaw('mediaItem') : false; ?>
<?php $execJsScript = isset($execJsScript) ? $sf_data->getRaw('execJsScript') : true; ?>

<?php if ($hasMediaImages): ?>

<?php use_javascript("/arquematicsExtraSlotsPlugin/js/jquery-picture.js"); ?> 

<?php $uniqId = uniqid(); ?>
<picture id="<?php echo $uniqId; ?>" title="<?php echo $mediaItem->title ?>" alt="<?php echo $mediaItem->title ?>" width="100%" height="auto" >             
        <?php foreach ($mediaQueries as $media):?>
            <source src="<?php echo $media['url']; ?>" media="(min-width:<?php echo $media['width'] ?>px)">
        <?php endforeach; ?>
        <noscript>
            <img src="<?php echo $mediaQueries[0]['url']; ?>" title="<?php echo $mediaItem->title ?>" alt="<?php echo $mediaItem->title ?>">
        </noscript>
 </picture>

<?php if ($execJsScript): ?>
    <?php include_js_call('arMedia/jsShowPicture', array('pictureId' => $uniqId)); ?>
<?php endif; ?>

 <?php endif ?>