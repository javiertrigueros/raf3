<?php
/**
 * devuelve el título original 
 * 
 * @param <arMenu $node>
 * @return <string>
 */
function getOriginalMenuName($node)
{
    $ret = '';
    if ($node && is_object($node))
    {
       if ($node->getMenuType() == 'page')
       {
           $page = aPageTable::retrieveByIdWithSlots($node->getPageId());
           $ret = $page->getTitle();
       }
       else if (($node->getMenuType() == 'event')
               || ($node->getMenuType() == 'blog'))
       {
           $cat = Doctrine_Core::getTable('aCategory')
                   ->retrieveById($node->getCategoryId());
           
           $ret = $cat->getName();
       }
        
    }
    
    return $ret;
}

function getPageType($node)
{
    $ret = "";

    if (strlen(trim($node['engine'])) >= 0)
    {
        if (($node['engine'] === 'aBlog'))
        {
           $ret = 'blog';
        }
        else if (($node['engine'] === 'aEvent'))
        {
           $ret =  'events';
        }
        else
        {
           $ret =  'page'; 
        }
    }
        
    return $ret;
}

function getPageTypeText($node)
{
    $ret = "";

    if (strlen(trim($node['engine'])) >= 0)
    {
        if (($node['engine'] === 'aBlog'))
        {
           $ret =  __('Blog', null, 'configure');
        }
        else if (($node['engine'] === 'aEvent'))
        {
           $ret =  __('Events', null, 'configure');
        }
        else
        {
           $ret =  __('Page', null, 'configure'); 
        }
    }
        
    return $ret;                                           
}

function getTree2($treeDataNodes)
{
   $ret = "";
   if (count($treeDataNodes) > 0)
   {
       $ret .='<ul>';
       $class = '';
       $subTree = '';
       foreach ($treeDataNodes as $node)
       {
          if (isset($node['children']) 
              && (count($node['children']) > 0))
          {
            $subTree = getTree($node['children']);
            $class = 'jstree-open jstree-drop';
          }
          else {
            $subTree = '';
            $class = 'jstree-leaf jstree-drop';
          }

          
          $ret .='<li class="'.$class.'" data-title="'.$node['title'].'" data-slug="'.$node['slug'].'" data-id="'.$node['id'].'" id="node-'.$node['id'].'">';
          $ret .=' <a href="#">'.$node['title'].'</a>';
          $ret .= $subTree;
          $ret .='</li>';
         
          
       }
       $ret .='</ul>';
   }
   return $ret;
}
    



?>
