<?php

/**
 * 
 * @param <$aBlogItem $aBlogItem>
 * @return <string>
 */
function getAuthor($aBlogItem, $showLink = false)
{
    $ret = '';
    $author = $aBlogItem->getAuthor();
    
    if ($author && is_object($author))
    {
        if ($showLink)
        {
            //TODO: javier de momento solo dos tipos
            $route = ($aBlogItem->getType() === 'post')?
                '@a_blog_author?' . http_build_query(array('author' => $author->username)):
                '@a_event_author?' . http_build_query(array('author' => $author->username));
            
            $ret =  link_to($author->getName() ? aHtml::entities($author->getName()) : aHtml::entities($author), $route); 
        }
        else {
            $ret =  $author->getName() ? aHtml::entities($author->getName()) : aHtml::entities($author);  
        }
    }
    
    return $ret;
}

/**
 * devuelve la fecha de inicio de un evento formateada
 * 
 * @param <aEvent $aBlogEvent>
 * @return <string>
 */
function getTextEventStart($aBlogEvent, $culture)
{
    $ret = "";
    
    if ($aBlogEvent->isDateTimeDefined())
    {
      $format = aDate::getDateFormatPHP($culture);
      $when = aDate::normalize($aBlogEvent['start_date']);
      $date = date($format, $when);
      $time = date('H:i',aDate::normalize($aBlogEvent['start_time']));
    
      $ret = __('Start date %datetime%',array('%datetime%' => $date.' '. $time),'blog');
    }
    else {
      $ret = __('Undefined start date',array(),'blog');  
    }
    
    
    return $ret;
}
/**
 * devuelve la fecha de inicio de un evento formateada
 * 
 * @param <aEvent $aBlogEvent>
 * @return <string>
 */
function getTextEventEnd($aBlogEvent, $culture)
{
    $ret = "";
    
    if ($aBlogEvent->isDateTimeDefined())
    {
      $format = aDate::getDateFormatPHP($culture);
      $when = aDate::normalize($aBlogEvent['end_date']);
      $date = date($format, $when);
      $time = date('H:i',aDate::normalize($aBlogEvent['end_time']));
    
      $ret = __('End date %datetime%',array('%datetime%' => $date.' '. $time),'blog');
    }
    else {
      $ret = __('Undefined start date',array(),'blog');  
    }
    
    return $ret;
}

/**
 * devuelve la fecha de un blogItem formateada
 * @param <obj $aBlogItem>
 * @return <string>
 */
function getDateTimeForEdit($aBlogItem)
{
    $ret = "";
    
    if (!$aBlogItem->getIsPublish() && (!$aBlogItem->getIsSave()))
    {
        $ret = __('Publish now',array(),'blog');
    }
    else  if ($aBlogItem->isPassed())
    {
        $ret = __('Published %time%',array('%time%' => aDate::medium($aBlogItem['published_at']).' '.aDate::time($aBlogItem['published_at']) ),'blog'); 
    }
    else
    {
       $ret = __('Scheduled on %time%',array('%time%' => aDate::medium($aBlogItem['published_at']).' '.aDate::time($aBlogItem['published_at']) ),'blog'); 
    }
    
    return $ret;
}
/**
 * devuelve el texto con el estado
 * 
 * @param <aBlogItem $aBlogItem>
 * @return string
 */
function getStatusText($aBlogItem)
{
    $ret = '';
    
    if (!$aBlogItem->getIsPublish())
    {
        $ret = __('Draft',array(),'blog');
    }
    else if ($aBlogItem->getStatus() == 'published') 
    {
        $ret = __('Published',array(),'blog');
    }
    else
    {
        $ret = __('Publication scheduled',array(),'blog');
    }
    
    return $ret;
    
}

function getCommentsStatus($aBlogItem)
{
    return $aBlogItem->getAllowComments()?
            __('Enabled',array(),'blog'):
            __('Disabled',array(),'blog');
}

function getNoFilterHTMLtags()
  {
      $ret = "";
      $allowedTags = sfConfig::get('app_arquematics_comments_allowed_tags', array());
      $allowedAttributes = sfConfig::get('app_arquematics_comments_allowed_attributes', array());
      $allowedStyles = sfConfig::get('app_arquematics_comments_allowed_styles', array());
    
      if ($allowedTags 
              && is_array($allowedTags) 
              && (count($allowedTags) > 0))
      {
          
          foreach ($allowedTags as $tag)
          {
              $ret .= " <$tag";
              if ($allowedAttributes 
                  && is_array($allowedAttributes)
                  && isset($allowedAttributes[$tag]))
              {
                  foreach ($allowedAttributes[$tag] as $attribute)
                  {
                     if (($attribute == 'style')
                        && is_array($allowedStyles)
                        && isset($allowedStyles[$tag]))
                     {
                       $ret .= " $attribute='".  implode('|',$allowedStyles[$tag])."'";                   
                     }
                     else
                     {
                       $ret .= " $attribute=''"; 
                     }
                  }
              }
              $ret .= '>';
          }
      }
      return htmlentities($ret, ENT_QUOTES);;
  }


?>