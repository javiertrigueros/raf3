<?php
// i18n with less effort. Also more flexibility for the future in how we choose to do it  
function ar_($s, $params = null)
{
  return __($s, $params, 'arquematics');
}

/**
 * parcial que se cargará al final de la página
 * 
 * @param <string $partial> 
 * @param <$arg> argumentos variables
 */
function include_js_call($partial , $args=null)
{
  ar_js_call_array($partial, $args);
}

function ar_js_call_array($callable, $args)
{
  arToolkit::$jsPartialCalls[] = array('callable' => $callable, 'args' => $args);
}

function ar_include_js_calls()
{
  echo ar_get_js_calls();
  //echo ar_get_js_calls_plain_text();
}

function ar_get_js_calls()
{
  $html = '';
  if (count(arToolkit::$jsPartialCalls))
  {
    foreach (arToolkit::$jsPartialCalls as $call)
    {
      if (isset($call['args']) && ($call['args'] !=null))
      {
        $html .= get_partial($call['callable'], $call['args']);  
      }
      else
      {
        $html .= get_partial($call['callable']);    
      }
      
    }
  }
  return $html;
}
/**
 * igual que nl2br pero filtrando los  <br         /> manuales a <br />
 * 
 * @param <string $content>
 * @return <string>
 */
function nl2br2($content) {
    $content = str_replace(array("\r\n", "\r", "\n"), "<br />", $content);
    return preg_replace('/<br\\s*?\/??>/i', '<br />', $content);
} 

/*
function ar_get_js_calls_plain_text()
{
  $html = '';
  if (count(arToolkit::$jsCalls))
  {
    foreach (arToolkit::$jsCalls as $call)
    {
        $html .= $call;  
    }
  }
  return $html;
}*/
