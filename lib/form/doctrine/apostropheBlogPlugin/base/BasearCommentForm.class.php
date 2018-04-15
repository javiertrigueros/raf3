<?php

/**
 * arComment form base class.
 *
 * @method arComment getObject() Returns the current form's model object
 *
 * @package    alcoor
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasearCommentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'user_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'parent'               => new sfWidgetFormInputText(),
      'a_blog_item_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('BlogItem'), 'add_empty' => true)),
      'ip'                   => new sfWidgetFormInputText(),
      'comment_approved'     => new sfWidgetFormInputCheckbox(),
      'comment'              => new sfWidgetFormTextarea(),
      'comment_author'       => new sfWidgetFormTextarea(),
      'comment_author_email' => new sfWidgetFormInputText(),
      'comment_author_url'   => new sfWidgetFormInputText(),
      'comment_agent'        => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'user_id'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'required' => false)),
      'parent'               => new sfValidatorInteger(array('required' => false)),
      'a_blog_item_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('BlogItem'), 'required' => false)),
      'ip'                   => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'comment_approved'     => new sfValidatorBoolean(array('required' => false)),
      'comment'              => new sfValidatorString(array('required' => false)),
      'comment_author'       => new sfValidatorString(array('required' => false)),
      'comment_author_email' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'comment_author_url'   => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'comment_agent'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('ar_comment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arComment';
  }

}
