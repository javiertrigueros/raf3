<?php
class arTagActions extends BaseArActions
{
 
  public function executeSend(sfWebRequest $request)
  {
    $this->data =   array("status" => 500,
                    "errors" => array(),
                    "HTML" => '',
                    "values" => array());
    
    $this->form = new arTagForm();
    $formValues = $request->getParameter($this->form->getName()); 

    $this->form->bind($formValues);
    
     if ($this->checkView()
         && $request->isMethod(sfRequest::POST) 
         && ($this->form->isValid()))
     {
         $this->loadUser();
         
         $hash = trim($this->form->getValue('hash'));
         
         $arTag = Doctrine::getTable('arTag')->getByHash($hash);
         
         if (!($arTag && is_object($arTag)))
         {
            $arTag = $this->form->save(); 
         }
         
         // guarda los datos en la session
         $arTagLinks = $this->getUser()->getAttribute('arTag',array(),'wall');
         $arTagLinks[] = $arTag->getId();
         $this->getUser()->setAttribute('arTag', $arTagLinks, 'wall');

         $count = (int)$arTag->countTag($this->aUserProfile->getId());
         
         $this->data =   array("status" => 200,
                    "errors" => $this->form->getErrors(),
                    "HTML" => '',
                    "values" => array('id' => $arTag->getId(),
                                      'name' => $arTag->getName(),
                                      'hash' => $arTag->getHash(),
                                      'tag_url' => url_for('@wall?tag='.$arTag->getHash()),
                                      'encrypt_text' => $arTag->getEncryptTxt($this->aUserProfile),
                                      'count' => $count == 0 ? 1 : $count + 1
                        ));
        
    }
    else
    {
        $this->data =   array("status" => 500,
                    "errors" => $this->form->getErrors(),
                    "HTML" => 'entra en error',
                    "values" => $formValues);
    }
     
    //devuelve el contenido en Json de $this->data
    $this->returnJson();
      
  }
  
  public function executeCancel(sfWebRequest $request)
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
        
        $this->getUser()->setAttribute('arTag', $imageLinks, 'wall');
        
        $this->link->delete();
        $this->data =   array("status" => 200,
                            "errors" => array(),
                            "HTML" => '',
                            "values" => array());
     }
     
     
     $this->returnJson();
      
  }
}
  