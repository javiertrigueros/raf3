<?php

/**
 * Base project form.
 * 
 * @package    arquematicsPlugin
 * @subpackage form
 * @author     Javier Trigueros Martinez de los Huertos
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
 
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
}
