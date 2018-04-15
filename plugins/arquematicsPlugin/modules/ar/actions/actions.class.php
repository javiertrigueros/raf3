<?php
/**
 * arActions
 * 
 * @package    arquematics
 * @subpackage ar
 * @author     Javier Trigueros
 */
class arActions extends BaseaActions
{

  public function executeError404(sfWebRequest $request)
  {
  	exit();
  }
}
