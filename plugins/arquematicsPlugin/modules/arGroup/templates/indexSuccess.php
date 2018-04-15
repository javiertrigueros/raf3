<?php use_helper('I18N','Partial','a','ar') ?>

<?php $aUserProfile = isset($aUserProfile) ? $sf_data->getRaw('aUserProfile') : false; ?>
<?php $formFriendRequestNoList = isset($formFriendRequestNoList) ? $sf_data->getRaw('formFriendRequestNoList') : null; ?>
<?php $countUsers = isset($countUsers) ? $sf_data->getRaw('countUsers') : 0; ?>
<?php $isLastPage = isset($isLastPage) ? $sf_data->getRaw('isLastPage') : true; ?>
<?php $formSearch = isset($formSearch) ? $sf_data->getRaw('formSearch') : null; ?>

<?php use_stylesheet("/arquematicsPlugin/js/vendor/bootstrap/css/bootstrap.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/assets/stylesheets/themes.css"); ?>

<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arGroupCommon.css"); ?>
<?php use_stylesheet("/arquematicsPlugin/css/arquematics/arGroup.css"); ?>


<?php use_javascript("/arquematicsPlugin/js/jquery.tmpl.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/tmpl.min.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/jquery.livequery.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/jquery.infinite.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/bootstrap/js/bootstrap.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/vendor/jquery/widget/jquery.ui.widget.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/arquematics.js"); ?>

<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/group/arquematics.userscreen.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/group/arquematics.subscribers.js"); ?>
<?php use_javascript("/arquematicsPlugin/js/arquematics/widget/group/arquematics.listeditor.js"); ?>

<?php slot('global-head-extra')?>
  <div id="content-groups-all" class="content-groups-all navbar">
    <div class=" navbar-inner">
      <?php include_partial('arGroup/listProfileList', array('currentUser' => $aUserProfile)); ?>
    </div>
  </div>
<?php end_slot() ?>

<?php slot('global-head-search')?>
<li>
        <form id="form_user_search" class="navbar-form pull-left" action="<?php echo url_for('@search_users_byname?username='.$aUser->getUsername()) ?>"  method="POST">
                <div class="input-group"> 
                  <?php echo $formSearch['search']->render(array('placeholder' => __('Search', null, 'apostrophe'), 'class' => 'form-control')) ?> 
                  <?php echo $formSearch->renderHiddenFields(); ?>
                  <span class="form-search navbar-icon cmd-search"> 
                       <i id="cmd-search" class="fa fa-search"></i>
                  </span> 
                </div>
        </form>
</li>
<?php end_slot() ?>

<?php slot('global-head')?>
<div id="header">

<div class="navbar-inner">
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
            <ul class="nav navbar-nav">
                <li id="list-warm-add-error-main" class="box-warm hide">
                     <span class="alert alert-danger">
                        <?php echo __("Is already in your list.",array(),'profile') ?>
                     </span>
                </li>
            </ul> <!-- / .navbar-nav -->
    </div><!-- / #main-navbar-collapse -->

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

<?php include_slot('global-head-extra') ?>

</div>
<?php end_slot() ?>

<div id="content-active-list" class="hide"></div>
<div id="content-container" data-is_last_page="<?php echo $isLastPage?'true':'false' ?>" data-count_users="<?php echo $countUsers; ?>" class="content-container ui-droppable hide" style="margin:0 auto;width: 85%; text-align: center;">
            <div id="members-minus" class="members-black members-insert-node">
                <div class="node-insert-icon minus-image"></div>
                <div class="insert-text"><?php echo __("Remove",array(),'profile') ?></div>
            </div>
            <?php include_component('arGroup','listUsers', array('aUserProfile' => $aUserProfile,'page' => 1)); ?> 
            <div id="members-loader" class="loader hide">
                  <img src="/arquematicsPlugin/images/loaders/general-loader.gif" class="loader-img">
            </div>
</div>

    
<form id="form_add_list" action="<?php echo url_for('@add_friend_request?username='.$aUser->getUsername()); ?>"  method="POST">
    <?php echo $formListAdd->renderHiddenFields() ?>
</form>

<form id="form_get_list" action="<?php echo url_for('@get_user_list?username='.$aUser->getUsername()); ?>"  method="POST">
    <?php echo $formGetList->renderHiddenFields() ?>
</form>

<form id="form_list_delete" action="<?php echo url_for('@delete_user_list?username='.$aUser->getUsername()); ?>"  method="POST">
    <?php echo $formListDelete->renderHiddenFields() ?>
</form>

<form id="form_list_delete_all" action="<?php echo url_for('@delete_user_list_all?username='.$aUser->getUsername()); ?>"  method="POST">
    <?php echo $formListDeleteAll->renderHiddenFields() ?>
</form>

<form id="form_friend_request_no_list" action="<?php echo url_for('add_friend_request_no_list'); ?>"  method="POST">
    <?php echo $formFriendRequestNoList->renderHiddenFields() ?>
</form>
    
<?php slot('nav-modal-primary'); ?>
<!-- /Modales -->

<div id="list-modal" class="modal fade">
  <form id="form_new_list" action="<?php echo url_for('@save_user_list?username='.$aUser->getUsername()) ?>"  method="POST">
  <div class="modal-dialog modal-vertical-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo __("List name",array(),'profile') ?></h4>
      </div>
      <div class="modal-body">
           <?php echo $formProfileList['name']->render(array('class' => 'form-control')) ?>
           <span id="new_list_name_help" class="help-inline help-block"></span>
           <?php echo $formProfileList->renderHiddenFields() ?>
      </div>
      <div class="modal-footer">
        <button  type="submit" id="cmd-create-cancel" class="btn close-modal btn-info"><?php echo __("cancel",array(),'profile') ?></button>
        <button  type="submit" id="cmd-create" data-loading-text="<?php echo __("send",array(),'arquematics') ?>" class="btn btn-success"><?php echo __("Create list",array(),'profile') ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->

<div id="list-modal-edit" class="modal fade">
  <form id="form_edit_list" action="<?php echo url_for('@edit_user_list?username='.$aUser->getUsername()) ?>"  method="POST">
  <div class="modal-dialog modal-vertical-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close-modal" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?php echo __("List name",array(),'profile') ?></h4>
      </div>
      <div class="modal-body">
        <?php echo $formProfileEdit['name']->render(array('class' => 'form-control')) ?>
        <span id="edit_list_name_help" class="help-inline help-block"></span>
        <?php echo $formProfileEdit->renderHiddenFields() ?>
      </div>
      <div class="modal-footer">
        <button  type="submit" id="cmd-edit-cancel" class="btn close-modal btn-info"><?php echo __("cancel",array(),'profile') ?></button>
        <button  type="submit" id="cmd-edit" data-loading-text="<?php echo __("send",array(),'arquematics') ?>" class="btn btn-success"><?php echo __("Save",array(),'profile') ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->
    
<div id="list-modal-delete" class="modal fade"></div>
<!-- /Modales -->
<?php end_slot() ?>

<!-- templates -->
<script id="template-remove-list-modal" type="text/x-jquery-tmpl">
    <div class="modal-dialog modal-vertical-centered">
        <div class="modal-content">
        
    <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><?php echo __("Remove list",array(),'profile') ?></h4>
        </div>
        <div class="modal-body">
            <p><?php echo __('Remove list "${listname}".',array(),'profile') ?></p>
        </div>
        <div class="modal-footer">
            <button id="cmd-delete-list-cancel" href="#" class="btn btn-info"><?php echo __("cancel",array(),'profile') ?></button>
            <button id="cmd-delete-list-confirm" href="#" data-loading-text="<?php echo __("Removing",array(),'arquematics') ?>" class="btn btn-primary btn-danger"><?php echo __("Confirm",array(),'profile') ?></button>
        </div>
            
        </div>
    </div>
</script>
<!-- The template to list -->
<script id="template-list" type="text/x-jquery-tmpl">
    <div id="content-list-head">
        <div id="list-cancel"  data-id="${id}">
            <div class="close close-control-x"><span class="close-control"><?php echo __("Close",array(),'profile') ?></span>×</div>
        </div>
        <ul class="title-container">
            <li class="dropdown">
                <h1 data-toggle="dropdown" role="button" id="list-name">
                    <small>
                        <span class="list-name-text">${name}</span>
                        <span class="count count-list">(${count})</span>
                        <b class="caret"></b>
                          
                    </small>
                </h1>
                <ul aria-labelledby="drop4" role="menu" class="dropdown-menu" id="menu">
                    <li><a href="#" id="menu-list-edit"><?php echo __("Rename list",array(),'profile') ?></a></li>
                    <li><a href="#" id="menu-list-delete" data-name="${name}" data-id="${id}" data-owner-id="${owner_id}"><?php echo __("Delete list",array(),'profile') ?></a></li>
                    <li class="divider"></li>
                    <li><a href="#" data-id="${id}" id="menu-close-link"><?php echo __("Close",array(),'profile') ?></a></li>
                </ul>
            </li>
       </ul>
    </div>
    <div id="content-list" data-id="${id}" data-items="${items}" data-owner-id="${owner_id}">
            <div id="members-list" class="members-black members-insert-node">
                <div class="node-insert-icon plus-image"></div>
                <div class="insert-text"><?php echo __("Add person",array(),'profile') ?></div>
            </div>
            <div id="list-loader" class="loader hide">
                  <img src="/arquematicsPlugin/images/loaders/general-loader.gif" class="loader-img">
            </div>
  </div>
  <h1 class="group-separator">
      <small>
          <span class="glyphicon glyphicon-arrow-up"></span>
          <?php echo __('Drag people to the lists.',array(),'profile') ?>
          <span class="glyphicon glyphicon-arrow-up"></span>
      </small>
  </h1>
</script>
<!-- The template to user -->
<script id="template-user" type="text/x-jquery-tmpl">
 <li id="${id}" class="user-item"  data-id="${id}">${name}</li>
</script>
<!-- The template to new list -->
<script id="template-new-list" type="text/x-jquery-tmpl">
 <div class="group ui-droppable span4 group-create hide" id="group0" data-id="0" data-items="0">
    <i class="icon-plus"></i>
    <a href='#'><?php echo __("Drop here to create a list",array(),'profile') ?></a>
 </div>
</script>

<?php /*
<!-- The template to create list -->
<script id="template-create-list" type="text/x-jquery-tmpl">
 <ul class="user-list hide">
    <li id="${id}" class="user-item"  data-id="${id}">${name}</li>  
 </ul>
 <form id="form_new_list" action="<?php echo url_for('@save_user_list?username='.$aUser->getUsername()) ?>"  method="POST">
    <?php echo $formProfileList['name']->render() ?>
    <?php echo $formProfileList->renderHiddenFields() ?>
    <button  type="submit" id="cmd-create" class="span3 btn btn-success"><?php echo __("Create list",array(),'profile') ?></button>
 </form>
</script>
<!-- /templates --> */ ?>

<?php include_js_call('arGroup/jsIndex', array('aUserProfile' => $aUserProfile)) ?>
 
<?php //slot('main-menu-bg',false) ?>
<?php slot('body_class','body-group theme-default main-menu-animated page-profile main-navbar-fixed dont-animate-mm-content-sm animate-mm-md animate-mm-lg'); ?>
<?php slot('a-breadcrumb','') ?>
<?php slot('a-subnav','') ?>
<?php slot('a-tabs','') ?>
<?php slot('a-search','') ?>