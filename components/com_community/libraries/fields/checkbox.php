<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (COMMUNITY_COM_PATH.'/libraries/fields/profilefield.php');
class CFieldsCheckbox extends CProfileField
{
    public function _translateValue( &$string )
    {
        $string = JText::_( $string );
    }

    /**
     * Method to format the specified value for text type
     **/
    public function getFieldData( $field, $delimeter = "<br/>" )
    {
        $value = $field['value'];

        // Since multiple select has values separated by commas, we need to replace it with <br />.
        $fieldArray = explode ( ',' , $value );

        array_walk($fieldArray, array('CFieldsCheckbox', '_translateValue'));

        $fieldValue = implode($delimeter, array_filter($fieldArray));
        return $fieldValue;
    }

    public function getFieldHTML( $field , $required )
    {
        $required = ($field->required == 1) ? ' data-required="true"' : '';
        $lists = array();
        //a fix for wrong data input
        $field->value= CStringHelper::trim($field->value);
        if(is_array($field->value)){
            $tmplist = $field->value;
        } else {
            if(CStringHelper::strrpos($field->value,',') == (CStringHelper::strlen($field->value) - 1)) {
                $field->value = CStringHelper::substr($field->value,0,-1);
            }
            $tmplist     = explode(',', $field->value);
        }
        if($tmplist){
            foreach($tmplist as $value){
                $lists[] = CStringHelper::trim( $value );
            }
        }
        $html            = '';
        $elementSelected = 0;
        $elementCnt      = 0;
        $cnt             = 0;
        $params          = new CParameter($field->params);
        $readonly        = '';

        if ($params->get('readonly') == 1 && !COwnerHelper::isCommunityAdmin()) {
            $readonly=' disabled="disabled"';
        }

        $optionCount = 0;
        foreach ($field->options as $option) {
            if (!empty($option)) $optionCount++;
        }

        $html .= '<div class="joms-checkbox--wrapper" style="display:inline-block">';
        if( is_array( $field->options ) )
        {
            foreach( $field->options as $option )
            {
                if(CStringHelper::trim($option)==''){
                    //do not display blank options
                    continue;
                }

                $selected = (in_array( JString::trim( $option ) , $lists ) || in_array( htmlspecialchars((JString::trim( $option ))) , $lists ) || ($optionCount == 1 && $option == $field->value)) ? ' checked="checked"' : '';

                if( empty( $selected ) )
                {
                    $elementSelected++;
                }


                $html .= '<label class="lblradio-block">';
                $html .= '<input type="checkbox" name="field' . $field->id . '[]" value="' . $option . '" class="joms-checkbox" ' . $selected . $readonly . $required . ' style="margin: 2px 5px 0 0" />';
                $html .= JText::_( $option ) . '</label>';
                $elementCnt++;

            }
        }

        $html   .= '</div>';

        return $html;
    }

    public function isValid( $value , $required )
    {
        if( $required && empty($value))
        {
            return false;
        }
        return true;
    }

    public function formatdata( $value )
    {
        $finalvalue = '';
        if(!empty($value))
        {
            foreach($value as $listValue){
                $finalvalue .= $listValue . ',';
            }
        }
        return $finalvalue;
    }
}
