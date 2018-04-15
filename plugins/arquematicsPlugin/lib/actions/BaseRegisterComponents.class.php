<?php

class BaseRegisterComponents extends sfComponents
{
  public function executeForm()
  {
    $class = sfConfig::get('app_arquematics_plugin_register_form', 'telvyRegisterForm');
    $this->form = new $class();
  }
}
