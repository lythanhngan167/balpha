<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Request_package
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
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
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_request_package') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'requestpackageform.xml');
$canEdit    = $user->authorise('core.edit', 'com_request_package') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'requestpackageform.xml');
$canCheckin = $user->authorise('core.manage', 'com_request_package');
$canChange  = $user->authorise('core.edit.state', 'com_request_package');
$canDelete  = $user->authorise('core.delete', 'com_request_package');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_request_package/css/list.css');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
        <div class="table-responsive">
	<table class="table table-striped" id="requestpackageList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<!-- <th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th>
			<?php endif; ?> -->

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_COMPANY', 'a.company', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_SERVICES', 'a.services', $listDirn, $listOrder); ?>
				</th>
				<!-- <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_EMAIL', 'a.email', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_PHONE', 'a.phone', $listDirn, $listOrder); ?>
				</th> -->
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_ADDRESS', 'a.address', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_PROVINCE', 'a.province', $listDirn, $listOrder); ?>
				</th>
				<!-- <th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_JOB', 'a.job', $listDirn, $listOrder); ?>
				</th> -->
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_NOTE', 'a.note', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_REQUEST_PACKAGE_REQUESTPACKAGES_STATUS', 'a.status', $listDirn, $listOrder); ?>
				</th>
				


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_REQUEST_PACKAGE_REQUESTPACKAGES_ACTIONS'); ?>
				</th>
				<?php endif; ?>

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
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_request_package'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_request_package')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<!-- <?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_request_package&task=requestpackage.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td> -->
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>

					<?php echo $item->company; ?>
				</td>
				<td>

					<?php echo $item->services; ?>
				</td>
				<!-- <td>

							<?php echo JFactory::getUser($item->created_by)->name; ?>				</td>
				<td>

					<?php echo $item->name; ?>
				</td>
				<td>

					<?php echo $item->email; ?>
				</td>
				<td>

					<?php echo $item->phone; ?>
				</td> -->
				<td>

					<?php echo $item->address; ?>
				</td>
				<td>

					<?php echo $item->province; ?>
				</td>
				<!-- <td>

					<?php echo $item->job; ?>
				</td> -->
				
				<td>
					<?php echo $item->note; ?>
				</td>
				
				<td>

					<?php echo $item->status; ?>
				</td>
				


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_request_package&task=requestpackage.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_request_package&task=requestpackageform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<!-- <?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_request_package&task=requestpackageform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_REQUEST_PACKAGE_ADD_ITEM'); ?></a>
	<?php endif; ?> -->

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo Text::_('COM_REQUEST_PACKAGE_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>

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
        td:nth-of-type(2):before { content: "Công ty Bảo hiểm:"; }
        td:nth-of-type(3):before { content: "Dịch vụ cần tư vấn:"; }
        td:nth-of-type(4):before { content: "Địa chỉ:"; }
        td:nth-of-type(5):before { content: "Tỉnh/TP:"; }
        td:nth-of-type(6):before { content: "Ghi chú thêm:"; }
        td:nth-of-type(7):before { content: "Trạng thái:"; }
        /* td:nth-of-type(8):before { content: "Trạng thái:"; } */
        <?php if($this->status_id != 7){ ?>
            /*td:nth-of-type(9):before { content: "Ngày mua:"; }*/
            td:nth-of-type(9):before { content: "Ghi chú:"; }
        <?php } ?>
        <?php if($this->status_id == 7){ ?>
          td:nth-of-type(9):before { content: "Ngày hoàn thành:";}
          td:nth-of-type(10):before { content: "Doanh thu:";}
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
