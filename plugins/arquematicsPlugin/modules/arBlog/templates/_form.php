<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : null;
  $existingTags = isset($existingTags) ? $sf_data->getRaw('existingTags') : null;
?>

<?php use_helper("a") ?>

<?php $saveLabels = array('nochange' => a_('Update'), 'draft' => a_('Save'), 'publish' => a_('Publish'), 'schedule' => a_('Update')) ?>
<?php $saveLabel = isset($form['publication']) ? __('Update',array(),'blog'):__('Save',array(),'blog') ?>
<?php // One tiny difference: if we move from something else *TO* schedule, label it 'Schedule' ?>
<?php $updateLabels = array('nochange' => a_('Update'), 'draft' => a_('Save'), 'publish' => a_('Publish'), 'schedule' => a_('Schedule')) ?>
<?php // Invoked by include_partial in the initial load of the form partial and also directly on AJAX updates of this section ?>
<div class="a-hidden">
  <?php echo $form->renderHiddenFields() ?>
</div>

<div class="published section a-form-row">
  <div class="post-save clearfix">
    <?php echo a_anchor_submit_button($saveLabel, array('a-save', 'a-sidebar-button a-save-blog-main','a-show-busy','big')) ?>              
  </div>
</div>
<?php if (isset($form['publication'])): ?>
  <div class="status section a-form-row">
    <h4><?php echo __('Status',array(),'blog') ?></h4>
    <div class="status-list option">
      <?php echo $form['publication']->render() ?>
    </div> 
  </div>
<?php endif ?>
<div class="a-published-at-container">
  <div class="a-form-row">
    <?php echo $form['published_at']->render() ?>
  </div>
  <?php echo $form['published_at']->renderError() ?>
</div>


<?php // Author & Editors Section ?>
<hr class="a-hr" />
<div class="author section a-form-row">

  <?php // Blog Post Author ?>
  <div class="post-author">
      <h4><?php echo __('Author',array(),'blog') ?>
      <?php if ($sf_user->hasCredential('admin')): ?>
        </h4>
        <div class="author_id option">
        <?php echo $form['author_id']->render() ?>
        <?php echo $form['author_id']->renderError() ?>
        </div>
      <?php else: ?>: <span><?php echo $a_blog_post->Author ?></span></h4><?php endif ?>
  </div>
</div>

  <?php // Blog Post Editors ?>
  <?php if(isset($form['editors_list'])): ?>
    <div class="post-editors">

      <?php if (!count($a_blog_post->Editors)): ?>
        <a href="#" onclick="return false;" class="post-editors-toggle a-sidebar-toggle a-sidebar-link"><?php echo __('Allow others to edit this post',array(),'blog') ?></a>
        <div class="post-editors-options option" id="editors-section">
      <?php else: ?>
        <hr/>
        <div class="post-editors-options option show-editors" id="editors-section">
      <?php endif ?>

        <h4><?php echo __('Editors',array(),'blog') ?></h4>
        <?php echo $form['editors_list']->render()?>
        <?php echo $form['editors_list']->renderError() ?>

        </div>
    </div>
  <?php endif ?>

  <?php // Blog Post Templates ?>
  <?php if(isset($form['template'])): ?>
    <hr class="a-hr" />
    <div class="template section">
      <h4><?php echo __('Template',array(),'blog') ?></h4>
      <div class="a-form-row">
        <?php echo $form['template']->render() ?>
      </div>
      <?php echo $form['template']->renderError() ?>
    </div>
  <?php endif ?>


  <?php // Blog Post Comments ?>
  <?php if (isset($form['allow_comments'])): ?>
    <hr class="a-hr" />
    <div class="comments section">
      <?php // I used cite because, unfortunately, span has been styled in this context. -Tom ?>
      <h4><cite class="a-allow-comments-label"><?php echo __('Allow Comments',array(),'blog') ?></cite><?php echo $form['allow_comments']->render() ?></h4>
    </div>
  <?php endif ?>
  
  <?php // Blog Post Categories ?>
  <hr class="a-hr" />
  <div class="categories section a-form-row" id="categories-section">
    <h4><?php echo __('Categories',array(),'blog') ?></h4>
    <?php echo $form['categories_list']->render() ?>
    <?php $adminCategories = $form->getAdminCategories() ?>
    <?php if (count($adminCategories)): ?>
      <div class="a-form-row">
        <?php echo __('Set by admin:',array(),'blog').' '. implode(',', $form->getAdminCategories()) ?>
      </div>
    <?php endif ?>
    <?php echo $form['categories_list']->renderError() ?>
  </div>

  <?php // Blog Post Tags ?>
  <hr class="a-hr" />
  <div class="tags section a-form-row">
    <h4><?php echo __('Tags',array(),'blog') ?></h4>
    <div>
    <?php echo $form['tags']->render() ?>
    <?php echo $form['tags']->renderError() ?>
    <?php a_js_call('pkInlineTaggableWidget(?, ?)', '#a-blog-post-tags-input', array('popular-tags' => $popularTags, 'existing-tags' => $existingTags, 'typeahead-url' => url_for('taggableComplete/complete'), 'tags-label' => __('Add Tag',array(),'blog'))) ?>
    </div>
  </div>

  <?php // This is the place to add fields to this form without overriding the whole thing ?>
  <?php include_partial('aBlogAdmin/formAfter', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>

  <hr class="a-hr" />
  <div class="delete preview section a-form-row">
    <?php $engine = $a_blog_post->findBestEngine(); ?>
    <?php aRouteTools::pushTargetEnginePage($engine) ?>
    <?php echo link_to('<span class="icon"></span>'.__('Preview',array(),'blog'), 'a_blog_post', array('preview' => 1) + $a_blog_post->getRoutingParams(), array('class' => 'a-btn icon a-search alt lite a-align-left', 'rel' => 'external')) ?>
    <?php aRouteTools::popTargetEnginePage($engine->engine) ?>
    <?php if($a_blog_post->userHasPrivilege('delete')): ?>
      <?php echo link_to('<span class="icon"></span>'.__('Delete Post',array(),'blog'), 'a_blog_admin_delete', $a_blog_post, array('class' => 'a-btn icon a-delete alt lite a-align-right', 'method' => 'delete', 'confirm' => __('Are you sure? You want to delete this post?',array(),'blog') )) ?>
    <?php endif ?>
  </div>
  
<?php // This is NOT the right place to close the form tag ?>

<?php a_js_call('aBlogEnableForm(?)', array('update-labels' => $updateLabels, 'reset-url' => url_for('@a_blog_admin_update?' . http_build_query(array('id' => $a_blog_post->id, 'slug' => $a_blog_post->slug))), 'editors-choose-label' => __('Choose Editors',array(),'blog'), 'categories-choose-label' => __('Choose Categories',array(),'blog'), 'categories-add' => $sf_user->hasCredential('admin'), 'add-add-label' => __('Add',array(),'blog'), 'categories-add-label' => __('+ New Category',array(),'blog'), 'popularTags' => $popularTags, 'existingTags' => $existingTags, 'template-change-warning' => __('You are changing template. Be sure to save any changes.',array(),'blog'))) ?>