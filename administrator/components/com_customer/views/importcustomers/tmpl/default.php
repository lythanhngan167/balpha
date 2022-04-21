<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Project
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
use Joomla\CMS\Uri\Uri;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_project/assets/css/project.css');
$document->addStyleSheet(JUri::root() . 'media/com_project/css/list.css');

$user = JFactory::getUser();
//$sortFields = $this->getSortFields();
?>


    <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
            <?php endif; ?>

            <?php //echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

            <div class="clearfix"></div>
            <table class="table table-striped" id="projectsList">
                <thead>
                <tr>
									<th class='left'>
										ID
									</th>
									<th class='left'>
										Họ tên
									</th>
									<th class='left'>
										Số điện thoại
									</th>
									<th class='left'>
										Email
									</th>
									<th class='left'>
										Mã dự án
									</th>


									<th class='left'>
										Thao tác
									</th>
                  <th class='left'>
										Link
									</th>


                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->items as $i => $item) :

                    ?>
                    <tr class="row<?php echo $i % 2; ?>">

											<td class=" nowrap">
													<?php echo $item->id;  ?>
											</td>
											<td class=" nowrap">
													<?php echo $item->name;  ?>
											</td>
											<td class=" nowrap">
													<?php echo $item->phone;  ?>
											</td>
											<td class=" nowrap">
													<?php echo $item->email;  ?>
											</td>
											<td class=" nowrap">
													<?php echo $item->project_id;  ?>
											</td>
                      <td class=" nowrap">
												<button type="button" id="button-<?php echo $item->id; ?>" class="btn btn-success" onclick="importCustomer(<?php echo $item->id; ?>,'<?php echo $item->name; ?>','<?php echo $item->phone; ?>','<?php echo $item->email; ?>','<?php echo $item->project_id; ?>','<?php echo $item->link; ?>', <?php echo $i; ?>)">Import</button>
												<div id="import-text-<?php echo $item->id; ?>"></div>
											</td>
											<td class=" nowrap">
													<?php echo $item->link;  ?>
											</td>





                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<script>
function importCustomer(id,name,phone,email,project_id,link, i) {
	var result = confirm("Bạn có chắc muốn Import?");
	if(result === true) {
		jQuery.ajax({
			url: "<?php echo Uri::root(); ?>index.php?option=com_registration&task=registrationform.saveLandingPage",
			type: "POST",
			dataType:"text",
			data : {
				name : name,
				phone : phone,
				email : email,
				link : link
			},
			success: function (result) {
				if(result > 0){
					jQuery('#button-' + id).css('display', 'none');
					jQuery('#import-text-' + id).html('<span class="badge badge-success">Đã Import</span>');
					jQuery.ajax({
						url: "<?php echo Uri::base(); ?>index.php?option=com_customer&task=importcustomers.updateStatus",
						type: "POST",
						dataType:"text",
						data : {
							id : id
						},
						success: function (result) {

						}
					});
				} else {
					alert("Import thất bại, vui lòng thử lại!");
				}

			}
		});
	}
}
</script>
<style>
.nowrap{
  word-break: break-all;
}
</style>
