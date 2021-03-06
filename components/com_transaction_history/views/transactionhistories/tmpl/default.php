<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Transaction_history
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
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user = Factory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_transaction_history') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'transactionhistoryform.xml');
$canEdit = $user->authorise('core.edit', 'com_transaction_history') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'transactionhistoryform.xml');
$canCheckin = $user->authorise('core.manage', 'com_transaction_history');
$canChange = $user->authorise('core.edit.state', 'com_transaction_history');
$canDelete = $user->authorise('core.delete', 'com_transaction_history');
?>
<h3><?php echo $this->params->get('page_title'); ?></h3>
<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

    <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <table class="table table-striped" id="transactionhistoryList">
        <thead>
        <tr>
            <?php if (isset($this->items[0]->state)): ?>
                <!-- <th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th> -->
            <?php endif; ?>

            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_ID', 'a.id', $listDirn, $listOrder); ?>
            </th>
            <!-- <th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th> -->
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_TITLE', 'a.title', $listDirn, $listOrder); ?>
            </th>
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_AMOUNT', 'a.amount', $listDirn, $listOrder); ?>
            </th>
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_CREATED_DATE', 'a.created_date', $listDirn, $listOrder); ?>
            </th>
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_TYPE_TRANSACTION', 'a.type_transaction', $listDirn, $listOrder); ?>
            </th>
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_STATUS', 'a.status', $listDirn, $listOrder); ?>
            </th>
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_REFERENCE_ID', 'a.reference_id', $listDirn, $listOrder); ?>
            </th>


            <?php if ($canEdit || $canDelete): ?>
                <th class="center">
                    <?php echo JText::_('COM_TRANSACTION_HISTORY_TRANSACTIONHISTORIES_ACTIONS'); ?>
                </th>
            <?php endif; ?>

        </tr>
        </thead>

        <tbody>
        <?php foreach ($this->items as $i => $item) : ?>
            <?php $canEdit = $user->authorise('core.edit', 'com_transaction_history'); ?>

            <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_transaction_history')): ?>
                <?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
            <?php endif; ?>

            <tr class="row<?php echo $i % 2; ?>">

                <?php if (isset($this->items[0]->state)) : ?>
                    <?php $class = ($canChange) ? 'active' : 'disabled'; ?>
                    <!-- <td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_transaction_history&task=transactionhistory.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
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
                <!-- <td>

							<?php echo JFactory::getUser($item->created_by)->name; ?>
            </td> -->
                <td>
                    <?php if (isset($item->checked_out) && $item->checked_out) : ?>
                        <?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'transactionhistories.', $canCheckin); ?>
                    <?php endif; ?>
                    <a href="<?php echo JRoute::_('index.php?option=com_transaction_history&view=transactionhistory&id=' . (int)$item->id); ?>">
                        <?php echo $this->escape($item->title); ?></a>
                </td>
                <td>


                    <span class="price"><?php echo number_format($item->amount, 0, ".", "."); ?> <?php echo BIZ_XU; ?></span>
                </td>
                <td>

                    <?php
                    echo date("d-m-Y H:i:s", strtotime($item->created_date));
                    ?>
                </td>
                <td>

                    <?php echo $item->type_transaction; ?>
                </td>
                <td>

                    <?php echo $item->status; ?>
                </td>
                <td>

                    <?php echo $item->reference_id; ?>
                </td>


                <?php if ($canEdit || $canDelete): ?>
                    <!-- <td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_transaction_history&task=transactionhistoryform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_transaction_history&task=transactionhistoryform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td> -->
                <?php endif; ?>

            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
                <?php echo $this->pagination->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
    </table>

    <?php if ($canCreate) : ?>
        <!-- <a href="<?php echo Route::_('index.php?option=com_transaction_history&task=transactionhistoryform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_TRANSACTION_HISTORY_ADD_ITEM'); ?></a> -->
    <?php endif; ?>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
    <?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php if ($canDelete) : ?>
    <script type="text/javascript">

        jQuery(document).ready(function () {
            jQuery('.delete-button').click(deleteItem);
        });

        function deleteItem() {

            if (!confirm("<?php echo Text::_('COM_TRANSACTION_HISTORY_DELETE_MESSAGE'); ?>")) {
                return false;
            }
        }
    </script>
<?php endif; ?>

<style>

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
        tbody td:nth-of-type(1):before { content: "ID:"; }
        tbody td:nth-of-type(2):before { content: "Ti??u ?????:"; }
        tbody td:nth-of-type(3):before { content: "S??? BizXu:"; }
        tbody td:nth-of-type(4):before { content: "Ng??y t???o:"; }
        tbody td:nth-of-type(5):before { content: "Lo???i Giao d???ch:"; }
        tbody td:nth-of-type(6):before { content: "Tr???ng th??i:"; }
        tbody td:nth-of-type(7):before { content: "M?? tham chi???u:"; }

        .note{
            display: inline-block;
        }
        .btn{
            width: auto;
        }
        .pagination{margin: 0px;}
    }
</style>
