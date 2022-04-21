<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$fielddata 	= $data->fielddata;
if(trim($data->value) == '' && isset($fielddata->params['default_value']) && !empty($fielddata->params['default_value'])){
	$value  = $fielddata->params['default_value'];
}else {
	$value  = $data->value;
}
// TODO :
?>
<?php $class = $fielddata->css_class;?>

<?php 
$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <form>
					<fieldset name='joomprofile-field[$fielddata->id]'>
						<field 	
							type = 'language'					
							name='joomprofile-field[$fielddata->id]' 
							default='$value'
                            client='site'
							>
							<option value=''>JOPTION_USE_DEFAULT</option>
						</field>
					</fieldset>
				</form>";

        $form = JForm::getInstance('joomprofile-field['.$fielddata->id.']', $xml);
       echo $form->getField('joomprofile-field['.$fielddata->id.']')->input;

?><?php

