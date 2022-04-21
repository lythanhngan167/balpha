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
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user = Factory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_recharge') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'rechargeform.xml');
$canEdit = $user->authorise('core.edit', 'com_recharge') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'rechargeform.xml');
$canCheckin = $user->authorise('core.manage', 'com_recharge');
$canChange = $user->authorise('core.edit.state', 'com_recharge');
$canDelete = $user->authorise('core.delete', 'com_recharge');
?>
<h3>Lịch sử nạp <?php echo BIZ_XU; ?></h3>
<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

    <?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <?php if ($canCreate) : ?>
        <!-- <a  href="<?php echo Route::_('index.php?option=com_recharge&task=rechargeform.edit&id=0', false, 0); ?>"
           class="btn btn-success btn-small can-create"><i
                    class="icon-plus"></i>
            <?php echo 'Nạp BizXu';  ?></a> -->
    <?php endif; ?>
    <!-- Text::_('COM_RECHARGE_ADD_ITEM'); -->
    <table class="table table-striped" id="rechargeList">
        <thead>
        <tr>
            <?php if (isset($this->items[0]->state)): ?>
                <!-- <th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th> -->
            <?php endif; ?>

            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_ID', 'a.id', $listDirn, $listOrder); ?>
            </th>
            <!-- <th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_MODIFIED_BY', 'a.modified_by', $listDirn, $listOrder); ?>
				</th> -->

            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_CODE', 'a.code', $listDirn, $listOrder); ?>
            </th>
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_AMOUNT', 'a.amount', $listDirn, $listOrder); ?>
            </th>

            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_STATUS', 'a.status', $listDirn, $listOrder); ?>
            </th>

<!--            <th class=''>-->
<!--                --><?php //echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_BANK_NAME', 'a.bank_name', $listDirn, $listOrder); ?>
<!--            </th>-->

            <!-- <th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_IMAGE', 'a.image', $listDirn, $listOrder); ?>
				</th> -->
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_CREATED_TIME', 'a.created_time', $listDirn, $listOrder); ?>
            </th>

            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_TYPE', 'a.type', $listDirn, $listOrder); ?>
            </th>
            <th class=''>
                <?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_NOTE', 'a.note', $listDirn, $listOrder); ?>
            </th>
            <!-- <th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_RECHARGE_RECHARGES_UPDATED_TIME', 'a.updated_time', $listDirn, $listOrder); ?>
				</th> -->


            <?php if ($canEdit || $canDelete): ?>
                <!-- <th class="center">
				<?php echo JText::_('COM_RECHARGE_RECHARGES_ACTIONS'); ?>
				</th> -->
            <?php endif; ?>

        </tr>
        </thead>

        <tbody>
        <?php foreach ($this->items as $i => $item) : ?>
            <?php $canEdit = $user->authorise('core.edit', 'com_recharge'); ?>

            <?php if (!$canEdit && $user->authorise('core.edit.own', 'com_recharge')): ?>
                <?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
            <?php endif; ?>

            <tr class="row<?php echo $i % 2; ?>">

                <?php if (isset($this->items[0]->state)) : ?>
                    <?php $class = ($canChange) ? 'active' : 'disabled'; ?>
                    <!-- <td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_recharge&task=recharge.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
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

							<?php echo JFactory::getUser($item->created_by)->name; ?>				</td>
				<td>

							<?php echo JFactory::getUser($item->modified_by)->name; ?>				</td> -->

                <td>
                    <!-- <?php if (isset($item->checked_out) && $item->checked_out) : ?>
  					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'recharges.', $canCheckin); ?>
  				<?php endif; ?> -->
                    <a href="<?php echo JRoute::_('index.php?option=com_recharge&view=recharge&id=' . (int)$item->id); ?>">
                        <?php echo $item->code; ?></a>
                </td>
                <td>
                    <b class="price"><?php echo number_format($item->amount, 0, ".", "."); ?> <?php echo BIZ_XU; ?></b>
                </td>

                <td>

                    <?php echo $item->status; ?>
                </td>
<!--                <td>-->
                <!---->
                <!--                    --><?php //echo $item->bank_name; ?>
                <!--                </td>-->
                <!-- <td> -->

                <?php
                // if (!empty($item->image)) :
                // 	$imageArr = (array) explode(',', $item->image);
                // 	foreach ($imageArr as $singleFile) :
                // 		if (!is_array($singleFile)) :
                // 			$uploadPath = 'images/banking' . DIRECTORY_SEPARATOR . $singleFile;
                // 			echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image">' . $singleFile . '</a> ';
                // 		endif;
                // 	endforeach;
                // else:
                // 	echo $item->image;
                // endif;
                ?>
                <!-- </td> -->
                <td>

                    <?php echo $item->created_time; ?>
                </td>

                <td>

                    <?php echo $item->type; ?>
                </td>
                <td>

                    <?php echo $this->escape($item->note); ?>
                </td>
                <!-- <td>

					<?php echo $item->updated_time; ?>
				</td> -->


                <?php if ($canEdit || $canDelete): ?>
                    <!-- <td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_recharge&task=rechargeform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_recharge&task=rechargeform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
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

            if (!confirm("<?php echo Text::_('COM_RECHARGE_DELETE_MESSAGE'); ?>")) {
                return false;
            }
        }
    </script>
<?php endif; ?>
<style>
    .price {
        font-weight: bold;
    }

    @media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {
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

        tr {
            border: 1px solid #eee;
            margin-bottom: 10px
        }

        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            border-top: 0 !important;
        }

        td:last-of-type {
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
        tbody td:nth-of-type(1):before {
            content: "ID:";
        }

        tbody td:nth-of-type(2):before {
            content: "Mã nạp <?php echo BIZ_XU; ?>:";
        }

        tbody td:nth-of-type(3):before {
            content: "Số <?php echo BIZ_XU; ?>:";
        }

        tbody td:nth-of-type(4):before {
            content: "Trạng thái:";
        }


        tbody td:nth-of-type(5):before {
            content: "Ngày tạo:";
        }

        tbody td:nth-of-type(6):before {
            content: "Loại:";
        }

        tbody td:nth-of-type(7):before {
            content: "Ghi chú:";
        }


    }

</style>
