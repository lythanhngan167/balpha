<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Order
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_order/assets/css/order.css');
$document->addStyleSheet(JUri::root() . 'media/com_order/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_order');
$saveOrder = $listOrder == 'a.`ordering`';
$sale_id = $this->state->get('filter.sale_id');
$status = $this->state->get('filter.status');


if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_order&task=orders.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'orderList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<style>
.text-danger{
	color: #dc3545;
}
.text-success{
	color: #28a745;
}
</style>
<script type="text/javascript">
function payback(){
	if (document.adminForm.boxchecked.value == 0) {
		alert('Vui lòng chọn khách hàng để hoàn tiền.');
		} else {
			jQuery.ajax({
			url: 'index.php?option=com_order&tmpl=component&task=orders.payback',
			type: 'post',
			data: jQuery('#adminForm').serialize(),
			dataType: 'json',
			async: true,
			success: function(response){
				alert(response.message);
				location.reload();

			}
		});
		}
}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_order&view=reports'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

            <?php echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

			<div class="clearfix"></div>

			<br />
			<!-- <p style="float: left;">Hoàn tiền cho 1 khách hàng: <b><?= number_format(RETURN_AMOUNT);?></b></p> -->
			<button type="button" onclick="payback();" class="btn btn-primary float-right" style="float: right;">Hoàn tiền</button>
			<br /><br />

			<div class="clearfix"></div>
			<table class="table table-striped" id="orderList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>


									<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'Khách hàng', 'a.`name`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'Điện thoại', 'a.`phone`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'Email', 'a.`email`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'Ngày mua', 'a.`buy_date`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>Trạng thái</th>
        <th class='left'>Giá tiền</th>
				<th class='left'>Hoàn tiền</th>




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
                    $ordering   = ($listOrder == 'a.ordering');
                    $canCreate  = $user->authorise('core.create', 'com_order');
                    $canEdit    = $user->authorise('core.edit', 'com_order');
                    $canCheckin = $user->authorise('core.manage', 'com_order');
                    $canChange  = $user->authorise('core.edit.state', 'com_order');
                    ?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
                                    $disableClassName = '';
                                    $disabledLabel    = '';

                                    if (!$saveOrder) :
                                        $disabledLabel    = JText::_('JORDERINGDISABLED');
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
						<?php if ($this->showCheckbox($item)):?>
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								<?php endif;?>
						</td>


										<td>

					<?php echo $item->id; ?>
				</td>				<td>


				<?= $item->name; ?>
				<td>


				<?= $item->phone; ?>

				<td>


				<?= $item->email; ?>
				</td>				<td>

				<?= JFactory::getDate($item->buy_date)->format("d-m-Y H:i"); ?>
				</td>				<td>

					<?= $this->getStatus($item); ?>
				</td>
        <td>
          <span class="price"><?php
          $project = $this->getProject($item->project_id);
          echo number_format($project->price,0,".",".") . " đ";
           ?>
         </span>
         </td>
        <td>

				<?= $this->getPayBackStatus($item); ?>
				</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="boxchecked" value="0"/>
			<input type="hidden" name="sale_id" value="<?= $sale_id ?>"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[ id ];

        if (!cb) return false;

        while (true) {
            cbx = f[ 'cb' + i ];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField   = document.createElement('input');

        inputField.type  = 'hidden';
        inputField.name  = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
	jQuery(document).ready(function(){
		jQuery('.js-stools-btn-clear').hide();
	});
</script>
<style>
.price{color:red; font-weight:bold;}
</style>
