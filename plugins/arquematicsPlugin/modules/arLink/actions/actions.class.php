<?php
class arLinkActions extends BaseArActions
{

  public function executeSendLink(sfWebRequest $request)
  {

    if ($this->checkView())
    {
        $this->loadUser();

        $this->form = new arWallLinkForm( null, array('aUserProfile' => $this->aUserProfile));
        $formValues = $request->getParameter($this->form->getName()); 

        $this->form->bind($formValues);
    
        if ($request->isMethod(sfRequest::POST) 
         && ($this->form->isValid()))
        {
            $arWallLink = $this->form->save();

            // guarda los datos en la session
            $imageLinks = $this->getUser()->getAttribute('arWallLink',array(),'wall');
            $imageLinks[] = $arWallLink->getId();
            $this->getUser()->setAttribute('arWallLink', $imageLinks, 'wall');
                
            $this->getUser()->setAttribute('activeTool', 'arWallLink', 'wall');
            
            $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => get_partial('arLink/wallLink', array('preview' => true, 'link' => $arWallLink)),
                    "values" => array("id" => $arWallLink->getId()));
        }
        else
        {
            $this->data =   array("status" => 500,
                "errors" => $this->form->getErrors(),
                "HTML" => '',
                "values" => $formValues);
        }
    }
    else
    {
        $this->data =   array("status" => 500,
                "errors" => $this->form->getErrors(),
                "HTML" => '',
                "values" => $formValues);
    }
    //devuelve el contenido en Json de $this->data
    $this->returnJson();
  }
 /*
  public function executeSendLink2(sfWebRequest $request)
  {
    $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array());
    
    $this->form = new arWallLinkForm();
    $formValues = $request->getParameter($this->form->getName()); 

    $this->form->bind($formValues);
    
     if ($this->checkView()
         && $request->isMethod(sfRequest::POST) 
         && ($this->form->isValid()))
     {
         $this->loadUser();
         
         try
         {
             $file = $this->form->getValue('thumb');
             
             $arWallLink = new arWallLink();
             
             if($file)
             {
                $fileName = $file->save();
                 
                $arWallLink->setFileName($fileName);
                $arWallLink->setName($fileName);
                $arWallLink->setMimeContentType($file->getType());
                $arWallLink->setUserId($this->userid);
                $arWallLink->setHasThumb(true);         
             }
             else
             {
                 $arWallLink->getHasThumb(false);
             }
             
             $arWallLink->setUrl($formValues['url']);
             
             unset($formValues['_csrf_token']);
             $data = json_encode($formValues,JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
             
             $arWallLink->setOembed($data);
             $linkType = strtolower($formValues['type']);
             $linkType = ($linkType === 'rich')?'video':$linkType;
             $arWallLink->setLinkType($linkType);
            
             $arWallLink->save();
            
            
            // guarda los datos en la session
            $imageLinks =  $imageLinksA  = $this->getUser()->getAttribute('arWallLink',array(),'wall');
            $imageLinks[] = $arWallLink->getId();
            $this->getUser()->setAttribute('arWallLink', $imageLinks, 'wall');
                
            $this->getUser()->setAttribute('activeTool', 'arWallLink', 'wall');

            session_write_close();
            
            $this->data =   array("status" => 200,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array("id" => $arWallLink->getId(),
                                      "vamos" => $imageLinks,
                                      'vamosend' =>  $imageLinksA,
                                      "has_thumb" => $arWallLink->getHasThumb(),
                                      "thumb_link" => url_for("@user_resource?type=arWallLink&name=".$arWallLink->getBaseName()."&format=".$arWallLink->getExtension()."&size=external_small"),
                                      "url" => $arWallLink->getUrl(),
                                      "oembed" => $formValues));
          
         }
         catch (Exception $e)
         {
              $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $formValues);
         }
    }
    else
    {
            $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => $formValues);
    }
     
     
     //devuelve el contenido en Json de $this->data
     $this->returnJson();
      
  }*/
  
  public function executeCancelLink(sfWebRequest $request)
  {
    
     
     $this->link = $this->getRoute()->getObject();
     
     $imageLinks = $this->getUser()->getAttribute('arWallLink',array(),'wall');
                
     $this->getUser()->setAttribute('activeTool', 'arWallLink', 'wall');
     
     if ($this->checkView()
         && $request->isMethod(sfRequest::POST) 
         && (is_object($this->link)) 
         && in_array($this->link->getId(),$imageLinks))
     {
        if(($key = array_search($this->link->getId(), $imageLinks)) !== false) 
        {
            unset($imageLinks[$key]);
        }
        
        $this->getUser()->setAttribute('arWallLink', $imageLinks, 'wall');
        
        $this->link->delete();
        $this->data =   array("status" => 200,
                            "errors" => array(),
                            "HTML" => '',
                            "values" => array());
     }
     
     
     $this->returnJson();
      
  }
}
  