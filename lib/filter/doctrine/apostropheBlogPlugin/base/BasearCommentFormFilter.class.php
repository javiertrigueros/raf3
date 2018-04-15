<?php

/**
 * arComment filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasearCommentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'parent'               => new sfWidgetFormFilterInput(),
      'a_blog_item_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('BlogItem'), 'add_empty' => true)),
      'ip'                   => new sfWidgetFormFilterInput(),
      'comment_approved'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'comment'              => new sfWidgetFormFilterInput(),
      'comment_author'       => new sfWidgetFormFilterInput(),
      'comment_author_email' => new sfWidgetFormFilterInput(),
      'comment_author_url'   => new sfWidgetFormFilterInput(),
      'comment_agent'        => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'parent'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'a_blog_item_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('BlogItem'), 'column' => 'id')),
      'ip'                   => new sfValidatorPass(array('required' => false)),
      'comment_approved'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'comment'              => new sfValidatorPass(array('required' => false)),
      'comment_author'       => new sfValidatorPass(array('required' => false)),
      'comment_author_email' => new sfValidatorPass(array('required' => false)),
      'comment_author_url'   => new sfValidatorPass(array('required' => false)),
      'comment_agent'        => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('ar_comment_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'arComment';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'user_id'              => 'ForeignKey',
      'parent'               => 'Number',
      'a_blog_item_id'       => 'ForeignKey',
      'ip'                   => 'Text',
      'comment_approved'     => 'Boolean',
      'comment'              => 'Text',
      'comment_author'       => 'Text',
      'comment_author_email' => 'Text',
      'comment_author_url'   => 'Text',
      'comment_agent'        => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
    );
  }
}
