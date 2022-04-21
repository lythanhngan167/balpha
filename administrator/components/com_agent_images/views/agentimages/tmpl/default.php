<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Agent_images
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
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'administrator/components/com_agent_images/assets/css/agent_images.css');
$document->addStyleSheet(Uri::root() . 'media/com_agent_images/css/list.css');

$user      = Factory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_agent_images');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_agent_images&task=agentimages.saveOrderAjax&tmpl=component';
    HTMLHelper::_('sortablelist.sortable', 'agentimageList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo Route::_('index.php?option=com_agent_images&view=agentimages'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

            <?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

			<div class="clearfix"></div>
			<table class="table table-striped" id="agentimageList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
                            <?php echo HTMLHelper::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>

									<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_CREATED_BY', 'a.`created_by`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE1', 'a.`image1`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE2', 'a.`image2`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE3', 'a.`image3`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE4', 'a.`image4`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE5', 'a.`image5`', $listDirn, $listOrder); ?>
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
					$canCreate  = $user->authorise('core.create', 'com_agent_images');
					$canEdit    = $user->authorise('core.edit', 'com_agent_images');
					$canCheckin = $user->authorise('core.manage', 'com_agent_images');
					$canChange  = $user->authorise('core.edit.state', 'com_agent_images');
					?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = Text::_('JORDERINGDISABLED');
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
							<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
								<?php echo JHtml::_('jgrid.published', $item->state, $i, 'agentimages.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

										<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'agentimages.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_agent_images&task=agentimage.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->id); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->id); ?>
				<?php endif; ?>

				</td>				<td>

					<?php echo $item->created_by; ?>
				</td>				<td>

					<?php
						if (!empty($item->image1)) :
							$image1Arr = explode(',', $item->image1);
							foreach ($image1Arr as $fileSingle) :
								if (!is_array($fileSingle)) :
									$uploadPath = 'images/landingpage' .DIRECTORY_SEPARATOR . $fileSingle;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image1">' . $fileSingle . '</a> | ';
								endif;
							endforeach;
						else:
							echo $item->image1;
						endif; ?>
				</td>				<td>

					<?php
						if (!empty($item->image2)) :
							$image2Arr = explode(',', $item->image2);
							foreach ($image2Arr as $fileSingle) :
								if (!is_array($fileSingle)) :
									$uploadPath = 'images/landingpage' .DIRECTORY_SEPARATOR . $fileSingle;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image2">' . $fileSingle . '</a> | ';
								endif;
							endforeach;
						else:
							echo $item->image2;
						endif; ?>
				</td>				<td>

					<?php
						if (!empty($item->image3)) :
							$image3Arr = explode(',', $item->image3);
							foreach ($image3Arr as $fileSingle) :
								if (!is_array($fileSingle)) :
									$uploadPath = 'images/landingpage' .DIRECTORY_SEPARATOR . $fileSingle;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image3">' . $fileSingle . '</a> | ';
								endif;
							endforeach;
						else:
							echo $item->image3;
						endif; ?>
				</td>				<td>

					<?php
						if (!empty($item->image4)) :
							$image4Arr = explode(',', $item->image4);
							foreach ($image4Arr as $fileSingle) :
								if (!is_array($fileSingle)) :
									$uploadPath = 'images/landingpage' .DIRECTORY_SEPARATOR . $fileSingle;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image4">' . $fileSingle . '</a> | ';
								endif;
							endforeach;
						else:
							echo $item->image4;
						endif; ?>
				</td>				<td>

					<?php
						if (!empty($item->image5)) :
							$image5Arr = explode(',', $item->image5);
							foreach ($image5Arr as $fileSingle) :
								if (!is_array($fileSingle)) :
									$uploadPath = 'images/landingpage' .DIRECTORY_SEPARATOR . $fileSingle;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image5">' . $fileSingle . '</a> | ';
								endif;
							endforeach;
						else:
							echo $item->image5;
						endif; ?>
				</td>

					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
			<?php echo HTMLHelper::_('form.token'); ?>
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