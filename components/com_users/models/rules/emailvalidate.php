<?php

defined('JPATH_PLATFORM') or die;
use Joomla\Registry\Registry;

class JFormRuleEmailvalidate extends JFormRule 
{

    public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null) 
    {
        return JMailHelper::isEmailAddress($value);

    }
}

?>