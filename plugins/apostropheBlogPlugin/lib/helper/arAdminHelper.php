<?php

  function linkToEditPost($object)
  {
    return '<li class="a-admin-action-edit"><a title="'.__('Edit', array(), 'sf_admin').'" class="a-btn icon a-edit no-label" href="'.url_for('@ar_blog_post_edit?page_back='.arMenuInfo::ADMINBLOG.'&id='.$object->getId()).'"><span class="icon"></span>'.__('Edit', array(), 'sf_admin').'</a></li>';
  }

  function linkToDeletePost($object)
  {
    if ($object->isNew())
    {
      return '';
    }

    $ret =  '<li class="a-admin-action-delete">';
    $ret .= ' <a data-toggle="modal"  id="delete-'.$object->getId().'"  title="'.__('Delete', array(), 'sf_admin').'" href="#" class="a-btn no-label icon a-delete alt" >';
    $ret .= ' <span class="icon"></span>'.__('Delete', array(), 'sf_admin');
    $ret .= '</a>';
    
    include_js_call('aBlogAdmin/jsDeleteBlogItem', array('aBlogItem' => $object)); 
    
    return $ret;
  }
   
  function linkToEditEvent($object)
  {
    return '<li class="a-admin-action-edit"><a title="'.__('Edit', array(), 'sf_admin').'" class="a-btn icon a-edit no-label" href="'.url_for('@ar_blog_event_edit?page_back='.arMenuInfo::ADMINEVENTS.'&id='.$object->getId()).'"><span class="icon"></span>'.__('Edit', array(), 'sf_admin').'</a></li>';
  }

  function linkToDeleteEvent($object)
  {
    if ($object->isNew())
    {
      return '';
    }
    
    $ret =  '<li class="a-admin-action-delete">';
    $ret .= ' <a data-toggle="modal" data-target="#delete-modal-go-'.$object->getId().'" id="delete-'.$object->getId().'"  title="'.__('Delete', array(), 'sf_admin').'" href="'.url_for('@ar_blog_event_delete?id='.$object->getId()).'" class="a-btn no-label icon a-delete alt" >';
    $ret .= ' <span class="icon"></span>'.__('Delete', array(), 'sf_admin');
    $ret .= '</a>';
    
    include_js_call('aEventAdmin/jsDeleteEventItem', array('aEventItem' => $object)); 
    
    return $ret;
   }
   
   function linkToEditCat($object)
   {
       
     return '<li class="a-admin-action-edit"><a title="'.__('Edit', array(), 'sf_admin').'" class="a-btn icon a-edit no-label" href="'.url_for('@a_category_admin_edit?id='.$object->id).'"><span class="icon"></span>'.__('Edit', array(), 'sf_admin').'</a></li>';  
   }
   
   function linkToDeleteCat($object)
   {
    if ($object->isNew())
    {
      return '';
    }
    
    $ret =  '<li class="a-admin-action-delete">';
    $ret .= ' <a data-toggle="modal" data-target="#delete-modal-go-'.$object->getId().'" id="delete-'.$object->getId().'"  title="'.__('Delete', array(), 'sf_admin').'" href="'.url_for('@ar_blog_event_delete?id='.$object->getId()).'" class="a-btn no-label icon a-delete alt" >';
    $ret .= ' <span class="icon"></span>'.__('Delete', array(), 'sf_admin');
    $ret .= '</a>';
    
    include_js_call('aCategoryAdmin/jsDelete', array('object' => $object)); 
    
    return $ret;
   }


?>