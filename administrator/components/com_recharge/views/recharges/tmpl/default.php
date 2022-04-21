<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Recharge
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;


use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'administrator/components/com_recharge/assets/css/recharge.css');
$document->addStyleSheet(Uri::root() . 'media/com_recharge/css/list.css');

$user = Factory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_recharge');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_recharge&task=recharges.saveOrderAjax&tmpl=component';
    HTMLHelper::_('sortablelist.sortable', 'rechargeList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo Route::_('index.php?option=com_recharge&view=recharges'); ?>" method="post"
      name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
            <?php endif; ?>

            <?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

            <div class="clearfix"></div>
            <table class="table table-striped" id="rechargeList">
                <thead>
                <tr>
                    <?php if (isset($this->items[0]->ordering)): ?>
                        <th width="1%" class="nowrap center hidden-phone">
                            <?php echo HTMLHelper::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
                    <?php endif; ?>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value=""
                               title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                    </th>
                    <?php if (isset($this->items[0]->state)): ?>
                        <!-- <th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th> -->
                    <?php endif; ?>

                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_ID', 'a.`id`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_CODE', 'a.`code`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_CREATED_BY', 'a.`created_by`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_AMOUNT', 'a.`amount`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_NOTE', 'a.`note`', $listDirn, $listOrder); ?>
                    </th>
                    <!--				<th class='left'>-->
                    <!--				--><?php //echo JHtml::_('searchtools.sort',  'COM_RECHARGE_RECHARGES_BANK_NAME', 'a.`bank_name`', $listDirn, $listOrder); ?>
                    <!--				</th>-->

                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_STATUS', 'a.`status`', $listDirn, $listOrder); ?>
                    </th>
                    <?php //if((int)$this->userGroup[0] === 15) {?>
                        <th class='left' width="10%">
                            Người tạo
                        </th>
                    <?php //} else {?>
                        <th class='left' width="10%">
                            Thao tác
                        </th>
                    <?php //} ?>
                    <!-- <th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_IMAGE', 'a.`image`', $listDirn, $listOrder); ?>
				</th> -->
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_CREATED_TIME', 'a.`created_time`', $listDirn, $listOrder); ?>
                    </th>

                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_TYPE', 'a.`type`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_UPDATED_TIME', 'a.`updated_time`', $listDirn, $listOrder); ?>
                    </th>

                    <!-- <th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_RECHARGE_RECHARGES_MODIFIED_BY', 'a.`modified_by`', $listDirn, $listOrder); ?>
				</th> -->
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($this->items as $i => $item) :
                    $ordering = ($listOrder == 'a.ordering');
                    $canCreate = $user->authorise('core.create', 'com_recharge');
                    $canEdit = $user->authorise('core.edit', 'com_recharge');
                    $canCheckin = $user->authorise('core.manage', 'com_recharge');
                    $canChange = $user->authorise('core.edit.state', 'com_recharge');
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">

                        <?php if (isset($this->items[0]->ordering)) : ?>
                            <td class="order nowrap center hidden-phone">
                                <?php if ($canChange) :
                                    $disableClassName = '';
                                    $disabledLabel = '';

                                    if (!$saveOrder) :
                                        $disabledLabel = Text::_('JORDERINGDISABLED');
                                        $disableClassName = 'inactive tip-top';
                                    endif; ?>
                                    <span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
                                          title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
                                    <input type="text" style="display:none" name="order[]" size="5"
                                           value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
                                <?php else : ?>
                                    <span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td class="hidden-phone">
                            <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                        </td>
                        <!-- <?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'recharges.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?> -->

                        <td>

                            <?php echo $item->id; ?>
                        </td>
                        <td>
                            <!-- <?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'recharges.', $canCheckin); ?>
					<?php endif; ?> -->
                            <?php if ($canEdit) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_recharge&task=recharge.edit&id=' . (int)$item->id); ?>">
                                    <?php echo $this->escape($item->code); ?></a>
                            <?php else : ?>
                                <?php echo $this->escape($item->code); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo $item->created_by; ?>
                            <br>(<?php echo $item->created_by_username; ?>)
                        </td>
                        <td>

					<span class="price"><?php echo number_format($item->amount, 0, ".", "."); ?>

					</span>
                        </td>
                        <td>

                            <?php echo $this->escape($item->note); ?>
                        </td>
<!--                        <td>-->
<!---->
<!--                            --><?php //echo $item->bank_name; ?>
<!--                        </td>-->
                        <td>
                            <?php if ($item->status == 'Chưa xác nhận') { ?>
                                 <span class="label label-warning"><?php echo $item->status; ?></span>
                            <?php } ?>
                            <?php if ($item->status == 'Đã xác nhận') { ?>
                                <span class="label label-success"><?php echo $item->status; ?></span>
                            <?php } ?>
                            <?php if ($item->status == 'Hủy') { ?>
                                <span class="label label-danger"><?php echo $item->status; ?></span>
                            <?php } ?>
                            <?php if ($item->status == 'Chờ thanh toán') { ?>
                                <span class="label label-default"><?php echo $item->status; ?></span>
                            <?php } ?>
                        </td>
                        <td>
                        <?php //if((int)$this->userGroup[0] === 15 && $item->sbdm_id) {?>

                        <?php //} ?>
                        <?php echo $item->sbdm_username ? $item->sbdm_name . '<br/>'. '('.$item->sbdm_username.')' : '#'; ?>
                        </td>
                        <td>

                                <div class="button-confirm">
                                    <?php if ($item->status == 'Chưa xác nhận') { ?>
                                        <button type="button" class="btn btn-primary"
                                                onclick="confirmRecharge('confirmed','Đã xác nhận','<?php echo $item->code; ?>','<?php echo number_format($item->amount, 0, ".", "."); ?>',<?php echo $item->amount; ?>,<?php echo $item->id; ?>,<?php echo $item->created_by_id; ?>);">
                                            Xác nhận
                                        </button>
                                        <div style="padding-top:5px;">
                                            <button type="button" class="btn btn-danger"
                                                    onclick="confirmRecharge('cancel','Huỷ','<?php echo $item->code; ?>','<?php echo number_format($item->amount, 0, ".", "."); ?>',<?php echo $item->amount; ?>,<?php echo $item->id; ?>,<?php echo $item->created_by_id; ?>);">
                                                Huỷ
                                            </button>
                                        </div>
                                    <?php } ?>
                                </div>

                        </td>
                        <!-- <td> -->

                        <?php
                        // if (!empty($item->image)) :
                        // 	$imageArr = explode(',', $item->image);
                        // 	foreach ($imageArr as $fileSingle) :
                        // 		if (!is_array($fileSingle)) :
                        // 			$uploadPath = 'images/banking' .DIRECTORY_SEPARATOR . $fileSingle;
                        // 			echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image">' . $fileSingle . '</a> | ';
                        // 		endif;
                        // 	endforeach;
                        // else:
                        // 	echo $item->image;
                        // endif;
                        ?>
                        <!-- </td>			 -->
                        <td>

                            <?php echo $item->created_time; ?>
                        </td>
                        <td>

                            <?php echo $item->type; ?>
                        </td>
                        <td>

                            <?php echo $item->updated_time; ?>
                        </td>
                        <!-- <td>

				<?php echo $item->modified_by; ?>
			</td> -->

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
            <?php echo HTMLHelper::_('form.token'); ?>
        </div>
</form>
<script>

    jQuery.noConflict();

    function confirmRecharge(status, status_name, code, amount_format, amount, id, uid) {
        if (confirm('Bạn có chắc muốn chuyển Mã nạp BizXu ' + code + ' với số BizXu ' + amount_format + ' sang trạng thái ' + status_name + '?')) {
            jQuery.ajax({
                url: "<?php echo JUri::base(); ?>index.php?option=com_recharge&ajax=1&type=confirm&code=" + code + "&amount=" + amount + '&status=' + status + '&id=' + id + '&uid=' + uid,
                success: function (result) {
                    if (result == '-1') {
                        alert("Vui lòng đăng nhập!");
                        location.reload();
                    }
                    if (result == '1') {
                        alert("Cập nhật Trạng thái Nạp BizXu thành công!");
                        location.reload();
                    }
                    if (result == '0') {
                        alert("Cập nhật Trạng thái Nạp BizXu thất bại, vui lòng kiểm tra lại.");
                        location.reload();
                    }
                }
            });

        }

    }

    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[id];

        if (!cb) return false;

        while (true) {
            cbx = f['cb' + i];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField = document.createElement('input');

        inputField.type = 'hidden';
        inputField.name = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
</script>
<style>
    .price {
        color: red;
    }

    #toolbar-trash,
    #toolbar-checkin,
    #toolbar-archive,
    #toolbar-unpublish,
    #toolbar-publish,
    #toolbar-edit,
    #toolbar-copy,
    #filter_state_chzn {
        display: none !important;
    }

</style>
