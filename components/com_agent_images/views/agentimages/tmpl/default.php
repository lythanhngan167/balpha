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
use \Joomla\CMS\Language\Text;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_agent_images') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'agentimageform.xml');
$canEdit    = $user->authorise('core.edit', 'com_agent_images') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'agentimageform.xml');
$canCheckin = $user->authorise('core.manage', 'com_agent_images');
$canChange  = $user->authorise('core.edit.state', 'com_agent_images');
$canDelete  = $user->authorise('core.delete', 'com_agent_images');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_agent_images/css/list.css');
?>
<h3><?php
  $active = JFactory::getApplication()->getMenu()->getActive();
echo $active->title;
 ?></h3>
<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">


        <div class="table-responsive">
	<table class="table table-striped" id="agentimageList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th>
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE1', 'a.image1', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE2', 'a.image2', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE3', 'a.image3', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE4', 'a.image4', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_AGENT_IMAGES_AGENTIMAGES_IMAGE5', 'a.image5', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_AGENT_IMAGES_AGENTIMAGES_ACTIONS'); ?>
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
			<?php $canEdit = $user->authorise('core.edit', 'com_agent_images'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_agent_images')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_agent_images&task=agentimage.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td>
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>

							<?php echo JFactory::getUser($item->created_by)->name; ?>				</td>
				<td>

					<?php
						if (!empty($item->image1)) :
							$image1Arr = (array) explode(',', $item->image1);
							foreach ($image1Arr as $singleFile) :
								if (!is_array($singleFile)) :
									$uploadPath = 'images/landingpage' . DIRECTORY_SEPARATOR . $singleFile;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image1">' . $singleFile . '</a> ';
								endif;
							endforeach;
						else:
							echo $item->image1;
						endif; ?>				</td>
				<td>

					<?php
						if (!empty($item->image2)) :
							$image2Arr = (array) explode(',', $item->image2);
							foreach ($image2Arr as $singleFile) :
								if (!is_array($singleFile)) :
									$uploadPath = 'images/landingpage' . DIRECTORY_SEPARATOR . $singleFile;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image2">' . $singleFile . '</a> ';
								endif;
							endforeach;
						else:
							echo $item->image2;
						endif; ?>				</td>
				<td>

					<?php
						if (!empty($item->image3)) :
							$image3Arr = (array) explode(',', $item->image3);
							foreach ($image3Arr as $singleFile) :
								if (!is_array($singleFile)) :
									$uploadPath = 'images/landingpage' . DIRECTORY_SEPARATOR . $singleFile;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image3">' . $singleFile . '</a> ';
								endif;
							endforeach;
						else:
							echo $item->image3;
						endif; ?>				</td>
				<td>

					<?php
						if (!empty($item->image4)) :
							$image4Arr = (array) explode(',', $item->image4);
							foreach ($image4Arr as $singleFile) :
								if (!is_array($singleFile)) :
									$uploadPath = 'images/landingpage' . DIRECTORY_SEPARATOR . $singleFile;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image4">' . $singleFile . '</a> ';
								endif;
							endforeach;
						else:
							echo $item->image4;
						endif; ?>				</td>
				<td>

					<?php
						if (!empty($item->image5)) :
							$image5Arr = (array) explode(',', $item->image5);
							foreach ($image5Arr as $singleFile) :
								if (!is_array($singleFile)) :
									$uploadPath = 'images/landingpage' . DIRECTORY_SEPARATOR . $singleFile;
									echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the image5">' . $singleFile . '</a> ';
								endif;
							endforeach;
						else:
							echo $item->image5;
						endif; ?>				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_agent_images&task=agentimageform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_agent_images&task=agentimageform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_agent_images&task=agentimageform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_AGENT_IMAGES_ADD_ITEM'); ?></a>
	<?php endif; ?>

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

		if (!confirm("<?php echo Text::_('COM_AGENT_IMAGES_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
