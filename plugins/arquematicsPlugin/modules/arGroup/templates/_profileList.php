<?php 
$display = isset($display) ? $sf_data->getRaw('display') : true;
$list = isset($list) ? $sf_data->getRaw('list') : false; 
?>
<div class="group group-active editable <?php if (!$display){ echo 'hide';} ?>" id="group<?php echo $list->getId() ?>" data-items="<?php echo $list->getListData() ?>" data-count="<?php echo ($list->count()) ?>" data-id="<?php echo $list->getId() ?>" data-owner-id="<?php echo $list->getProfileId() ?>" original-title="<?php echo $list->getName() ?>">

    <button class="btn btn-default group-button">
        <div class="text"><span class="list-name-text"><?php echo ucfirst($list->getName()) ?></span><span class="count count-buttom">(<?php echo ($list->count()) ?>)</span></div>
        <div class="animate hide"></div>
    </button>
    
</div>