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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');


$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_order') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'orderform.xml');
$canEdit    = $user->authorise('core.edit', 'com_order') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'orderform.xml');
$canCheckin = $user->authorise('core.manage', 'com_order');
$canChange  = $user->authorise('core.edit.state', 'com_order');
$canDelete  = $user->authorise('core.delete', 'com_order');
$month = $_GET['month'];
if($month !=''){
  $url_month = '&month='.$month;
}else{
  $url_month = '';
}
if(isset($_GET['project']) && $_GET['project'] > 0){
  $url_project_id = '&project='.$_GET['project'];
  $project_id = $_GET['project'];
}else{
  $url_project_id = "";
  $project_id = 0;
}

//$url_project_id = "&project=".$item->id;
?>

<h3><?php echo $this->params->get('page_title'); ?></h3>

<div class="row">
    <div class="col-lg-6 col-sm-12">
        Dự án: <select name="project" id="project">
        <option dir="<?php echo JRoute::_('index.php?option=com_order&view=orders&Itemid=288'.$url_month); ?>" value="" >Tất cả</option>
      <?php foreach ($this->listProject as $i => $item) {
            $url_project = "&project=".$item->id;
          ?>
                <option <?php if(isset($_GET['project'])&&$_GET['project'] == $item->id){ echo 'selected="selected"';} ?> dir="<?php echo JRoute::_('index.php?option=com_order&view=orders&Itemid=288'.$url_project.$url_month); ?>" value="<?php echo $item->id?>"><?php echo $item->title; ?></option>
        <?php }?>
        </select>
        <div clas="month_filter" style="padding-top:8px;">
        Tháng: <select name="month" id="month">
            <option value="" dir="<?php echo JRoute::_('index.php?option=com_order&view=orders&Itemid=288'.$url_project_id); ?>">Chọn tháng</option>
      <?php for ($i = 1; $i<=12 ; $i++) {
        if($i < 10){
          $parameter_month = '&month=0'.$i;
          $value_month = '0'.$i;
        }else{
          $parameter_month = '&month='.$i;
          $value_month = $i;
        }

          ?>
                <option <?php if($month == $value_month){ echo 'selected="selected"';} ?> dir="<?php echo JRoute::_('index.php?option=com_order&view=orders&Itemid=288'.$parameter_month.$url_project_id); ?>" value="<?php echo $value_month;?>">Tháng <?php echo $i; ?></option>
        <?php }?>
        </select>
      </div>
        <div style="margin-top:8px;" class="clear-btn"><a href="<?php echo JRoute::_('index.php?option=com_order&view=orders&Itemid=288'); ?>"><button style="" type="button" class="btn btn-dark">Xóa</button></a></div>

    </div>
    <div class="col-lg-6 col-sm-12">
      <div class="totalmoney">
      <strong><?php echo BIZ_XU; ?> đã dùng</strong>: <?php
      $end_month = '';
      $first_month = '';
      if($user->id > 0){
        if($month !=''){
          $year = date("Y");
          $first_month = $year."-".$month."-01 00:00:00";
      		$query_date = $year."-".$month."-01";
      		$end_month = date('Y-m-t 23:59:59', strtotime($query_date));
        }
      echo "<span class='price'>".number_format($this->getTotalMoney($user->id,$project_id,$first_month,$end_month),0,",",".")." ".BIZ_XU."</span>";
      }else{
      echo '<span class="price">0 '.BIZ_XU.'</span>';
      }
      ?></div>

    </div>


        </div>
<form action="<?php echo JRoute::_('index.php?option=com_order&view=orders'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php //echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
	<table class="table table-striped" id="orderList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<!-- <th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th> -->
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_CATEGORY_ID', 'a.category_id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_QUANTITY', 'a.quantity', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_PRICE', 'a.price', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_TOTAL', 'a.total', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_PROJECT_ID', 'a.project_id', $listDirn, $listOrder); ?>
				</th>
				<!-- <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th> -->
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'Ngày mua', 'a.create_date', $listDirn, $listOrder); ?>
				</th>
				<!-- <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ORDER_ORDERS_MODIFIED_DATE', 'a.modified_date', $listDirn, $listOrder); ?>
				</th> -->


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_ORDER_ORDERS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_order'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_order')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<!-- <td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_order&task=order.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td> -->
				<?php endif; ?>

								<td>

					#<?php echo $item->id; ?>
				</td>
				<td>

          <?php echo $this->getCatName($item->category_id); ?>
				</td>
				<td>

					<?php echo $item->quantity; ?>
				</td>
				<td>

					<?php //echo $item->price; ?>
          <strong class="price"><?php echo number_format($item->price,0,",","."); ?> <?php echo BIZ_XU; ?></strong>
				</td>
				<td>

					<?php //echo $item->total; ?>
          <strong class="price"><?php echo number_format($item->total,0,",","."); ?> <?php echo BIZ_XU; ?></strong>
				</td>
				<td>

          <?php echo $this->getProjectName($item->project_id); ?>
				</td>
				<!-- <td>

							<?php echo JFactory::getUser($item->created_by)->name; ?>
            </td> -->
				<td>

					<?php
          echo JFactory::getDate($item->create_date)->format("d-m-Y H:i:s");
          ?>
				</td>
				<!-- <td>

					<?php echo $item->modified_date; ?>
				</td> -->


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_order&task=orderform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_order&task=orderform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
    <tfoot>
		<tr>
			<th colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</th>
		</tr>
		</tfoot>
	</table>

	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_order&task=orderform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_ORDER_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
<script>
jQuery('#month').change(function () {
  window.location = jQuery('option:selected', this).attr('dir');
});
</script>
<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);


	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_ORDER_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}


</script>

<?php endif; ?>
<script>
jQuery('#project').change(function () {
  //alert(jQuery(this).attr('dir'));

      window.location = jQuery('option:selected', this).attr('dir');


});
</script>
<style>
.price{font-weight:bold;}
.totalmoney{ text-align: right; padding-bottom: 10px;}
@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {
        /* Force table to not be like tables anymore */
        table, thead, tbody, th, td, tr {
            display: block;
        }
        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        tr { border: 1px solid #eee; margin-bottom: 10px}
        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            border-top: 0 !important;
        }
        td:last-of-type{
            border: none;
        }
        td:before {
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            color: #0b0b0b;
            font-weight: 600;
        }
        /*
		Label the data
		*/
        td:nth-of-type(1):before { content: "ID:"; }
        td:nth-of-type(2):before { content: "Danh mục:"; }
        td:nth-of-type(3):before { content: "Số lượng:"; }
        td:nth-of-type(4):before { content: "Đơn giá:"; }
        td:nth-of-type(5):before { content: "Tổng giá:"; }
        td:nth-of-type(6):before { content: "Dự án:"; }
        td:nth-of-type(7):before { content: "Ngày mua:"; }

        .note{
            display: inline-block;
        }
        .btn{
            width: auto;
        }
        .pagination{margin: 0px;}
    }
</style>
