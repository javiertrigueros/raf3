<?php
 
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $parent = isset($parent) ? $sf_data->getRaw('parent') : null;
  
  $slugStem = isset($slugStem) ? $sf_data->getRaw('slugStem') : null;
  
  $create = isset($create) ? $sf_data->getRaw('create') : false;
  
?>

<!-- form settings -->   
<div class="a-form-row a-hidden">
    <?php echo $form['id']->render() ?>
    <?php echo $form['_csrf_token']->render() ?>
   
</div>

<?php echo $form->renderGlobalErrors() ?>

<div class="a-options-section title-permalink open clearfix">

    <?php if (isset($form['realtitle'])): ?>
   
    <div class="a-form-row a-page-title">
        <?php // "Why realtitle?" To avoid excessively magic features of sfFormDoctrine.
        // There is another way but I think it might still try to initialize the field
  	// in an unwanted fashion even if it allows them to be saved right ?>
        <div class="a-form-field">
            <?php echo $form['realtitle']->render(array('id' => 'a-edit-page-title', 'class' => 'a-page-title-field')) ?>
            <?php if (isset($form['slug'])): ?>
                <div class="a-page-slug<?php //echo ($create)? ' a-hidden':'' ?>">
                    <h4>
                        <label>http://<?php echo $_SERVER['HTTP_HOST'] ?><?php echo ($page->getSlug() == '/') ? '/':'' ?></label>
                    </h4>
                    <?php if (isset($form['slug'])): ?>
                        <div class="a-form-field">
                            <?php echo $form['slug']->render() ?>
                        </div>
                        <?php echo $form['slug']->renderError() ?>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>   
      <?php echo $form['realtitle']->renderError() ?>
      </div>
      <?php endif ?>

      <hr class="a-hr" />

      <?php if (isset($form['joinedtemplate'])): ?>
        <div class="a-form-row a-edit-page-template">
            <h4><label><?php echo a_('Page Type') ?></label></h4>
            <div class="a-form-field">
                <?php echo $form['joinedtemplate']->render() ?>
            </div>
	</div>
      <?php endif ?>

      <?php if (sfConfig::get('app_a_simple_permissions')): ?>
        <div class="a-form-row status">
            <h4>
                <label><?php echo a_('Who can see this?') ?></label>
            </h4>
         
            <div class="a-form-field">
                <?php echo $form['simple_status']->render() ?>
            </div>
        </div>
       <?php else: ?>
       <?php // It's nice to be able to shut this off in custom permissions ?>
       <?php  if (isset($form['archived'])): ?>
        <div class="a-form-row status">
            <h4>
                <label><?php echo __('Published', null, 'apostrophe') ?></label>
            </h4>
            <div class="status">
                <?php echo $form['archived'] ?>
                <?php if(isset($form['cascade_archived'])): ?>
                    <div class="cascade-checkbox a-cascade-option">
                        <?php echo $form['cascade_archived'] ?>
                        <?php echo __('apply to subpages', null, 'apostrophe') ?>
                    </div>
                <?php endif ?>
            </div>
         </div>
	<?php endif ?>
    <?php endif ?>
</div>

<a href="#more-options"  class="a-btn lite mini a-more-options-btn">
    <?php echo __('More Options...', null, 'adminMenu') ?>
</a>



<div class="a-options-more">
    <hr class="a-hr" />
    <div class="a-options-section tags-metadata a-accordion clearfix">
        <h3 class="a-tag-heading head-menu-control"><?php echo __('Tags and Metadata', null, 'adminMenu') ?></h3>
	<div class="accordion-content">
            <div class="a-form-row keywords">
                <div class="a-form-field">
                    <?php echo $form['tags']->render() ?>
		</div>
		<?php echo $form['tags']->renderError() ?>
            </div>
            <?php if (sfConfig::get('app_a_metaTitle')) : ?>
                <div class="a-form-row meta-title">
                    <h4 class="a-block">
                        <label><?php echo __('Description', array(), 'adminMenu') ?></label>
                    </h4>
                    <div class="a-help"><?php echo __('Leave blank to use the navigation title as the title tag.', null, 'adminMenu') ?></div>
                    <div class="a-form-field">
                        <?php echo $form['real_meta_title']->render(array('class' => 'a-page-meta-title-field')) ?>
                    </div>
                    <?php echo $form['real_meta_title']->renderError() ?>
		</div>
            <?php endif ?>
                <div class="a-form-row meta-description">
                    <h4 class="a-block">
                        <label><?php echo __('Description', array(), 'adminMenu') ?></label>
                    </h4>
                    <div class="a-form-field">
                        <?php echo $form['real_meta_description'] ?>
                    </div>
                    <?php echo $form['real_meta_description']->renderError() ?>
		</div>
	</div>
    </div>

 <?php if (!sfConfig::get('app_a_simple_permissions')): ?>
    <hr class="a-hr" />
    <?php $hasSubpages = $page->hasChildren(false) ?>
    <?php include_partial('arPageAdmin/allPrivileges', array('form' => $form, 'inherited' => false, 'admin' => true, 'hasSubpages' => $hasSubpages)) ?>
  <?php endif ?>
    <hr class="a-hr" /> 
</div>


<!-- /.form settings --> 