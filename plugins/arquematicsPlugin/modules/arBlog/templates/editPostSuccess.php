<?php use_helper('I18N','a','ar') ?>

<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  
  $authUser = isset($authUser) ? $sf_data->getRaw('authUser') : null;
  $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : null; 
  
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $formCategory = isset($formCategory) ? $sf_data->getRaw('formCategory') : null;
  $formTag = isset($formTag) ? $sf_data->getRaw('formTag') : null;
  
  $culture = isset($culture) ? $sf_data->getRaw('culture') : 'es';
          
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
  
  $pageBack = isset($pageBack) ? $sf_data->getRaw('pageBack') : false;
?>

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arBlog.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>


<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/tmpl.min.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/plugins/autoresize.jquery.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/bootstrap-multiselect.js"); ?>


<?php use_javascript("/apostrophePlugin/js/plugins/jquery.simpleautogrow.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/PixelAdmin/PixelAdmin.MainNavbar.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>



<?php include_partial('arWall/encrypt', array('sections' => array('mainNodeId' => '#col-content'),
                                               'aUserProfile' => $aUserProfile))?>


<?php slot('global-head-search','')?>

<?php slot('global-head')?>
<div id="navbar-content" class="navbar-inner">
<!-- Main navbar header -->
    <div class="navbar-header">
        <!-- Logo -->

        <?php include_component('arMenuAdmin','showBackButton', array('pageBack' => arMenuInfo::WALL)); ?>
        <!-- Main navbar toggle -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
            <i class="navbar-icon fa fa-bars"></i>
        </button>
    </div> <!-- / .navbar-header -->
    <div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
        <div>
            <div class="right clearfix">
                <ul class="nav navbar-nav pull-right right-navbar-nav">
                    <?php include_slot('global-head-search') ?>
               
                    <?php include_component('arMenuAdmin','showMainMenu'); ?>
                </ul> <!-- / .navbar-nav -->
            </div> <!-- / .right -->
        </div>
    </div><!-- / #main-navbar-collapse -->
</div><!-- / .navbar-inner -->
<?php end_slot() ?>

<div id="content-wrapper">
  <div class="profile-row">

   <div id="col-content" class="left-col">
        
         <div class="arblog-info-content row-fluid">

          <div id="a-blog-title-and-slug">
            <?php include_component('arBlog','showTitleAndSlug', array('authUser' => $authUser,
                                                                            'a_blog_post' => $a_blog_post,
                                                                            'editable' => true)) ?>
          </div>

          <div class="a-blog-item post<?php echo ($a_blog_post->hasMedia())? ' has-media':''; ?> <?php echo $a_blog_post->getTemplate() ?>">
            <?php include_partial('arBlog/singleColumnTemplate', array('a_blog_post' => $a_blog_post, 'edit' => true)) ?>
          </div>

        </div>

                    
    </div>
      
    <div id="col-publish" class="right-col">
        <?php include_partial('arBlog/formPublishPost', 
                array('formBlogPost' => $form,
                      'culture' => $culture,
                      'aBlogItem' => $a_blog_post)) ?>

        <?php include_partial('arBlog/formCategory', array('form' => $formCategory, 'aBlogItem' => $a_blog_post)); ?>
        <?php include_partial('arBlog/formTag', array('form' => $formTag,  'aBlogItem' => $a_blog_post)); ?>
       
    </div>

  </div>
</div>


<?php slot('body_class','theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>




   



 
