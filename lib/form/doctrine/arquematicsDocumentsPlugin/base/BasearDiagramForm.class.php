<?php

/**
 * arDiagram form base class.
 *
 * @method arDiagram getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearDiagramForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'guid'          => new sfWidgetFormInputText(),
      'title'         => new sfWidgetFormTextarea(),
      'json'          => new sfWidgetFormTextarea(),
      'data_image'    => new sfWidgetFormTextarea(),
      'type'          => new sfWidgetFormInputText(),
      'user_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
      'messages_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage')),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'guid'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'title'         => new sfValidatorString(array('required' => false)),
      'json'          => new sfValidatorString(array('required' => false)),
      'data_image'    => new sfValidatorString(array('required' => false)),
      'type'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
      'messages_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arWallMessage', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'arDiagram', 'column' => array('guid')))
    );

    $this->widgetSchema->setNameFormat('ar_diagram[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDiagram';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['messages_list']))
    {
      $this->setDefault('messages_list', $this->object->Messages->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveMessagesList($con);

    parent::doSave($con);
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

}
