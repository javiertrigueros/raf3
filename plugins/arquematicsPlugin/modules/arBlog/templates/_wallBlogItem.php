<?php  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : false; ?>
<?php //$excerptLength = (sfConfig::get('app_aBlog_excerpts_length')) ? sfConfig::get('app_aBlog_excerpts_length') : 30; ?>
<?php  $authUser = ($authUser)? $sf_data->getRaw('authUser'): false; ?>
<?php  $message = ($message)? $sf_data->getRaw('message'): false; ?>

<div class="blog-item-content">
    
    <?php include_component('arMedia','showBlogItemImageResponsive',array('execJsScript' => false,'aBlogItem' => $aBlogItem)); ?>  
    
    <div class="blog-item-text">

        <a href="<?php echo url_for('a_blog_post',$aBlogItem) ?>" >
            <?php if ($aBlogItem->getType() == 'post'): ?>
                 <span class="ar-icon-small ar-icon-blog fa fa-comment-o"></span>
            <?php else: ?>
                 <span class="ar-icon-small ar-icon-blog fa fa-calendar"></span>
            <?php endif; ?>
           
            <span class="file-text file-text-content">
                <?php echo ucfirst($aBlogItem->getTitle()); ?>
            </span>
        </a>

        <?php if (sfConfig::get('app_arquematics_encrypt',false)): ?>
        <div data-encrypt-text="<?php echo $aBlogItem->getEncryptExcerpt($authUser) ?>" class="content-text message-user-content-blog">
            
        </div>
        <?php else: ?>
        <div class="content-text">
            <?php echo aHtml::simplify($aBlogItem->getExcerpt())  ?>        
        </div>
        <?php endif; ?>  
    </div>
</div>     

