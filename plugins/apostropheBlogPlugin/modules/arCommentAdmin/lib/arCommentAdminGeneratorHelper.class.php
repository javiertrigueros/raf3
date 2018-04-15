<?php

/**
 * arCommentAdmin module helper.
 *
 * @package    alcoor
 * @subpackage arCommentAdmin
 * @author     Your name here
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class arCommentAdminGeneratorHelper extends BaseArCommentAdminGeneratorHelper
{
    /*
  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return '<li class="a-admin-action-delete">'.link_to('<span class="icon"></span>'.__($params['label'], array(), 'a_admin'), 'ar_blog_post_delete', $object, array('class'=>'a-btn no-label icon a-delete alt', 'title' => 'Delete', 'method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'])).'</li>';
 }*/
 
 public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    $ret =  '<li class="a-admin-action-delete">';
    $ret .= ' <a data-toggle="modal" data-target="#delete-comment-modal-'.$object->getId().'" id="delete-post-'.$object->getId().'"  title="'.__('Delete', array(), 'sf_admin').'" href="'.url_for('@ar_comment_delete?id='.$object->getId()).'" class="a-btn no-label icon a-delete alt" >';
    $ret .= ' <span class="icon"></span>'.__('Delete', array(), 'sf_admin');
    $ret .= '</a>';
    
    include_js_call('arCommentAdmin/jsDeleteComment', array('arComment' => $object)); 
    
    return $ret;
  }
 
 
}
