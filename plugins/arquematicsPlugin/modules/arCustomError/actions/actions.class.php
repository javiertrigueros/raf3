<?php

/**
 * arCustomError actions.
 *
 * @package    arquematicsPlugin
 * @author     Javier Trigueros Martínez de los Huertos
 * @version    0.1
 */
class arCustomErrorActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  /**
   * Muestra la página de error
   * 
   * @param sfWebRequest $request 
   */
  public function executePag404(sfWebRequest $request)
  {

    $this->getResponse()->setStatusCode(404,'Not Found' );
    
  }

}
