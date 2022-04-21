<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Factory;
$user = Factory::getUser();

JHtml::_('behavior.keepalive');
?>
<?php
	$gr = 0;
	$groups = $user->get('groups');
	if ($user->id > 0) {
		foreach ($groups as $group)
			{
					$gr = $group;
			}
			if ($gr == 2) {
				$itemid = 348;
			}
			if ($gr == 3) {
				$itemid = 276;
			}
	}
	else {
		$itemid = 276;
	}
 ?>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure', 0)); ?>" method="post" id="login-form" class="form-vertical">
<?php if ($params->get('greeting', 1)) : ?>
	<div class="login-greeting">
		<a href="#<?php //echo JRoute::_('index.php?Itemid='.$itemid); ?>">
			<i class="fa fa-user-circle-o" aria-hidden="true"></i>
		</a>
	<span class="welcome-visiter">
	<?php if (!$params->get('name', 0)) : ?>
			<?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name'), ENT_COMPAT, 'UTF-8')); ?>
			<?php else : ?>
		<?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username'), ENT_COMPAT, 'UTF-8')); ?>
	<?php endif; ?>
	</span>
	<!-- <br> -->
	<?php if($user->groups[3] == 3) {?>
	<div class="bizxu-account" ><?php echo BIZ_XU.": "?><a><?php echo number_format($user->money, 0, ',', '.') ?></a></div>
	<?php } else {
		if($user->groups[2] == 2 && $user->money > 0) {
	?>
	<div class="bizxu-account"><?php echo BIZ_XU.": "?><a><?php echo number_format($user->money, 0, ',', '.') ?></a></div>
	<?php }} ?>
	</div>
<?php endif; ?>
<?php if ($params->get('profilelink', 0)) : ?>
	<ul class="unstyled">
		<li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=profile'); ?>">
			<?php echo JText::_('MOD_LOGIN_PROFILE'); ?></a>
		</li>
	</ul>
<?php endif; ?>

<div class="logout-button" id="logout-user-button">
		<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>

</form>
