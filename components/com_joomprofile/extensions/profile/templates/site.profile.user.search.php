<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('jquery.framework');
Jhtml::_('bootstrap.framework');
JHtml::_('behavior.framework');

JHtml::script("media/com_joomprofile/js/joomprofile.js");
JHtml::script("media/com_joomprofile/js/validation.js");

JHtml::script("components/com_joomprofile/extensions/profile/templates/js/search.js");
JHtml::stylesheet('media/com_joomprofile/css/joomprofile.css');
?>
<link href="media/com_joomprofile/css/font-awesome.css" rel="stylesheet">
<link href="components/com_joomprofile/extensions/profile/templates/css/search.css" rel="stylesheet">

<div id="f90pro" class="clearfix ">
	<div class="f90pro-wrapper">
	<form class="f90-validate-form">
		<div class="f90-search-header">
			<div class="row-fluid">
				<div class="form-search">
					<div class="input-append span11">
		    			<input type="text" class="search-query input-block-level">
		    			<button type="submit" class="btn btn-info"><?php echo JText::_('COM_JOOMPROFILE_SEARCH');?></button>
		    		</div>
		  	 	</div>
			</div>
		</div>
		
		<div class="f90-search-body">
			<div class="row-fluid">
				<div class="span4">
					<div class="f90-search-sidebar">
						<?php foreach($data->searchFields as $field): ?>
							<?php $fieldObj =$field->toObject();?>
							<?php $field_instance = JoomprofileLibField::get($fieldObj->type);?>
							<div class="control-group">
								<div class="control-label">
								<label class="hasTooltip">						      
							      	<?php echo JText::_($fieldObj->title);?>
							    </label>
							    </div>
						      	<div class="controls">
						      		<?php echo $field_instance->getSearchHtml($fieldObj, '', '');?>
								</div>
							</div>
						<?php endforeach;?>
					</div>			
				</div>
				<div class="span8">
					<div class="f90-search-userlist">
						s
					</div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('form.token'); ?>
		
		</form>
	</div>		
</div>
      
<?php 