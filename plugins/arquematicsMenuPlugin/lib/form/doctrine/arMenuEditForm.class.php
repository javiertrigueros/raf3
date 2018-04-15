<?php

/**
 * PluginarMenu form.
 *
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class arMenuEditForm extends PluginarMenuForm
{
  
  public function setup()
  {
      parent::setup();
      
    
       unset(
           $this['id'],
           $this['url'],
           //$this['name'],
           $this['lft'],
           $this['rgt'],
           $this['slug'],
           $this['level'],
           $this['menu_type'],
           //$this['page_id'], 
           $this['created_at'],
           $this['updated_at']
        );
       
       $this->widgetSchema['page_id'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['page_id']  = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Page'), 'required' => true));
       
       $this->widgetSchema['slot_name'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['slot_name']= new sfValidatorString(array('required' => true));
       
       $this->widgetSchema['permid'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['permid']= new sfValidatorString(array('required' => true));
       
       $this->widgetSchema['root_id'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['root_id'] = new sfValidatorDoctrineChoice(array('column' => 'root_id', 'model' => 'arMenu', 'required' => true));

       $this->widgetSchema['name'] = new sfWidgetFormInput(array(), array('class' => 'span7 no-borders ui-control-text-input','autocomplete'=>'off'));
       $this->validatorSchema['name']= new sfValidatorString(array('required' => true));
       
       $this->widgetSchema['dataJson'] = new sfWidgetFormInputHidden();
       $this->validatorSchema['dataJson']= new sfValidatorString(array('required' => true));
    
       $treeMenuNodes = $this->getOption('treeMenuNodes');
          
       if ($treeMenuNodes && (count($treeMenuNodes) > 0))
       {
           $rootNode = $treeMenuNodes->getFirst();
           $this->setDefault('name', $rootNode->getName());
           $this->setDefault('root_id', $rootNode->getRootId());
       }
       
       $this->setDefault('slot_name', $this->getOption('slot_name'));
       $this->setDefault('permid', $this->getOption('permid'));
       $this->setDefault('page_id', $this->getOption('page_id'));
       
       $this->widgetSchema->setNameFormat('arMenu[%s]');
  }
  
}
