<?php
/**
 * 
 * aUserAdmin module helper.
 * @package    sfShibbolethPlugin
 * @subpackage aUserAdmin
 * @author     Alex Gilbert
 * @version    SVN: $Id: aUserAdminGeneratorHelper.class.php 12896 2008-11-10 19:02:34Z fabien $
 */
class aGroupAdminGeneratorHelper extends BaseaGroupAdminGeneratorHelper
{
  

  public function linkToDelete($object, $params)
  {
   
    if ($object->isNew())
    {
      return '';
    }
    
    $ret =  '<li class="a-admin-action-delete">';
    $ret .= ' <a data-toggle="modal" data-target="#delete-comment-modal-'.$object->getId().'" id="delete-'.$object->getId().'"  title="'.__('Delete', array(), 'sf_admin').'" href="'.url_for('aGroupAdmin/index').'/'.$object->getId().'" class="a-btn no-label icon a-delete alt" >';
    $ret .= ' <span class="icon"></span>'.__('Delete', array(), 'sf_admin');
    $ret .= '</a>';
    $ret .= '</li>';
    
    include_js_call('aGroupAdmin/jsDeleteGroup', array('arObj' => $object)); 
    
    return $ret;

    //return '<li class="a-admin-action-delete">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'apostrophe'), $this->getUrlForAction('delete'), $object, array('class'=>'a-btn icon no-label a-delete alt','method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'apostrophe') : $params['confirm'])).'</li>';
  }

}
