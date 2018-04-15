<?php

/**
 * PluginarComment form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarCommentForm extends BasearCommentForm
{
  public function setup()
  {
    parent::setup();

    unset(
      $this['id'],
      $this['comment_author'],
      $this['comment_author_email'], 
      $this['comment_author_url'],
      $this['user_id'],
      $this['created_at'],
      $this['updated_at']
    );
    
    $userProfile = $this->getOption('aUserProfile');
    
    if (!($userProfile && is_object($userProfile)))
    {
        
       $this->widgetSchema['comment_author'] = new sfWidgetFormInput(array(), array('autocomplete'=>'off', 'class' => 'focus-control form-control'));
       $this->validatorSchema['comment_author'] = new sfValidatorString(array('required' => true));
       $this->validatorSchema['comment_author']->setMessage('required', __('Required', array(), 'arquematics'));
       
       $this->widgetSchema['comment_author_email'] = new sfWidgetFormInput(array(), array('autocomplete'=>'off','class' => 'form-control'));
       $this->validatorSchema['comment_author_email'] = new sfValidatorEmail(
                                                                array('required' => true), 
                                                                array('invalid' => __('Email address is invalid',array(),'arquematics')));
       $this->validatorSchema['comment_author_email']->setMessage('required', __('Required', array(), 'arquematics'));
       
       
       $this->widgetSchema['comment_author_url'] = new sfWidgetFormInput(array(), array('autocomplete'=>'off','class' => 'form-control'));
       $this->validatorSchema['comment_author_url'] = new sfValidatorDomain(array('required' => false));
       $this->validatorSchema['comment_author_url']->setMessage('invalid', __('Invalid', array(), 'arquematics'));
        
    }
    else
    {
       $this->widgetSchema['user_id'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['user_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => true));
    }
    
    
    $this->widgetSchema['a_blog_item_id'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['a_blog_item_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('BlogItem'), 'required' => true));
   
    $this->widgetSchema['parent'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['parent'] = new sfValidatorDoctrineChoice(array('model' => 'arComment', 'required' => false));
    
    
    $this->widgetSchema['comment'] = new sfWidgetFormTextarea(array(), array('autocomplete'=>'off','class' => 'focus-control form-control'));
    $this->validatorSchema['comment'] = new sfValidatorHtml(array('required' => true, 
                                            'allowed_tags' => sfConfig::get('app_arquematics_comments_allowed_tags', array()),
                                            'allowed_attributes' => sfConfig::get('app_arquematics_comments_allowed_attributes', array()),
                                            'allowed_styles' => sfConfig::get('app_arquematics_comments_allowed_styles', array())));
    $this->validatorSchema['comment']->setMessage('required', __('Required', array(), 'arquematics'));
    
   
    
    $this->widgetSchema['ip'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['ip'] = new sfExtraValidatorIp(array('required' => true));
    
    $this->widgetSchema['comment_agent'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['comment_agent'] = new sfValidatorString(array('required' => true));
    
    $this->validatorSchema->setPostValidator(
            new sfValidatorCallback(array('callback' => array($this, 'checkCallback')))
      );

    $this->widgetSchema->setNameFormat('ar_comment[%s]');
  }
  
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    $aBlogItem = $this->getOption('aBlogItem');

    if (isset($this->widgetSchema['a_blog_item_id'])
        && $aBlogItem && is_object($aBlogItem)
        && ($aBlogItem->getId() > 0))
    {
      $this->setDefault('a_blog_item_id', $aBlogItem->getId());
    }
    
    $userProfile = $this->getOption('aUserProfile');
    
    if (isset($this->widgetSchema['user_id'])
        && $userProfile && is_object($userProfile))
    {
       $this->setDefault('user_id', $userProfile->getId()); 
    }

  }
  
  public function checkCallback($validator, $values)
  {
      $aBlogItem = Doctrine_Core::getTable('aBlogItem')
              ->retrieveById($values['a_blog_item_id']);

      if ($aBlogItem 
          && is_object($aBlogItem) 
          && (!$aBlogItem->getAllowComments()))
      {
          throw new sfValidatorError($validator, 'Not Allow Comments');
      }
  
      return $values;
  }
}
