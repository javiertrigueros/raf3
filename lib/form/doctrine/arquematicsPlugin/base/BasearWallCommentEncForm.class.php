<?php

/**
 * arWallCommentEnc form base class.
 *
 * @method arWallCommentEnc getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearWallCommentEncForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'user_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'wall_comment_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Comment'), 'add_empty' => true)),
      'content'         => new sfWidgetFormTextarea(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'wall_comment_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Comment'), 'required' => false)),
      'content'         => new sfValidatorString(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ar_wall_comment_enc[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arWallCommentEnc';
  }

}
