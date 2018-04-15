<?php

class arPagePermissionsWidget extends sfWidgetFormInputHidden
{
	
	
	protected function configure($options = array(), $attributes = array())
        {
                $this->addOption('name-id', 'none');
                $this->addOption('removeLabel', a_('Remove'));
                $this->addOption('addLabel', a_('+ Add'));
                $this->addOption('extra', false);
                $this->addOption('applyToSubpagesLabel',a_('apply to subpages'));
                $this->addOption('hasSubpages',false);
                     
		parent::configure($options, $attributes);
	}
	
	public function render($name, $value = null, $attributes = array(), $errors = array())
	{
		$attributes['id'] = $this->generateId($name).'-'. uniqid();
		$html = parent::render($name, $value, $attributes, $errors);
		
                $html .= "<div class='a-page-permissions-widget clearfix' id='a-page-".$attributes['id']."'></div>";
		
                a_js_call('apostrophe.enablePermissions(?)', array(
                    'id' => 'a-page-'.$attributes['id'],
                    'hiddenField' => $attributes['id'], 
                    'name' => $this->getOption('name-id'),
                    'removeLabel' => $this->getOption('removeLabel'),
                    'addLabel' => $this->getOption('addLabel'),
                    'extra' => $this->getOption('extra'),
                    'applyToSubpagesLabel' => $this->getOption('applyToSubpagesLabel'),
                    'hasSubpages' => $this->getOption('hasSubpages')));
       
		return $html;
	}
}
?>
