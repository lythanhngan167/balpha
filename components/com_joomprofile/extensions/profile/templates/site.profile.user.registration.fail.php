<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div id="f90pro" class="clearfix ">
	<div class="f90pro-wrapper clearfix ">
		<!-- error Box starts here || Above code is same for all pages in f90pro  -->
	             
	    <div class="f90pro-errorbox clearfix">
	    	<h2><span class="f90pro-error-name"><?php echo JText::_('COM_JOOMPROFILE_REGISTRATION_FAILED');?></span></h2>
	    		<?php foreach ($data->messages as $message):?>
					<div class="f90pro-error">					
						<div class="alert alert-danger">
							<?php echo $message;?>
						</div>
					</div>
				<?php endforeach;?>
		</div>

		<div class="row-fluid">
			<div class="pull-right">
				<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.registration', false);?>">
					<?php echo JText::_('COM_JOOMPROFILE_TRY_AGAIN');?>
				</a>
			</div>
		</div>

		<!-- Error Ends here -->
    </div>
</div>   
<?php 