<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
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
$canCreate  = $user->authorise('core.create', 'com_customer') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'customerform.xml');
$canEdit    = $user->authorise('core.edit', 'com_customer') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'customerform.xml');
$canCheckin = $user->authorise('core.manage', 'com_customer');
$canChange  = $user->authorise('core.edit.state', 'com_customer');
$canDelete  = $user->authorise('core.delete', 'com_customer');
?>
<style>
.red2{ background-color:red; width:20px; height:20px; display:block; float:left;}
.green2{ background-color:green; width:20px; height:20px; display:block; float:left;}
.red2{ background-color:red; width:20px; height:20px; display:block; float:left;}
.blue2{ background-color:blue; width:20px; height:20px; display:block; float:left;}
.yellow2{ background-color:yellow; width:20px; height:20px; display:block; float:left; }
.black2{ background-color:black; width:20px; height:20px; display:block; float:left;}
.color2{float:left; padding-right:10px;}
.rectangle{ margin-right:10px; margin-left:4px;}
.wrap-color{
  padding-top:10px;
  padding-bottom:10px;
}

</style>
<h3><?php echo $this->params->get('page_title'); ?></h3>

<form action="<?php echo JRoute::_('index.php?option=com_customer&view=customers'); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
  <div class="wrap-color">
  <input type="radio" class="color2" <?php  if($_REQUEST['color'] == 'red') echo 'checked="checked"'; ?> onclick='openColor("red")' name="color" value="red"> <span class="rectangle red2"> </span>
  <input type="radio" class="color2" <?php  if($_REQUEST['color'] == 'green') echo 'checked="checked"'; ?> onclick='openColor("green")' name="color" value="green"> <span class="rectangle green2"> </span>
  <input type="radio" class="color2" <?php  if($_REQUEST['color'] == 'blue') echo 'checked="checked"'; ?> onclick='openColor("blue")' name="color" value="blue"> <span class="rectangle blue2"> </span>
  <input type="radio" class="color2" <?php  if($_REQUEST['color'] == 'yellow') echo 'checked="checked"'; ?> onclick='openColor("yellow")' name="color" value="yellow"> <span class="rectangle yellow2"> </span>
  <input type="radio" class="color2" <?php  if($_REQUEST['color'] == 'black') echo 'checked="checked"'; ?> onclick='openColor("black")' name="color" value="black"> <span class="rectangle black2"> </span>
</div>
	<table class="table table-striped" id="customerList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<!-- <th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th> -->
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_PHONE', 'a.phone', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_EMAIL', 'a.email', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_PLACE', 'a.place', $listDirn, $listOrder); ?>
				</th>
				<!-- <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_SALE_ID', 'a.sale_id', $listDirn, $listOrder); ?>
				</th> -->
				<!-- <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_CATEGORY_ID', 'a.category_id', $listDirn, $listOrder); ?>
				</th> -->
        <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_PROVINCE_ID', 'a.province', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_PROJECT_ID', 'a.project_id', $listDirn, $listOrder); ?>
				</th>
				<?php if($this->status_id === "99"){?>
					<th class=''>
					<?php echo JHtml::_('grid.sort',  'Duyệt', 'a.approve_id', $listDirn, $listOrder); ?>
					</th>
				<?php } else { ?>
					<th class=''>
					<?php echo JHtml::_('grid.sort',  'COM_CUSTOMER_CUSTOMERS_STATUS_ID', 'a.status_id', $listDirn, $listOrder); ?>
					</th>
				<?php } ?>

        <?php if($this->status_id != 7){ ?>
        <!-- <th class=''>
				<a>Ngày mua</a>
				</th> -->
        <th class=''>
        <a>Ghi chú</a>
        </th>
        <?php } ?>

		<?php if($this->status_id === "99"){?>
			<?php if((int)$item->rating_id === 3){?>
				<th>
					<?php echo JHtml::_('grid.sort',  'Lý do', 'a.rating_note', $listDirn, $listOrder); ?>
				</th>
			<?php } else {?>
				<th>
					<?php echo JHtml::_('grid.sort',  'Lý do', 'a.rating_id', $listDirn, $listOrder); ?>
				</th>
			<?php }?>
		<?php }?>

        <?php if($this->status_id == 7){ ?>
          <th class=''>
          <a>Ngày hoàn thành</a>
          </th>
				<th class=''>
				<a>Doanh thu</a>
				</th>
        <?php } ?>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_CUSTOMER_CUSTOMERS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) :

      ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_customer'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_customer')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?> color <?php echo $item->color; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<!-- <td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_customer&task=customer.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td> -->
				<?php endif; ?>

								<td>

					<a href="<?php echo JRoute::_('index.php?option=com_customer&view=customer&id='.(int) $item->id); ?>">
            #<?php echo $item->id; ?>
          </a>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'customers.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_customer&view=customer&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->name); ?></a>
				</td>
				<td>

					<?php echo $item->phone; ?>
				</td>
				<td class="break-word">

					<?php echo $item->email; ?>
				</td>
				<td class="break-word">

					<?php echo $item->place; ?>
				</td>
				<!-- <td>

					<?php echo $item->sale_id; ?>
				</td> -->
				<!-- <td>

					<?php echo $item->category_id; ?>
				</td> -->
        		<td>

					<?php echo $item->province; ?>
				</td>
				<td>

					<?php echo $item->project_id; ?>
				</td>
				<?php if($this->status_id === "99"){?>

					<?php if((int)$item->trash_approve === 0){?>
						<td>
              <?php //if($item->project_id_int == AT_PROJECT){?>
              <?php echo '<span id="trashStatus" class="badge badge-warning">Chờ duyệt</span>' ?>
              <?php //} ?>
            </td>
					<?php } ?>
					<?php if((int)$item->trash_approve === 2){?>
						<td>
              <?php //if($item->project_id_int == AT_PROJECT){?>
              <?php echo '<span id="trashStatus" class="badge badge-danger">Từ chối</span>' ?>
              <?php //} ?>
            </td>
					<?php } ?>

				<?php } else { ?>
					<td>
					<?php echo $item->status_id; ?>
					</td>
				<?php } ?>

        <?php if($this->status_id != 7){ ?>
        <!-- <td>

					<?php
          echo JFactory::getDate($item->buy_date)->format("d-m-Y H:i");
          ?>
				</td> -->
        <td>
          <?php $lastest_note = $this->getLatestNote($item->id); ?>
          <?php if($lastest_note->id > 0){ ?><p><?php echo $lastest_note->note; ?> </p><?php } ?>
          <?php if($lastest_note->id > 0){ ?><p><strong>Ngày:</strong> <?php echo JFactory::getDate($lastest_note->create_date)->format("d-m-Y H:i"); ?> </p> <?php } ?>
          <?php if($this->status_id == 6){ ?>
          <span style="color:red;"><?php
          if($item->payback == 0){
            echo 'Chưa hoàn BizXu';
          }else{
            echo 'Đã hoàn BizXu';
          } ?></span>
         <?php } ?>
				</td>
        <?php } ?>

        <?php if($this->status_id == 7){ ?>
          <td>

  					<?php
            echo JFactory::getDate($item->modified_date)->format("d-m-Y H:i");
            ?>
  				</td>
				<td>

					<span class="price"><?php echo number_format($item->total_revenue,0,",","."); ?> BizXu</span>
				</td>
        <?php } ?>

		<?php if($this->status_id === "99"){?>
			<?php if((int)$item->rating_id === 3){?>
				<td>
					<?php //echo $item->rating_note?>
          <?php echo $item->rating_text; ?>
          <?php if($item->trash_confirmed_by_dm != ''){ ?>
          <br>
          <b>DM</b>: <?php echo $item->trash_confirmed_by_dm; ?>
          <?php if($item->rating_note != ''){ ?>
          <br><b>Ghi chú</b>: <?php echo $item->rating_note; ?>
          <?php } ?>
          <?php } ?>
				</td>
			<?php } else {?>
				<td>
					<?php echo $item->rating_text; ?>
          <?php if($item->trash_confirmed_by_dm != ''){ ?>
          <br>
          <b>DM</b>: <?php echo $item->trash_confirmed_by_dm; ?>
          <?php if($item->rating_note != ''){ ?>
          <br><b>Ghi chú</b>: <?php echo $item->rating_note; ?>
          <?php } ?>
          <?php } ?>
				</td>
			<?php }?>
		<?php } ?>
					<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_customer&task=customerform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_customer&task=customerform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
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
		<!-- <a href="<?php echo JRoute::_('index.php?option=com_customer&task=customerform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_CUSTOMER_ADD_ITEM'); ?></a> -->
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_CUSTOMER_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}


</script>
<?php endif; ?>

<script>

jQuery(document).ready(function(){

    <?php if($_REQUEST['Itemid'] == 282 ||
    $_REQUEST['Itemid'] == 283 ||
    $_REQUEST['Itemid'] == 284 ||
    $_REQUEST['Itemid'] == 285 ||
    $_REQUEST['Itemid'] == 286 ||
    $_REQUEST['Itemid'] == 438
    ){
    ?>
    jQuery('.item-281.plus').trigger('click');
    <?php
    }
    ?>

    <?php if($_REQUEST['Itemid'] == 295 ||
    $_REQUEST['Itemid'] == 296 ||
    $_REQUEST['Itemid'] == 297 ||
    $_REQUEST['Itemid'] == 298
    ){
    ?>
    jQuery('.item-294.plus').trigger('click');
    <?php
    }
    ?>
});


function openColor(color){
  var url = '<?php echo JUri::base().'index.php?option=com_customer&view=customers&Itemid='.$_REQUEST['Itemid'].'&color='; ?>'+color
  window.location = url;
}
</script>

<style>
.price{ font-weight:bold;}
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
        td:nth-of-type(2):before { content: "Họ tên:"; }
        td:nth-of-type(3):before { content: "Điện thoại:"; }
        td:nth-of-type(4):before { content: "Email/Facebook:"; }
        td:nth-of-type(5):before { content: "Địa chỉ/Khác:"; }
        td:nth-of-type(6):before { content: "Tỉnh/TP:"; }
        td:nth-of-type(7):before { content: "Dự án:"; }
        td:nth-of-type(8):before { content: "Trạng thái:"; }
        <?php if($this->status_id != 7){ ?>
            /*td:nth-of-type(9):before { content: "Ngày mua:"; }*/
            td:nth-of-type(9):before { content: "Ghi chú:"; }
        <?php } ?>
        <?php if($this->status_id == 7){ ?>
          td:nth-of-type(9):before { content: "Ngày hoàn thành:";}
          td:nth-of-type(10):before { content: "Doanh thu:";}
        <?php } ?>

        <?php if($this->status_id == 99){?>
          td:nth-of-type(10):before { content: "Lý do:";}
        <?php } ?>

        .note{
            display: inline-block;
        }
        .btn{
            width: auto;
        }
        .pagination{margin: 0px;}
    }
    .color.green{
      background-color:#e2fbe2!important;
    }
    .color.black{
      background-color:#e6e6e6!important;
    }
    .color.red{
      background-color:#f9e8e8!important;
    }
    .color.yellow{
      background-color:#fbfbe7!important;
    }
    .color.blue{
      background-color:#e3e3fb!important;
    }
</style>
