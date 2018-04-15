<?php

/**
 * sfGuardUserProfile filter form base class.
 *
 * @package    alcoor
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasesfGuardUserProfileFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'email_address'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'username'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_id'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'first_last'        => new sfWidgetFormFilterInput(),
      'password'          => new sfWidgetFormFilterInput(),
      'description'       => new sfWidgetFormFilterInput(),
      'address'           => new sfWidgetFormFilterInput(),
      'key_saved'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'public_key'        => new sfWidgetFormFilterInput(),
      'store_key'         => new sfWidgetFormFilterInput(),
      'phone'             => new sfWidgetFormFilterInput(),
      'public_mail_key'   => new sfWidgetFormFilterInput(),
      'private_mail_key'  => new sfWidgetFormFilterInput(),
      'facebook_uid'      => new sfWidgetFormFilterInput(),
      'profile_data'      => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'gmaps_locate_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate')),
    ));

    $this->setValidators(array(
      'email_address'     => new sfValidatorPass(array('required' => false)),
      'username'          => new sfValidatorPass(array('required' => false)),
      'user_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'first_last'        => new sfValidatorPass(array('required' => false)),
      'password'          => new sfValidatorPass(array('required' => false)),
      'description'       => new sfValidatorPass(array('required' => false)),
      'address'           => new sfValidatorPass(array('required' => false)),
      'key_saved'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'public_key'        => new sfValidatorPass(array('required' => false)),
      'store_key'         => new sfValidatorPass(array('required' => false)),
      'phone'             => new sfValidatorPass(array('required' => false)),
      'public_mail_key'   => new sfValidatorPass(array('required' => false)),
      'private_mail_key'  => new sfValidatorPass(array('required' => false)),
      'facebook_uid'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'profile_data'      => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'gmaps_locate_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'arGmapsLocate', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sf_guard_user_profile_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addGmapsLocateListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query
      ->leftJoin($query->getRootAlias().'.arUsersGmapsLocate arUsersGmapsLocate')
      ->andWhereIn('arUsersGmapsLocate.locate_id', $values)
    ;
  }

  public function getModelName()
  {
    return 'sfGuardUserProfile';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'email_address'     => 'Text',
      'username'          => 'Text',
      'user_id'           => 'ForeignKey',
      'first_last'        => 'Text',
      'password'          => 'Text',
      'description'       => 'Text',
      'address'           => 'Text',
      'key_saved'         => 'Boolean',
      'public_key'        => 'Text',
      'store_key'         => 'Text',
      'phone'             => 'Text',
      'public_mail_key'   => 'Text',
      'private_mail_key'  => 'Text',
      'facebook_uid'      => 'Number',
      'profile_data'      => 'Text',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'gmaps_locate_list' => 'ManyKey',
    );
  }
}
