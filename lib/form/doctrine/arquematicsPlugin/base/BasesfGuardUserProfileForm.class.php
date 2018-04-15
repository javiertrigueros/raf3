<?php

/**
 * sfGuardUserProfile form base class.
 *
 * @method sfGuardUserProfile getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'email_address'     => new sfWidgetFormInputText(),
      'username'          => new sfWidgetFormInputText(),
      'user_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'first_last'        => new sfWidgetFormInputText(),
      'password'          => new sfWidgetFormInputText(),
      'description'       => new sfWidgetFormTextarea(),
      'address'           => new sfWidgetFormTextarea(),
      'key_saved'         => new sfWidgetFormInputCheckbox(),
      'public_key'        => new sfWidgetFormInputText(),
      'store_key'         => new sfWidgetFormInputText(),
      'phone'             => new sfWidgetFormInputText(),
      'public_mail_key'   => new sfWidgetFormTextarea(),
      'private_mail_key'  => new sfWidgetFormTextarea(),
      'facebook_uid'      => new sfWidgetFormInputText(),
      'profile_data'      => new sfWidgetFormTextarea(),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'gmaps_locate_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'email_address'     => new sfValidatorString(array('max_length' => 255)),
      'username'          => new sfValidatorString(array('max_length' => 128)),
      'user_id'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'required' => false)),
      'first_last'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'password'          => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'description'       => new sfValidatorString(array('required' => false)),
      'address'           => new sfValidatorString(array('required' => false)),
      'key_saved'         => new sfValidatorBoolean(array('required' => false)),
      'public_key'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'store_key'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'phone'             => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'public_mail_key'   => new sfValidatorString(array('required' => false)),
      'private_mail_key'  => new sfValidatorString(array('required' => false)),
      'facebook_uid'      => new sfValidatorInteger(array('required' => false)),
      'profile_data'      => new sfValidatorString(array('required' => false)),
      'created_at'        => new sfValidatorDateTime(),
      'updated_at'        => new sfValidatorDateTime(),
      'gmaps_locate_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUserProfile', 'column' => array('email_address'))),
        new sfValidatorDoctrineUnique(array('model' => 'sfGuardUserProfile', 'column' => array('username'))),
      ))
    );

    $this->widgetSchema->setNameFormat('sf_guard_user_profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'sfGuardUserProfile';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['gmaps_locate_list']))
    {
      $this->setDefault('gmaps_locate_list', $this->object->GmapsLocate->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveGmapsLocateList($con);

    parent::doSave($con);
  }

  public function saveGmapsLocateList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['gmaps_locate_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->GmapsLocate->getPrimaryKeys();
    $values = $this->getValue('gmaps_locate_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('GmapsLocate', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('GmapsLocate', array_values($link));
    }
  }

}
