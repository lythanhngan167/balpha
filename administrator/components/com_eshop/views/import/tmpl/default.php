<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
?>
<script type="text/javascript">
    function importProducts() {
        var form = document.adminForm;
        if (form.excel_file.value =="") {
            alert("Vui lòng chọn file.");
            return;
        }

        form.task.value = 'import.products';
        form.submit();
    }
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"
      class="form form-horizontal">
    <div class="control-group">
        <div class="control-label">
            <span class="required">*</span>
            <?php echo JText::_('ESHOP_NAME'); ?>
        </div>
        <div class="controls" style="margin-left: 380px;">
            <input type="file" name="excel_file" id="excel_file" size="57" class="input_box" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="option" value="com_eshop" />
            <input type="button" class="btn btn-primary" value="<?php echo JText::_('ESHOP_INSTALL'); ?>" onclick="importProducts();" />
            <?php echo JHtml::_( 'form.token' ); ?>
        </div>
    </div>

</form>

