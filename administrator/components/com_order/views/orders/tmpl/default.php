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
$created_by = $this->state->get('filter.created_by');

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_order&task=orders.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'orderList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo JRoute::_('index.php?option=com_order&view=orders'); ?>" method="get"
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
			<?php if ($created_by):
				$created_user = JFactory::getUser($created_by);
				?>


			<a href="<?php echo JRoute::_('index.php?option=com_order&view=reports&filter[status]=1&filter[sale_id]='.$created_user->id); ?>" class="btn btn-primary btn-small btn-light" role="button" >Xem dữ liệu đã mua của <?= $created_user->name?></a>
			<a href="<?php echo JRoute::_('index.php?option=com_order&view=reports&filter[status]=2&filter[sale_id]='.$created_user->id); ?>" class="btn btn-primary btn-small btn-danger" role="button" >Xem dữ liệu trả lại của <?= $created_user->name?></a>
			<?php endif;?>
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
					<?php if (isset($this->items[0]->state)): ?>
						<!-- <th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th> -->
					<?php endif; ?>

									<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_CATEGORY_ID', 'a.`category_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_QUANTITY', 'a.`quantity`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_PRICE', 'a.`price`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_TOTAL', 'a.`total`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_PROJECT_ID', 'a.`project_id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_CREATED_BY', 'a.`created_by`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_CREATE_DATE', 'a.`create_date`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort', 'COM_ORDER_ORDERS_MODIFIED_DATE', 'a.`modified_date`', $listDirn, $listOrder); ?>
				</th>


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
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<!-- <td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'orders.', $canChange, 'cb'); ?>
</td> -->
						<?php endif; ?>

										<td>

					<?php echo $item->id; ?>
				</td>				<td>


					<?php
                    echo $this->getNameCat($item->category_id);
                     ?>
				</td>				<td>

					<?php echo $item->quantity; ?>
				</td>				<td>

					<span class="price"><?php echo number_format($item->price, 0, ",", "."); ?></span>
				</td>				<td>

					<span class="price"><?php echo number_format($item->total, 0, ",", "."); ?></span>
				</td>				<td>

					<?php echo $this->getNameProject($item->project_id); ?>
				</td>				<td>

					<?php echo $item->created_by; ?>
          <br>
					<?php
					$createdUser   = JFactory::getUser($item->created_by_id);
					echo $createdUser->username;
					?>
				</td>				<td>

					<?php echo JFactory::getDate($item->create_date)->format("d-m-Y H:i"); ?>
				</td>				<td>

					<?php echo JFactory::getDate($item->modified_date)->format("d-m-Y H:i"); ?>
				</td>

					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="option" value="com_order"/>
			<input type="hidden" name="view" value="orders"/>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
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
</script>
<style>
.price{color:red; font-weight:bold;}
#toolbar-new,#toolbar-copy,#toolbar-edit,#toolbar-publish,#toolbar-unpublish,#toolbar-archive,#toolbar-checkin,#toolbar-trash{
  display: none;

}
</style>
