<?php
/*
 * 
 * aUserAdmin actions.
 * @package    sfShibbolethPlugin
 * @subpackage aUserAdmin
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 12896 2008-11-10 19:02:34Z fabien $
 */
class aUserAdminActions extends BaseaUserAdminActions
{
    
 
    
  protected function executeBatchActive(sfWebRequest $request)
  {
      
    $ids = $request->getParameter('ids');

    $items = Doctrine_Query::create()
      ->from('sfGuardUser')
      ->whereIn('id', $ids)
      ->execute();
    
    $count = count($items);
    try
    {
      foreach ($items as $record)
      {
        $record->statusActive();
      }
      
      if ($count == count($ids))
      {
        $this->getUser()->setFlash('notice', __('The selected users have been enabled successfully.',null,'arquematics'));
      }
     
    }
    catch (Exception $e)
    {
      $this->getUser()->setFlash('error', 'An error occurred while enabled the selected items.');
    }
    
    $this->redirect('@a_user_admin');
 
  }
  
  protected function executeBatchDisable(sfWebRequest $request)
  {
      
    $ids = $request->getParameter('ids');

    $items = Doctrine_Query::create()
      ->from('sfGuardUser')
      ->whereIn('id', $ids)
      ->execute();
    
    $count = count($items);
    try
    {
      $this->getUser()->setFlash('notice', $count);
     
      foreach ($items as $record)
      {
        $record->statusDisable();
      }
      
      if ($count == count($ids))
      {
        $this->getUser()->setFlash('notice', __('The selected users have been disable successfully.',null,'arquematics'));
      }
     
    }
    catch (Exception $e)
    {
        
      $this->getUser()->setFlash('error', 'An error occurred while enabled the selected items.'. $e);
    }
    
    $this->redirect('@a_user_admin');
 
  }

  /**
   * DOCUMENT ME
   * @return mixed
   */
  protected function buildQuery()
  {
    $query = parent::buildQuery();
    // This user is for running scheduled tasks only. It must remain a superuser and
    // should never be marked active or have a known password (it has a randomly generated
    // password just in case someone somehow marks it active). So we hide it from 
    // the admin panel, where nothing good could ever happen to it.
    $query->andWhere($query->getRootAlias() . '.username <> ?', array('ataskuser'));
    return $query;
  }
}
