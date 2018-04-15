<?php

/**
 * PluginarMenu form.
 *
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginarMenuForm extends BasearMenuForm
{
    
  
  public function setup()
  {
      parent::setup();
      
      
       unset(
           $this['id'],
           $this['url'],
           $this['lft'],
           $this['rgt'],
           $this['slug'],
           $this['level'],
           $this['menu_type'],
           $this['created_at'],
           $this['updated_at']
        );
       
       $this->widgetSchema['permid'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['permid']= new sfValidatorString(array('required' => true));
       
       $this->widgetSchema['slot_name'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['slot_name']= new sfValidatorString(array('required' => true));
       
       $this->widgetSchema['page_id'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['page_id']  = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'required' => true));
       
       $this->widgetSchema['root_id'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['root_id']= new sfValidatorDoctrineChoice(array('column' => 'root_id', 'model' => 'arMenu', 'required' => true));

       $this->widgetSchema['name'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['name']= new sfValidatorString(array('required' => true));
       //valores por defecto
       $this->setDefault('root_id', $this->getOption('root_id'));
       $this->setDefault('page_id', $this->getOption('page_id'));
       $this->setDefault('name', $this->getOption('name'));
       $this->setDefault('permid', $this->getOption('permid'));
       $this->setDefault('slot_name', $this->getOption('slot_name'));
       
       
       $this->widgetSchema->setNameFormat('arMenu[%s]');
  }
  
}
