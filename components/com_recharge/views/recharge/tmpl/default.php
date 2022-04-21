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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_recharge.' . $this->item->id);

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_recharge' . $this->item->id)) {
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">
    <h3>Chi tiết nạp <?php echo BIZ_XU; ?> #<?php echo $this->item->code; ?></h3>
    <?php if ($this->item->type == 'bank_tranfer' && $this->item->status == 'Chưa xác nhận') { ?>
        <div class="color-orange">
            Vui lòng chuyển khoản vào tài khoản bên dưới với nội dung chuyển <?php echo BIZ_XU; ?>: Mã nạp <?php echo BIZ_XU; ?> + username . <br>
            Nội dung chuyển <?php echo BIZ_XU; ?> của
            bạn: <?php echo $this->item->code . " + " . JFactory::getUser($this->item->created_by)->username; ?> .
        </div>
        <div>
            <b>Số tài khoản</b>:
            <?php echo $this->params['info_bank']; ?>
        </div>
    <?php } ?>
    <table class="table">

        <tr>
            <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_CODE'); ?></th>
            <td><b><?php echo $this->item->code; ?></b></td>
        </tr>

        <?php if ($this->item->type == 'bank_tranfer'): ?>
            <tr>
                <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_BANK_NAME'); ?></th>
                <td><?php echo $this->item->bank_name; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_AMOUNT'); ?></th>
            <td><b class="price"><?php echo number_format($this->item->amount, 0, ".", "."); ?> <?php echo BIZ_XU; ?></b>
            </td>
        </tr>

        <tr>
            <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_NOTE'); ?></th>
            <td><?php echo nl2br($this->item->note); ?></td>
        </tr>

        <tr>
            <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_STATUS'); ?></th>
            <td><?php echo $this->item->status; ?></td>
        </tr>

        <!-- <tr>
			<th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_IMAGE'); ?></th>
			<td>
			<?php
        // foreach ((array) $this->item->image as $singleFile) :
        // 	if (!is_array($singleFile)) :
        // 		$uploadPath = 'images/banking' . DIRECTORY_SEPARATOR . $singleFile;
        // 		 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
        // 	endif;
        // endforeach;
        ?></td>
		</tr> -->
        <tr>
            <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_TYPE'); ?></th>
            <td><?php echo $this->item->type; ?></td>
        </tr>

        <tr>
            <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_CREATED_TIME'); ?></th>
            <td><?php echo $this->item->created_time; ?></td>
        </tr>


        <tr>
            <th><?php echo JText::_('COM_RECHARGE_FORM_LBL_RECHARGE_UPDATED_TIME'); ?></th>
            <td><?php echo $this->item->updated_time; ?></td>
        </tr>

    </table>

</div>

<?php if ($canEdit && $this->item->checked_out == 0): ?>

    <!-- <a class="btn" href="<?php echo JRoute::_('index.php?option=com_recharge&task=recharge.edit&id=' . $this->item->id); ?>"><?php echo JText::_("COM_RECHARGE_EDIT_ITEM"); ?></a> -->

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete', 'com_recharge.recharge.' . $this->item->id)) : ?>

    <a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
        <?php echo JText::_("COM_RECHARGE_DELETE_ITEM"); ?>
    </a>

    <div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal"
         aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3><?php echo JText::_('COM_RECHARGE_DELETE_ITEM'); ?></h3>
        </div>
        <div class="modal-body">
            <p><?php echo JText::sprintf('COM_RECHARGE_DELETE_CONFIRM', $this->item->id); ?></p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Close</button>
            <a href="<?php echo JRoute::_('index.php?option=com_recharge&task=recharge.remove&id=' . $this->item->id, false, 2); ?>"
               class="btn btn-danger">
                <?php echo JText::_('COM_RECHARGE_DELETE_ITEM'); ?>
            </a>
        </div>
    </div>

<?php endif; ?>
<style>
    .label {
        font-size: 14px;
    }

    .color-orange {
        color: orange;
    }
</style>
