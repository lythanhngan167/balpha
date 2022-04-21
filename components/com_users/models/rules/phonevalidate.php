<?php

defined('JPATH_PLATFORM') or die;
use Joomla\Registry\Registry;

class JFormRulePhonevalidate extends JFormRule 
{

    protected $regex = '/^0[0-9]{9}+$/';
    public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null) 
    {
        $result = preg_match($this->regex, $value);
        if(!$result) {
            $element->attributes()->message = 'Số điện thoại không hợp lệ.';
            return false;
        }
        return true;

    }
}

?>