<?php

  // Compatible with sf_escaping_strategy: true

  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;

 $categories = $a_event->Categories; 

 $i = count($categories);

 if ($i > 0)

 {

   foreach($categories as $category)

   {

      echo link_to($category->name, '@a_event_admin_addFilter?name=categories_list&value='.$category->id, 'post=true'); 

      $i--;

       if ($i > 0)

       {

         echo ",&nbsp;";  

       }

   } 

 }