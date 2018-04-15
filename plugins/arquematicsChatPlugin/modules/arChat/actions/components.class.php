<?php


class arChatComponents extends sfComponents
{
  
  public function executeNormalView()
  {
   $this->arHasToken = $this->getUser()->isAuthenticated();

   
   if ($this->arHasToken)
   {
       $chatToken = new arChatToken();
       
       $chatToken->save();
       
       $this->arToken = $chatToken->getToken();
       
       
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/jxhr.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/base64.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/jsjac.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/jquery.timers.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/const.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/datastore.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/browser-detect.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/common.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/date.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/links.js");
       $this->getResponse()->addJavascript("/arquematicsChatPlugin/js/min.js");
       
   }

  }
  
}