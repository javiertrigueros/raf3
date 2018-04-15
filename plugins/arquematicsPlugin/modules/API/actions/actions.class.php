<?php
class APIActions extends BaseArActions
{
  public function preExecute()
  {
    sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial','a','ar'));
  }
  
  public function executeLogUserInfo(sfWebRequest $request)
  {
      if ($this->checkView())
      {
          $this->loadUser();
          
          $this->data = $this->aUserProfile->getData();
          
          $profileImage = $this->aUserProfile->getProfileImage();
       
          $this->data['icon'] = ($profileImage)?
               url_for("@user_resource?type=arProfileUpload&name=".$profileImage->getBaseName()."&format=".$profileImage->getExtension()."&size=mini"):
               '/arquematicsPlugin/images/unknown.mini.jpg';
          
          $this->data['url'] = url_for('@user_profile?username='.$this->aUserProfile->getUsername());
       
          $this->data['url_user_list'] = url_for('@user_list');

          $this->data['url_user_friend'] = url_for('@user_list_friends');

          $this->data['lang'] = $this->culture;
          
          $this->data['log_out'] = url_for(sfConfig::get('app_a_actions_logout', 'sf_guard_signout'));
          
          $this->data['cms_admin'] = $this->isCMSAdmin()?'true':'false';
          
          $this->returnJson(false);
      }
      else
      {
        throw new sfError404Exception(sprintf('Empty module and/or action after parsing the URL "%s" (%s/%s).', $request->getPathInfo(), 'API', 'LogUserInfo'));  
      }
  }
  
  /**
   * ejemplo para hacer un test con el correo electronico
   */
  public function executeSendMailTo()
  {
      $message = Swift_Message::newInstance()
           ->setFrom(array('javiertrigueros@arquematics.com' => 'arquematics.com'))
          ->setTo('javiertrigueros@gmail.com')
          //->setReplyTo('support@example.com')
          ->setSubject('Titulo del test')
          ->setBody('Body loren ipsum, loren iopsum loren iopsum loren iopsum');
        
        try {
           $failures = 0;
           $numOfMails = $this->getMailer()->send($message, $failures, 'local');      
        } catch(Exception $e) {
            $this->getUser()->setFlash('error', 'Error: ' . $e->getMessage());
        }
  }
  
  public function executeDeleteComment(sfRequest $request)
  {
 
      
      $comment = $this->getRoute()->getObject();
      
      if ($comment && is_object($comment))
      {
          $record_model = $request->getParameter('record_model');
          $record_id = (int)$request->getParameter('record_id');
          
          $objCommentTable = PluginCommentTable::getObject(
            $record_model,
            $record_id);
          
          $this->has_delete_roll = (is_object($objCommentTable) 
                  && $objCommentTable->canDelete());
    
          //mira si el usuario esta autentificado
          $this->user = $this->getUser();
          $this->is_auth_user = (is_object($this->user) 
                  &&  $this->user->isAuthenticated());

          
          if (($this->has_delete_roll && $this->is_auth_user)
                  || ($comment->isSelfComment($this->user)))
          {
               $comment_id = $comment->getId();
               
               try {
                   $comment->delete();
                   $data =   array("status" => 200,
                    "errors" => null,
                    "values" => array("comment_id" => $comment_id));
               }
               catch (Exception $e)
               {
                   $data =   array("status" => 500,
                    "errors" => null,
                    "values" => array("comment_id" => $comment_id));
                   
               }
               
                 
          }
          else
          {
               $data =   array("status" => 401,
                    "errors" => null,
                    "values" => array
                        ("has_delete_roll" => $this->has_delete_roll,
                        "is_auth_user" => $this->is_auth_user,
                        "record_model" => $record_model,
                        "record_id" => $record_id,
                        "comment_id" => $comment->getId()));  
          }
         
      }
      else
      {
           $data =   array("status" => 500,
                    "errors" => '',
                    "values" => array("comment_id" => $comment->getId()));  
      }
       
      
      
      
      $this->returnJSON($data);
  }

  public function executeSendComment(sfRequest $request)
  {
    $formValues = $request->getParameter('arquematicsComment');
    $data =   array("status" => '500',
                    "errors" => null,
                    "values" => $formValues);  
      
    if($request->isMethod(sfRequest::POST))
    {
        
        $this->form = new CommentForm(null, 
            array('name' => 'arquematicsComment',
                  'user' => $this->getUser()
            ));
        
        
        $this->form->bind( $formValues );
        if ($this->form->isValid())
        {
            $objCommentTable = PluginCommentTable::getObject(
            $formValues['record_model'],
            $formValues['record_id']);
    
            //es un objeto comentable por el usuario y valido podemos continuar
            if ($objCommentTable && $objCommentTable->canDoCreate())
            {
                $this->form->save();
                
                $comment = $this->form->getObject();
                  
                $this->has_delete_roll = $objCommentTable->canDelete();
    
                //mira si el usuario esta autentificado
                $this->user = $this->getUser();
                $this->is_auth_user = (is_object($this->user) &&  $this->user->isAuthenticated());

                
                $data =   array("status" => '200',
                    "HTML" => get_partial('comment/comment', 
                            array('obj' => $comment,
                                 'visible' => false,
                                 'user' =>  $this->user,
                                 'has_delete_roll' => $this->has_delete_roll, 
                                  'is_auth_user' => $this->is_auth_user))); 
            }
            else
            {
                $data =   array("status" => '500',
                    "errors" => $this->form->getErrors(),
                    "values" => $formValues);  
            } 
        }
        else
        {
             $data =   array("status" => '500',
                    "errors" => $this->form->getErrors(),
                    "values" => $formValues);  
        }
        
    }
    
    $this->returnJSON($data);
  }
  
  
  public function executeGetLocation(sfWebRequest $request)
	{
		$ressource = $request->getParameter('ressource');
		if(!empty($ressource))
		{
			$query = "SELECT ?location WHERE {{ <"
			. $ressource
			. "> <http://dbpedia.org/ontology/locationCity> ?location }}";
 
			$searchUrl = 'http://dbpedia.org/sparql?'
			. 'query='.urlencode($query)
			. '&format=json';
 
			if (!function_exists('curl_init')){
				die('CURL is not installed!');
			}
 
			$ch= curl_init();
			curl_setopt($ch, CURLOPT_URL, $searchUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
 
			return $this->renderText( json_encode($response));
		}
       }
}
  