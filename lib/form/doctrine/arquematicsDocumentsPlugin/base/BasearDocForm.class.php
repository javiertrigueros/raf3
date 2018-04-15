<?php

/**
 * arDoc form base class.
 *
 * @method arDoc getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearDocForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'group_id'       => new sfWidgetFormInputText(),
      'pad_write'      => new sfWidgetFormInputText(),
      'pad_read'       => new sfWidgetFormInputText(),
      'pass'           => new sfWidgetFormInputText(),
      'type'           => new sfWidgetFormInputText(),
      'created_at'     => new sfWidgetFormDateTime(),
      'updated_at'     => new sfWidgetFormDateTime(),
      'messages_list'  => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arDoc')),
      'documents_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arDoc')),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'group_id'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pad_write'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pad_read'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pass'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type'           => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'     => new sfValidatorDateTime(),
      'updated_at'     => new sfValidatorDateTime(),
      'messages_list'  => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arDoc', 'required' => false)),
      'documents_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arDoc', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ar_doc[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arDoc';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['messages_list']))
    {
      $this->setDefault('messages_list', $this->object->Messages->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['documents_list']))
    {
      $this->setDefault('documents_list', $this->object->Documents->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveMessagesList($con);
    $this->saveDocumentsList($con);

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

  public function saveDocumentsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['documents_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Documents->getPrimaryKeys();
    $values = $this->getValue('documents_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Documents', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Documents', array_values($link));
    }
  }

}
