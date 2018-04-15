<?php
/**
 * 
 * Valida la existencia real de dominios
 *
 * Arquematics 2011
 *
 * @author Javier Trigueros Martínez de los Huertos
 */
class sfValidatorDomain extends sfValidatorBase
{
  /**
   * Configuracion
   *
   *
   * @param <array $options>    array de optiones
   * @param <array $messages>     array de messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(),
                                $messages = array()) {
      // The type of method to use to validate it.
      $this->addOption('clean_type', 'url');

      // List of valid protocol schemes to allow in URLs
      $this->addOption('schemes', array('http', 'https'));

      // The default scheme
      $this->addOption('default_scheme', 'http');
      // Setup some basic error messages
      $msg = 'The provided domain does not appear to be valid.';
      $this->addMessage('badform', isset($messages['badform'])?$messages['badform']:$msg);
      $this->addMessage('badscheme', isset($messages['badscheme'])?$messages['badscheme']:$msg);
      $this->addMessage('nohost', isset($messages['nohost'])?$messages['nohost']:$msg);
      $this->addMessage('invalid', isset($messages['invalid'])?$messages['invalid']:$msg);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value) {
     
      if ($this->getOption('clean_type') == 'domain') {
          // If it's a domain, then it's simple to check it.
          $domain = $value;
      } else {
          // It's probably a complete URL, so check it in
          // more depth.

          // Verify that it can be parsed as a URL.
          // Note: @'s are bad practice, however if a method is
          // being checked and we can't stop the error, then
          // we want to hide it.

          $parts = @parse_url($value); // May throw a warning

          // If there is no scheme (http, https, etc.) then it's
          // likely that parse_url parsed it incorrectly, so
          // prepend a scheme and try again. if we don't do this,
          // we may get "example.com/foobar" as our path.
          if (!isset($parts['scheme'])) {
              $value = $this->getOption('default_scheme')
                       . '://' . $value;
              $parts = @parse_url($value);
          }

          // If it wasn't parsed, then something was wrong.
          if (!$parts) {
              throw new sfValidatorError($this, 'badform',
                            array('value' => $value));
          }

          // Validate that the scheme provided is valid
          if (!in_array($parts['scheme'],
                        $this->getOption('schemes'))) {
              throw new sfValidatorError($this, 'badscheme',
                            array('value' => $value));
          }

          // Ensure that the host was found
          if (!isset($parts['host'])) {
              throw new sfValidatorError($this, 'nohost',
                            array('value' => $value));
          } else {
              // Finally set the domain for the final, unified
              // verification.
              $domain = $parts['host'];
          }
      }

      // Convert the domain to an IP address
      $ip_address = gethostbyname($domain);

      // Unfortunately, gethostbyname's only response if it
      // fails, is returns the input $domain. Try to convert it
      // to a packed IP address. If that fails, then it isn't a
      // valid domain name.
      if (@inet_pton($ip_address)) {         
          return $value;
      }

      // Didn't validate...
    throw new sfValidatorError($this, 'invalid',
                    array('value' => $value));
  }
}
?>