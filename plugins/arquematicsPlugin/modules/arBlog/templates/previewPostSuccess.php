<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $configuration = isset($configuration) ? $sf_data->getRaw('configuration') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $formCategory = isset($formCategory) ? $sf_data->getRaw('formCategory') : null;
  $formCategoryMultiple = isset($formCategoryMultiple) ? $sf_data->getRaw('formCategoryMultiple') : null;
  $formSearchTag = isset($formSearchTag) ? $sf_data->getRaw('formSearchTag') : null;
  
  
  $formTag = isset($formTag) ? $sf_data->getRaw('formTag') : null;
  $formTagMultiple = isset($formTagMultiple) ? $sf_data->getRaw('formTagMultiple') : null;
  
  
  $helper = isset($helper) ? $sf_data->getRaw('helper') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
?>

<?php use_helper('I18N','a','ar') ?>

<?php use_stylesheet("/arquematicsPlugin/css/bootstrap.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/bootstrap-responsive.css"); ?>
  
<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome-ie7.css"); ?>
<?php use_stylesheet("/arquematicsExtraSlotsPlugin/css/font-awesome.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/blog.css"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/bootstrap-2.0.2.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics.fieldeditor.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/bootstrap-multiselect.js"); ?>



<div id="container" class="container-fluid">
   <div class="row-fluid">
       
     <div class="span9">
         <?php //include_component('arWall','showTabsTools'); ?>
         <?php //include_component('arWall','showTabsControls', array('aUserProfile' => $aUserProfile)); ?>
         
         <div id="content" class="row-fluid">
           <div id="a-blog-title-and-slug">
                <?php include_component('arBlog','showTitleAndSlug', array('a_blog_post' => $a_blog_post,'editable' => true)) ?>
           </div>

           <div class="a-blog-item post<?php echo ($a_blog_post->hasMedia())? ' has-media':''; ?> <?php echo $a_blog_post->getTemplate() ?>">
                <?php include_partial('arBlog/singleColumnTemplate', array('a_blog_post' => $a_blog_post, 'edit' => true)) ?>
           </div>
         </div>
     </div><!--/span-->
       
     <div class="span3 fuelux">
        <?php include_partial('arBlog/formPublish', array('formCategory' => $formCategory, 'aBlogItem' => $a_blog_post)) ?>
        <?php include_partial('arBlog/formCategory', array('formCategory' => $formCategory, 'formCategoryMultiple' => $formCategoryMultiple,'a_blog_post' => $a_blog_post)) ?>
        <?php include_partial('arBlog/formTag', array('formTag' => $formTag, 'aBlogItem' => $a_blog_post, 'formTagMultiple' => $formTagMultiple, 'formSearchTag' => $formSearchTag)) ?>
        <form method="post" action="<?php echo url_for('ar_blog_update', $a_blog_post) ?>" id="a-admin-form" class="a-ui blog">
            <?php include_partial('arBlog/form', array('form' => $form, 'a_blog_post' => $a_blog_post, 'popularTags' => $popularTags, 'existingTags' => $existingTags)) ?>
        </form> 
     </div><!--/span-->
     
   </div><!--/row-->
 
 </div><!--/.fluid-container-->
 
 <?php slot('global-head'); ?>
    <!-- Navbar -->
    <div id="header" class="navbar navbar-fixed-top">
        
      <div  class="mmbar">
           
            <div id="icon-menu" class=" search-content">
                <?php include_partial("arWall/login"); ?> 
                <ul class="pull-left">
                    <li class="navi link">
                        <span class="back">&nbsp;</span>
                        <?php echo link_to(__('Back',array(),'profile'),'wall',array(),array('class' => 'back')); ?>
                    </li>
                </ul>
                <span id="list-warm-add-error-main" class="hide warm-add-error-main alert alert-block alert-error fade in"><?php echo __("Is already in your list.",array(),'profile') ?></span> 

            </div>
      </div>
       
      
    </div>
    <!--/Navbar-->
<?php end_slot(); ?>