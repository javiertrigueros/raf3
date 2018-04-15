<?php
/**
 * 
 * aUserAdmin module helper.
 * @package    sfShibbolethPlugin
 * @subpackage aUserAdmin
 * @author     Alex Gilbert
 * @version    SVN: $Id: aUserAdminGeneratorHelper.class.php 12896 2008-11-10 19:02:34Z fabien $
 */
class aUserAdminGeneratorHelper extends sfModelGeneratorHelper
{
  public function linkToNew($params)
  {
    return '<li class="a-admin-action-new">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'apostrophe'), $this->getUrlForAction('new'), array() ,array("class"=>"a-btn icon big a-add")).'</li>';
  }

  public function linkToEdit($object, $params)
  {
    return '<li class="a-admin-action-edit">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'apostrophe'), $this->getUrlForAction('edit'), $object, array('class'=>'a-btn icon no-label a-edit')).'</li>';
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }
    
    $ret =  '<li class="a-admin-action-delete">';
    $ret .= ' <a data-toggle="modal" data-target="#delete-comment-modal-'.$object->getId().'" id="delete-'.$object->getId().'"  title="'.__('Delete', array(), 'sf_admin').'" href="'.url_for('aUserAdmin/index').'/'.$object->getId().'" class="a-btn no-label icon a-delete alt" >';
    $ret .= ' <span class="icon"></span>'.__('Delete', array(), 'sf_admin');
    $ret .= '</a>';
    $ret .= '</li>';
    
    include_js_call('aUserAdmin/jsDeleteUser', array('arUser' => $object)); 
    
    return $ret;

    //return '<li class="a-admin-action-delete">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'apostrophe'), $this->getUrlForAction('delete'), $object, array('class'=>'a-btn icon no-label a-delete alt','method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'apostrophe') : $params['confirm'])).'</li>';
  }

  public function linkToList($params)
  {
    return '<li class="a-admin-action-list">'.link_to('<span class="icon"></span>'.__('Cancel', array(), 'apostrophe'), $this->getUrlForAction('list'), array(), array('class'=>'a-btn icon a-cancel alt')).'</li>';
  }

  public function linkToSave($object, $params)
  {
    return '<li class="a-admin-action-save">' . a_anchor_submit_button(a_('Save', array(), 'apostrophe'), array('a-save')) . '</li>';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if (!$object->isNew())
    {
      return '';
    }
    return '<li class="a-admin-action-save-and-add">' . a_anchor_submit_button(a_($params['label']), array('a-save'), '_save_and_add') . '</li>';
  }

  public function linkToSaveAndList($object, $params)
  {
    return '<li class="a-admin-action-save-and-list">' . a_anchor_submit_button(a_('Save'), array('a-save'), '_save_and_list') . '</li>';
  }
  

  public function getUrlForAction($action)
  {
    return 'list' == $action ? 'a_user_admin' : 'a_user_admin_'.$action;
  }
}
