<?php
/**
* sfExtraValidatorIp check if the value is an ip address
*
* @author   David Zeller <zellerda01@gmail.com>
*/
class sfExtraValidatorIp extends sfValidatorRegex
{
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
        
        $this->setMessage('invalid', '"%value%" is not an valid ip address');
        $this->setOption('pattern', '
            ~^
            ([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}  # ip address
            $~ix'
        );
    }
}
