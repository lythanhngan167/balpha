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
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_recharge/css/form.css');

$codeRandom = $this->generateRandomString(10);

?>
<style>
    #toolbar-save-copy, #toolbar-save {
        display: none;
    }

    <?php if($_GET['id'] > 0){ ?>
    #toolbar-apply {
        display: none;
    }

    <?php } ?>
</style>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {
        <?php if($_GET['id'] > 0){ ?>
          js('#jform_code').val('<?php echo $this->item->code; ?>');
        <?php }else{ ?>
        js('#jform_code').val('<?php echo $codeRandom; ?>');
        <?php } ?>
        js('#jform_code').attr('readonly', 'readonly');
    });

    function confirmRecharge(status, status_name, code, amount_format, amount, id, uid) {
        if (confirm('Bạn có chắc muốn chuyển Mã nạp BizXu ' + code + ' với số BizXu ' + amount_format + ' sang trạng thái ' + status_name + '?')) {
            js.ajax({
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

    Joomla.submitbutton = function (task) {
        if (task == 'recharge.cancel') {
            Joomla.submitform(task, document.getElementById('recharge-form'));
        } else {

            if (task != 'recharge.cancel' && document.formvalidator.isValid(document.id('recharge-form'))) {

                Joomla.submitform(task, document.getElementById('recharge-form'));
            } else {
                alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form
        action="<?php echo JRoute::_('index.php?option=com_recharge&layout=edit&id=' . (int)$this->item->id); ?>"
        method="post" enctype="multipart/form-data" name="adminForm" id="recharge-form"
        class="form-validate form-horizontal">


    <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
    <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>
    <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>"/>
    <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>"/>
    <input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>"/>
    <?php echo $this->form->renderField('created_by'); ?>
    <?php echo $this->form->renderField('modified_by'); ?>
    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'recharge')); ?>
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'recharge', JText::_('COM_RECHARGE_TAB_RECHARGE', true)); ?>
    <div class="row-fluid">
        <div class="button-confirm">
            <?php if ($this->item->status == 'unconfirm') { ?>
                <button type="button" class="btn btn-primary"
                        onclick="confirmRecharge('confirmed','Đã xác nhận','<?php echo $this->item->code; ?>','<?php echo number_format($this->item->amount, 0, ".", "."); ?>',<?php echo $this->item->amount; ?>,<?php echo $this->item->id; ?>,<?php echo $this->item->created_by; ?>);">
                    Xác nhận
                </button>

                <button type="button" class="btn btn-danger"
                        onclick="confirmRecharge('cancel','Huỷ','<?php echo $this->item->code; ?>','<?php echo number_format($this->item->amount, 0, ".", "."); ?>',<?php echo $this->item->amount; ?>,<?php echo $this->item->id; ?>,<?php echo $this->item->created_by; ?>);">
                    Huỷ
                </button>
            <?php } ?>
        </div>
        <div class="span10 form-horizontal">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_RECHARGE_FIELDSET_RECHARGE'); ?><?php if ($_GET['id'] > 0) { ?> - <?php echo JFactory::getUser($this->item->created_by)->name; ?> (<?php echo JFactory::getUser($this->item->created_by)->username; ?>) <?php } ?></legend>
                <?php echo $this->form->renderField('code'); ?>
                <?php
                $listSale = $this->getListSale(3);
                //print_r($listSale);
                ?>
                <?php if ($_GET['id'] == 0) { ?>
                    <div class="control-group">
                        <div class="control-label"><label id="jform_sale-lbl" for="jform_sale" class="required">
                                Đại lý<span class="star">&nbsp;*</span></label>
                        </div>
                        <div class="controls">
                            <select id="jform_sale" name="jform[sale]" class="required" required="required"
                                    aria-required="true">
                                <option value="">Vui lòng chọn Đại lý</option>
                                <?php foreach ($listSale as $sale) { ?>
                                    <option value="<?php echo $sale->id; ?>"><?php echo $sale->name ?>
                                        (<?php echo $sale->username ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                <?php } ?>
                <?php echo $this->form->renderField('amount'); ?>
                <?php echo $this->form->renderField('note'); ?>
                <div <?php if ($_GET['id'] == 0) { ?> style="display:none;" <?php } ?>>
                    <?php echo $this->form->renderField('status'); ?>
                </div>
                <div class="images" style="display:none;">
                    <?php echo $this->form->renderField('image'); ?>
                    <?php if (!empty($this->item->image)) : ?>
                        <?php $imageFiles = array(); ?>
                        <?php foreach ((array)$this->item->image as $fileSingle) : ?>
                            <?php if (!is_array($fileSingle)) : ?>
                                <a href="<?php echo JRoute::_(JUri::root() . 'images/banking' . DIRECTORY_SEPARATOR . $fileSingle, false); ?>"><?php echo $fileSingle; ?></a> |
                                <?php $imageFiles[] = $fileSingle; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <input type="hidden" name="jform[image_hidden]" id="jform_image_hidden"
                               value="<?php echo implode(',', $imageFiles); ?>"/>
                    <?php endif; ?>
                </div>

                <?php echo $this->form->renderField('created_time'); ?>

                <div>
                    <?php echo $this->form->renderField('type'); ?>
                </div>

                <?php if ((int)$this->userGroup[0] === 15) : ?>
                    <div class="control-group">
                        <div class="control-label">
                            <label for="">Người tạo</label>
                        </div>
                        <div class="controls">
                            <input type="hidden" name="jform[sbdm_id]" value="<?php echo $this->user->id?>"/>
                        <?php echo $this->user->name . ' - '. $this->user->username; ?></div>
                    </div>
                <?php endif; ?>

                <?php echo $this->form->renderField('updated_time'); ?>

                <?php //echo $this->form->renderField('mycalendar'); ?>

                <?php //echo $this->form->renderField('bank_name'); ?>

                <?php if ($this->state->params->get('save_history', 1)) : ?>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
                    </div>
                <?php endif; ?>
            </fieldset>
        </div>
    </div>
    <?php echo JHtml::_('bootstrap.endTab'); ?>

    <?php if (JFactory::getUser()->authorise('core.admin', 'recharge')) : ?>
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
        <?php echo $this->form->getInput('rules'); ?>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
    <?php endif; ?>
    <?php echo JHtml::_('bootstrap.endTabSet'); ?>

    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>

</form>
