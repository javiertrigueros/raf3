<?php

/**
 * arGmapsLocate form base class.
 *
 * @method arGmapsLocate getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearGmapsLocateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'hash'                => new sfWidgetFormInputText(),
      'formated_address'    => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'user_locations_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
      'messages_list'       => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage')),
      'gmaps_locate_list'   => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hash'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'formated_address'    => new sfValidatorString(array('max_length' => 255)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
      'user_locations_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
      'messages_list'       => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage', 'required' => false)),
      'gmaps_locate_list'   => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'arGmapsLocate', 'column' => array('hash')))
    );

    $this->widgetSchema->setNameFormat('ar_gmaps_locate[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arGmapsLocate';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['user_locations_list']))
    {
      $this->setDefault('user_locations_list', $this->object->UserLocations->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['messages_list']))
    {
      $this->setDefault('messages_list', $this->object->Messages->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['gmaps_locate_list']))
    {
      $this->setDefault('gmaps_locate_list', $this->object->GmapsLocate->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveUserLocationsList($con);
    $this->saveMessagesList($con);
    $this->saveGmapsLocateList($con);

    parent::doSave($con);
  }

  public function saveUserLocationsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['user_locations_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->UserLocations->getPrimaryKeys();
    $values = $this->getValue('user_locations_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('UserLocations', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('UserLocations', array_values($link));
    }
  }

  public function saveMessagesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['messages_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Messages->getPrimaryKeys();
    $values = $this->getValue('messages_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Messages', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Messages', array_values($link));
    }
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
