<?php

class arBlogEventForm extends arBlogItemForm
{
  protected $engine = 'aEvent';
  
  protected $hasEndDate = false;
      
  public function setup()
  {
    parent::setup();
   
    $aUser = sfContext::getInstance()->getUser();
    
    $this->setWidget('start_date', new arDateWidget(array('culture' => $aUser->getCulture()), 
                array('class' => 'date-start no-borders ui-control-text-input span7',
                      'autocomplete'=>'off')));
    
    $this->setValidator('start_date', new sfValidatorArDate(array(
        'datetime_output' => 'Y-m-d',
        'format' => aDate::getDateFormatPHP($aUser->getCulture()),
        'required' => false)));

    $this->setWidget('start_time', new arTimeWidget(array('culture' => $aUser->getCulture()), array('class' => 'start-time no-borders ui-control-text-input span4','autocomplete'=>'off')));
    $this->setValidator('start_time', new sfValidatorTime(array('required' => false)));

    $this->setWidget('end_date', new arDateWidget(array('culture' => $aUser->getCulture()), 
                array('class' => 'date-end no-borders ui-control-text-input span7 hide',
                      'autocomplete'=>'off')));

    $this->setValidator('end_date', new sfValidatorArDate(array(
        'datetime_output' => 'Y-m-d',
        'format' => aDate::getDateFormatPHP($aUser->getCulture()),
        'required' => false)));

    $this->setWidget('end_time', new arTimeWidget(array('culture' => $aUser->getCulture()), array('class' => 'end-time no-borders ui-control-text-input span4 hide','autocomplete'=>'off')));
    $this->setValidator('end_time', new sfValidatorTime(array('required' => false)));

    $this->setWidget('location', new sfWidgetFormTextarea());
    $this->setValidator('location', new sfValidatorString(array('required' => false)));

       
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateEndDate')))
    );
    
    $this->widgetSchema->setNameFormat('a_blog_post[%s]');
  }
  
  
 
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    $aEvent = $this->getObject();
    
    if (isset($this->widgetSchema['start_date']))
    {
        
        $this->setDefault('start_date',  $aEvent->hasStartDate()?$aEvent->getStartDate():time());
    }
    
    if (isset($this->widgetSchema['start_time']))
    {
        $this->setDefault('start_time',  $aEvent->hasStartDate()?$aEvent->getStartTime():time());
    }
    
    if (isset($this->widgetSchema['end_date']))
    {
        $this->setDefault('end_date',  $aEvent->hasEndDate()?$aEvent->getEndDate():time());
    }
    
    if (isset($this->widgetSchema['end_time']))
    {
        
       $this->setDefault('end_time',  $aEvent->hasEndDate()?$aEvent->getEndTime():time());
    }

  }
  
  protected function doSave($conn = null)
  {

     $blogObject = $this->getObject();
     
     if (!$this->hasEndDate)
     {
       $blogObject->setEndDate(null);
       $blogObject->setEndTime(null);  
     }
    
     
     parent::doSave($conn);
  }
  
  
  public function validateEndDate($validator, $values)
  {
    if (isset($values['start_date']) && isset($values['start_time'])
        && isset($values['end_date']) && isset($values['end_time']))
    {
        $startDateTime = $values['start_date'] . ' ' . $values['start_time'];
        $endDateTime = $values['end_date'] . ' ' . $values['end_time'];
       
        $start = aDate::normalize($startDateTime);
        $end = aDate::normalize($endDateTime);
        
        
        if ($end < $start)
        {
            // Technically the problem might be the date but we show them on one row
            // anyway so always attach the error to the time which is easier to style
            $error = new sfValidatorError($validator, 'Ends before it begins!');
            throw new sfValidatorErrorSchema($validator, array('end_date' => $error));  
        }
        
         $this->hasEndDate = true;
    }
    else if (isset($values['start_date']) && isset($values['start_time']))
    {
       $this->hasEndDate = false;
    }
    else if (isset($values['end_date']) && isset($values['end_time']))
    {
        $error = new sfValidatorError($validator, 'Ends and not begins!');
        throw new sfValidatorErrorSchema($validator, array('end_date' => $error));   
    }
      
    return $values;
  }
  
  

  public function getModelName()
  {
    return 'aEvent';
  }

}
