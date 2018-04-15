<?php

/**
 * PluginsfGuardUser form.
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage filter
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: PluginsfGuardUserFormFilter.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
abstract class PluginsfGuardUserFormFilter extends BasesfGuardUserFormFilter
{
  public function setup()
  {
     parent::setup();
     
     $this->widgetSchema['username'] = new sfWidgetFormInput();
     $this->validatorSchema['username'] = new sfValidatorPass(array('required' => false));
     
     $this->widgetSchema['is_active'] = new sfWidgetFormChoice(array('choices' => array('' => __('yes or no',null,'apostrophe'), 1 => __('yes',null,'apostrophe'), 0 => __('no',null,'apostrophe'))));
     $this->validatorSchema['is_active'] = new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0)));
     
     $this->widgetSchema['is_super_admin'] = new sfWidgetFormChoice(array('choices' => array('' => __('yes or no',null,'apostrophe'), 1 => __('yes',null,'apostrophe'), 0 => __('no',null,'apostrophe'))));
     $this->validatorSchema['is_super_admin'] = new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0)));
     
     $this->widgetSchema['created_at'] = new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false));
     
     $this->widgetSchema->setFormFormatterName('list');
  }
}
