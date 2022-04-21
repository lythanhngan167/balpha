<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Export_customer
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_export_customer/assets/css/export_customer.css');
$document->addStyleSheet(JUri::root() . 'media/com_export_customer/css/list.css');

$user = JFactory::getUser();
$userId = $user->get('id');
?>
<style>
    #toolbar-new {
        display: none;
    }

    .control-group .control-label, .control-group .controls , .export_form label{
        display: inline-block;
    }
    .export_form label{
        width: 100px;
    }
</style>
<div class="export_form" style="text-align:center">
    <form method="post" action="<?php echo JUri::root() . 'administrator'; ?>/index.php?option=com_export_customer">
    <h3>Xuất dữ liệu Khách hàng ra Excel</h3>

    <?php echo $this->form->renderField('created_by'); ?>
    <br>
    <label>
        Chọn Dự án</label>
    <select name="project" id="project">
      <option dir="<?php echo JRoute::_('index.php?option=com_users&view=sales&month=' . $month . '&year=' . $year . '&leader=' . $leader->id); ?>"
              value="">Tất cả</option>
        <?php foreach ($this->project as $project) :

            ?>

            <option dir="<?php echo JRoute::_('index.php?option=com_users&view=sales&month=' . $month . '&year=' . $year . '&leader=' . $leader->id); ?>"
                    value="<?php echo $project->id; ?>"><?php echo $project->title; ?></option>
        <?php endforeach; ?>
    </select>
    <br>
    <br>

    <label>Chọn Trạng thái</label>
    <select id="status_id" name="status_id">
        <option value="" >Tất cả</option>
        <option value="1">New (Mới)</option>
        <option value="2">Shilly – Shally (Lưỡng lự)</option>
        <option value="3">Interested (Quan tâm)</option>
        <!-- <option value="4">Very Interested (Rất Quan tâm)</option> -->
        <option value="7">Done (Hoàn thành)</option>
        <option value="6">Return (Trả lại)</option>
        <!-- <option value="8">Cancel (Hủy)</option> -->
    </select>
    <br>
    <br>
    <?php echo $this->form->renderField('month'); ?>

    <br>
    <?php echo $this->form->renderField('year'); ?>
    <br>
    <br>
    <button class="btn btn-success" type="submit" >Xuất Excel</button>
    <button class="btn btn-sumary" type="reset" onclick="resetForm()" >Xóa</button>
    </form>
</div>
<script>
    <?php if($_POST['jform']){ ?>
    jQuery( document ).ready(function() {
      var h_created_by = jQuery('#h_created_by').val();
      var h_project = jQuery('#h_project').val();
      var h_month = jQuery('#h_month').val();
      var h_year = jQuery('#h_year').val();
      var h_status_id = jQuery('#h_status_id').val();

      if(h_created_by != ''){
        //jQuery('#jform_created_by option[value=h_created_by]').attr('selected','selected');
        jQuery('#jform_created_by').val(h_created_by).trigger('liszt:updated');
        jQuery('#project').val(h_project).trigger('liszt:updated');
        jQuery('#status_id').val(h_status_id).trigger('liszt:updated');
        jQuery('#jform_month').val(h_month).trigger('liszt:updated');
        jQuery('#jform_year').val(h_year).trigger('liszt:updated');

      }

    });
    <?php } ?>
    function resetForm() {
      jQuery('#jform_created_by').val('').trigger('liszt:updated');
      jQuery('#project').val('').trigger('liszt:updated');
      jQuery('#status_id').val('').trigger('liszt:updated');
      jQuery('#jform_month').val('').trigger('liszt:updated');
      jQuery('#jform_year').val('<?php echo date('Y') ?>').trigger('liszt:updated');
    }
    function exportExcel() {
        var project = jQuery('#project').val();
        var status_id = jQuery('#status_id').val();
        var created_by = jQuery('select[name="jform[created_by]"]').val();
        var month = jQuery('select[name="jform[month]"]').val();
        var year = jQuery('select[name="jform[year]"]').val();
        window.location = '<?php echo JUri::root() . 'administrator'; ?>/index.php?option=com_export_customer&project=' + project + '&status_id=' + status_id+'&created_by='+created_by+'&month='+month+'&year='+year;
    }
</script>
