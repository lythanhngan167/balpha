<?php
/**
 * @package     Joomla.Admin
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<div class="row-fluid">
	<div class="span2 joomprofile-admin-sidebar">
		<div class="accordion" id="f90adminmenu">

			<?php foreach ($menus as $id => $menu) :?>
				<div class="accordion-group <?php echo $view == $menu['view'] ? 'active' : '';?>">

					<div class="accordion-heading <?php echo $view == $menu['view'] ? 'active' : '';?>">
						<?php if(isset($menu['menus'])) :?>
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#f90adminmenu" href="#f90AdminMenuCollapse<?php echo $id;?>">
								<i class="fa-lg <?php echo $menu['class'];?>"></i> Hồ sơ Tư vấn viên<?php //echo $menu['text'];?>
								<i class="fa fa-lg fa-angle-down"></i>
							</a>
						<?php else:?>
							<a class="accordion-toggle" href="<?php echo $menu['link'];?>">
								<i class="fa-lg <?php echo $menu['class'];?>"></i> Cài đặt<?php //echo $menu['text'];?>
							</a>

						<?php endif;?>
					</div>
					<?php if(isset($menu['menus'])) :?>
						<div id="f90AdminMenuCollapse<?php echo $id;?>" class="accordion-body collapse <?php echo $view == $menu['view'] ? 'in' :  '';?>">
							<div class="accordion-inner">
								<ul class="submenu">
								<?php foreach ($menu['menus'] as $submenu):?>
									<?php list($app_view, $app_task) = explode('.', $task);?>
									<li><a <?php echo $submenu['view'] == $app_view ? 'class="active"' : '';?> href="<?php echo $submenu['link'];?>"><?php echo $submenu['text'];?></a></li>
								<?php endforeach;?>
								</ul>
							</div>
						</div>
					<?php endif;?>
				</div>
			<?php endforeach;?>

		</div>
	</div>

	<div class="span10 pull-right">
		<div class="joomprofile-admin">
			<?php echo $content;?>
		</div>
	</div>
</div>
