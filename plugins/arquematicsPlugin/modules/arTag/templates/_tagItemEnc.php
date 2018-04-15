<?php $tag = isset($tag) ? $sf_data->getRaw('tag') : null; ?>
<?php if ($tag['active']): ?>
     <div id="tag-control-<?php echo $tag['id'] ?>" class="cmd-tag tag-item active label label-primary" 
          data-tag_url="<?php echo url_for('@wall?tag='.$tag['hash']) ?>" 
          data-count="<?php echo $tag['count'] ?>" 
          data-hash="<?php echo $tag['hash'] ?>"
          data-tag_id="<?php echo $tag['id'] ?>">
        
        <span data-encrypt-text="<?php echo $tag['encContent'] ?>" class="content-text"></span>
        <i class="cmd-filter-tag tag-remove-circle fa fa-times-circle"></i>
        <span class="tag-counter hide">(<?php echo $tag['count']  ?>)</span>
     </div>
<?php else: ?>
     <div id="tag-control-<?php echo $tag['id'] ?>" class="cmd-tag tag-item label label-primary" 
          data-tag_url="<?php echo url_for('@wall?tag='.$tag['hash']) ?>" 
          data-count="<?php echo $tag['count'] ?>" 
          data-hash="<?php echo $tag['hash'] ?>"
          data-tag_id="<?php echo $tag['id'] ?>">
        
        <span data-encrypt-text="<?php echo $tag['encContent'] ?>" class="content-text"></span>
        <i class="cmd-filter-tag tag-remove-circle fa fa-times-circle hide"></i>
        <span class="tag-counter">(<?php echo $tag['count']  ?>)</span>
     </div>
<?php endif; ?>
 
