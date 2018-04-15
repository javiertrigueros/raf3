<?php

/**
 * 
 * aTagAdmin actions.
 * @package    apostrophePlugin
 * @subpackage aTagAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class aTagAdminActions extends BaseaTagAdminActions
{
    public function preExecute()
    {
       sfProjectConfiguration::getActive()
           ->loadHelpers(array('I18N','Partial','a','ar'));
       
       parent::preExecute();
    }

}
