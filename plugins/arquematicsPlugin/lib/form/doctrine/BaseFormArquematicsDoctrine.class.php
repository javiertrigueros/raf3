<?php
/**
 * @abstract Funciones básicas para la validación y manejo
 * de formularios. De esta clase heredan todos los formularios.
 * 
 * @author Javier Trigueros
 * 
 * @copyright 2011 nov alcoor.com
 * 
 * @version 0.1
 * 
 */
abstract class BaseFormArquematicsDoctrine extends sfFormDoctrine
{
  public function setup()
  {
  }
  /**
   * Devuelve los errores del formulario en un array
   * 
   * @return array of string: array con los errores por campos/widgets  
   */
  public function getErrors()
  {
   $errors = array();

   // errores en cada campo/widget
   foreach ($this as $form_field)
   {   
     if ($form_field->hasError())
     {   
       $error_obj = $form_field->getError();
       if ($error_obj instanceof sfValidatorErrorSchema)
       {   
         foreach ($error_obj->getErrors() as $error)
         {   
           //si el campo tiene más de un error se sobreescribe
           $errors[$form_field->getName()] = $error->getMessage();
         }   
       }   
       else
       {   
         $errors[$form_field->getName()] = $error_obj->getMessage();
       }   
     }   
   }   

   // errores globales
   foreach ($this->getGlobalErrors() as $validator_error)
   {   
     $errors[] = $validator_error->getMessage();
   }   

   return $errors;
  }
  
   /**
   * Embeds a form like "mergeForm" does, but will still
   * save the input data.
   */
  public function embedMergeForm($name, sfForm $form)
  {
    // This starts like sfForm::embedForm
    $name = (string) $name;
    if (true === $this->isBound() || true === $form->isBound())
    {
      throw new LogicException('A bound form cannot be merged');
    }
    $this->embeddedForms[$name] = $form;

    $form = clone $form;
    unset($form[self::$CSRFFieldName]);

    // But now, copy each widget instead of the while form into the current
    // form. Each widget ist named "formname|fieldname".
    foreach ($form->getWidgetSchema()->getFields() as $field => $widget)
    {
      $widgetName = "$name|$field";
      if (isset($this->widgetSchema[$widgetName]))
      {
        throw new LogicException("The forms cannot be merged. A field name '$widgetName' already exists.");
      }

      $this->widgetSchema[$widgetName] = $widget;                           // Copy widget
      $this->validatorSchema[$widgetName] = $form->validatorSchema[$field]; // Copy schema
      $this->setDefault($widgetName, $form->getDefault($field));            // Copy default value

      if (!$widget->getLabel())
      {
        // Re-create label if not set (otherwise it would be named 'ucfirst($widgetName)')
        $label = $form->getWidgetSchema()->getFormFormatter()->generateLabelName($field);
        $this->getWidgetSchema()->setLabel($widgetName, $label);
      }
    }

    // And this is like in sfForm::embedForm
    $this->resetFormFields();
  }

  /**
   * Override sfFormDoctrine to prepare the
   * values: FORMNAME|FIELDNAME has to be transformed
   * to FORMNAME[FIELDNAME]
   */
  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->values;
      foreach ($this->embeddedForms AS $name => $form)
      {
        foreach ($form AS $field => $f)
        {
          if (isset($values["$name|$field"]))
          {
            // Re-rename the form field and remove
            // the original field
            $values[$name][$field] = $values["$name|$field"];
            unset($values["$name|$field"]);
          }
        }
      }
    }

    // Give the request to the original method
    return parent::updateObject($values);
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (is_null($con))
    {
      $con = $this->getConnection();
    }
    if (is_null($forms))
    {
      $forms = $this->embeddedForms;
    }
    foreach ($forms as $key => $form)
    {
      if ($form instanceof sfFormDoctrine)
      {
        if (isset($this->values[$key]))
        {
          $form->bind($this->values[$key]);
          $form->doSave($con);
        }
       
        $form->saveEmbeddedForms($con);
      }
      else
      {
        $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
      }
    }

    return parent::saveEmbeddedForms($con, $forms);
  } 
}