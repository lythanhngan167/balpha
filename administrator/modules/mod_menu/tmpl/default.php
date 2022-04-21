<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doc       = JFactory::getDocument();
$direction = $doc->direction == 'rtl' ? 'pull-right' : '';
$class     = $enabled ? 'nav ' . $direction : 'nav disabled ' . $direction;

// Recurse through children of root node if they exist
$menuTree = $menu->getTree();
$root     = $menuTree->reset();


$user   = JFactory::getUser();
$groups = $user->get('groups');
//print_r($groups);
$hide7 = 0;
$hide10 = 0;
$hide11 = 0;
$hide12 = 0;
foreach ($groups as $group)
{
		if($group == 7){
			$hide7 = 1;
		}
		if($group == 10){
			$hide10 = 1;
		}
		if($group == 11){
			$hide11 = 1;
		}
		if($group == 12){
			$hide12 = 1;
		}
		if($group == 13){
			$hide13 = 1;
		}
}
$hide_group7_class = '';
$hide_group10_class = '';
$hide_group11_class = '';
$hide_group12_class = '';
$hide_group13_class = '';

if($hide7 == 1){
	$hide_group7_class = '';
}else{
	//$hide_group7_class = '';
	if($hide10 == 1){
		$hide_group10_class = 'hidde-group10';
	}else{
		//$hide_group10_class = '';
		if($hide11 == 1){
			$hide_group11_class = 'hidde-group11';
		}else{
			//$hide_group11_class = '';
			if($hide12 == 1){
				$hide_group12_class = 'hidde-group12';
			}else{
				if($hide13 == 1){
					$hide_group13_class = 'hidde-group13';
				}
			}
		}
	}
}



if ($root->hasChildren())
{
	echo '<ul id="menu" class="' . $class . '">' . "\n";

	// WARNING: Do not use direct 'include' or 'require' as it is important to isolate the scope for each call
	$menu->renderSubmenu(JModuleHelper::getLayoutPath('mod_menu', 'default_submenu'));
	// echo '<li class="'.$hide_group7_class.'                         '.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_content">Bài viết</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' "><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_recharge">Nạp tiền</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_project">Dự án</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_import_data">Nhập từ Excel</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_export_customer">Xuất ra Excel</a></li>';
	//
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' "><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_users&view=users">Thành viên</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_customer">Khách hàng</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' "><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_order">Lịch sử mua Dữ liệu</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_transaction_history">Lịch sử Giao dịch</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_notification&view=notifications">Thông báo</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_maxpick_level">Lấy dữ liệu tối đa theo Level</a></li>';
	// echo '<li class="'.$hide_group7_class.' '.$hide_group10_class.' '.$hide_group11_class.' "><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_users&view=sales">Quản lý Level cho Sale</a></li>';
	// echo '<li class="'.$hide_group7_class.'  												'.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_registration&view=registrations">Người đăng ký Landingpage</a></li>';
	// echo '<li class="'.$hide_group7_class.'  												'.$hide_group11_class.' '.$hide_group12_class.'"><a class="dropdown-toggle" data-toggle="dropdown" href="index.php?option=com_registration&view=report&date=today">Thống kê Landingpage</a></li>';

	echo "</ul>\n";

	echo '<ul id="nav-empty" class="dropdown-menu nav-empty hidden-phone"></ul>';

	if ($css = $menuTree->getCss())
	{
		$doc->addStyleDeclaration(implode("\n", $css));
	}
}
