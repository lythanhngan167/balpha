<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$user             = JFactory::getUser();
?>
<link href="<?php echo JUri::base(); ?>templates/protostar/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?php echo JUri::base(); ?>templates/protostar/js/bootstrap-toggle.min.js"></script>
	<fieldset id="users-profile-custom">
		<legend>Cấp Đại lý  và <?php echo BIZ_XU; ?></legend>
		<dl class="dl-horizontal">
					<dt>
						Tổng <?php echo BIZ_XU; ?>:
					</dt>
					<dd>
						<span class='price'><?php echo number_format($user->money, 0, ',', '.');?> BizXu</span>
					</dd>

					<dt>
						Cấp Datacenter:
					</dt>
					<dd>
						Level <?php echo $user->get('level'); ?>
					</dd>
					<dt>
						Cấp Đại lý BH:
					</dt>
					<dd>
						<?php if($user->get('level_tree') > 0){ ?>
						Level <?php echo $user->get('level_tree'); ?>
					<?php }else{ ?>
						Đang cập nhật
						<?php
						}
						?>
					</dd>
		</dl>
		<legend>Tự động mua Liên hệ (Data)</legend>
		<dl class="dl-horizontal">
			<input type="checkbox" id="toggle-autobuy"  >
			<script>
				jQuery(function() {
					jQuery('#toggle-autobuy').bootstrapToggle({
						on: 'Bật',
						off: 'Tắt'
					});
					<?php if($user->get('autobuy') == 1){ ?>
						jQuery('#toggle-autobuy').bootstrapToggle('on');
					<?php }else{ ?>
						jQuery('#toggle-autobuy').bootstrapToggle('off');
					<?php } ?>
						jQuery('#toggle-autobuy').change(function() {
						var autobuy = jQuery(this).prop('checked');
						var on_off = 0;
						if(autobuy){
							on_off = 1;
						}else{
							on_off = 0;
						}
						setAutoBuy(on_off);
					})
				})

			</script>

		</dl>
		<legend>Landingpage</legend>
		<dl class="dl-horizontal">
					<?php
						$block_landingpage = $user->get('block_landingpage');
					  if($block_landingpage == 1){
					    $temp_block = '(<span style="color:orange;">Tạm khóa</span>)';
					  }else{
					    $temp_block = '';
					  }
					?>
					<dt>
						Link Landingpage của bạn :
					</dt>
					<dd>
					<?php
					//$username = $user->username;
					//$username = $user->id;
					echo '<a target="_blank" href="http://bcavietnam.com/agent/'.$user->id.'.html">';
					echo 'http://bcavietnam.com/agent/'.$user->id.'.html';
					echo '</a>';
					echo '&nbsp;&nbsp;'.$temp_block;
					?>
					</dd>

		</dl>
	</fieldset>
	<script>
	function setAutoBuy(on_off) {
			jQuery.ajax({
					url: "<?php echo JUri::base(); ?>index.php?option=com_project&view=projectss&ajax=1&type=setAutoBuy&on_off=" + on_off,
					success: function (result) {
							// no login
							if (result == '-1') {
									alert("Vui lòng đăng nhập!");
									location.reload();
							}

							//ok
							if (result == '-2') {
								alert("Cập nhật thành công!");
							}

							//ok
							if (result == '-3') {
								alert("Cập nhật thất bại, vui lòng thử lại sau!");
							}

					}
			});
	}
	</script>
