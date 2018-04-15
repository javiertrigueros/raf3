<?php

class arMenuOptionsWidget extends sfWidgetFormChoice
{
	
	public function render($name, $value = null, $attributes = array(), $errors = array())
	{
                $name = $this->generateId($name);
		$attributes['id'] = $name;
                $name = $this->generateId($name).'-'. uniqid();
		return parent::render($name, $value, $attributes, $errors);	
	}
}
?>
