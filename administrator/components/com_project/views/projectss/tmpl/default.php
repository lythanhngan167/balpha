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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_project/assets/css/project.css');
$document->addStyleSheet(JUri::root() . 'media/com_project/css/list.css');

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_project');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_project&task=projectss.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'projectsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo JRoute::_('index.php?option=com_project&view=projectss'); ?>" method="post"
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
            <table class="table table-striped" id="projectsList">
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
                        <th width="1%" class="nowrap center">
                            <?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
                        </th>
                    <?php endif; ?>

                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_ID', 'a.`id`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_TITLE', 'a.`title`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>

                       <?php echo JHtml::_('searchtools.sort', 'Loại', 'a.`is_recruitment`', $listDirn, $listOrder); ?>

                   </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'Khách hàng tồn', '`remain_customer`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'Giá bán', 'a.`price`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_SHORT_DESCRIPTION', 'a.`short_description`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_DESCRIPTION', 'a.`description`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_FILE_1', 'a.`file_1`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_FILE_2', 'a.`file_2`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_FILE_3', 'a.`file_3`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_FILE_4', 'a.`file_4`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort', 'COM_PROJECT_PROJECTSS_FILE_5', 'a.`file_5`', $listDirn, $listOrder); ?>
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
                    $ordering = ($listOrder == 'a.ordering');
                    $canCreate = $user->authorise('core.create', 'com_project');
                    $canEdit = $user->authorise('core.edit', 'com_project');
                    $canCheckin = $user->authorise('core.manage', 'com_project');
                    $canChange = $user->authorise('core.edit.state', 'com_project');
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">

                        <?php if (isset($this->items[0]->ordering)) : ?>
                            <td class="order nowrap center hidden-phone">
                                <?php if ($canChange) :
                                    $disableClassName = '';
                                    $disabledLabel = '';

                                    if (!$saveOrder) :
                                        $disabledLabel = JText::_('JORDERINGDISABLED');
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
                            <td class="center">
                                <?php echo JHtml::_('jgrid.published', $item->state, $i, 'projectss.', $canChange, 'cb'); ?>
                            </td>
                        <?php endif; ?>

                        <td>

                            <?php echo $item->id; ?>
                        </td>
                        <td>
                            <?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
                                <?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'projectss.', $canCheckin); ?>
                            <?php endif; ?>
                            <?php if ($canEdit) : ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_project&task=projects.edit&id=' . (int)$item->id); ?>">
                                    <?php echo $this->escape($item->title); ?></a>
                            <?php else : ?>
                                <?php echo $this->escape($item->title); ?>
                            <?php endif; ?>

                        </td>
                        <td>



                          <?php echo $item->is_recruitment == 1?'Tuyển dụng':'Khách hàng'; ?>

                        </td>
                        <td>
                            <span style="display: inline-block; min-width: 50px;" class="text-success"><?php echo number_format($item->remain_customer,0,",","."); ?></span>
                            <?php if( $item->remain_customer && $item->state == 1): ?>
                                <a class="btn btn-danger small" href="<?php echo JRoute::_('index.php?option=com_users&view=users&filter[search]=&project_id=' . (int)$item->id); ?>">
                                    Gán cho Sale
                                </a>
                            <?php endif;?>
                        </td>
                        <td>

                            <span class="price"><?php echo number_format($item->price,0,",","."); ?></span>
                        </td>
                        <td>

                            <?php echo $item->short_description; ?>
                        </td>
                        <td>

                            <?php echo $item->description; ?>
                        </td>
                        <td>

                            <?php
                            if (!empty($item->file_1)) :
                                $file_1Arr = explode(',', $item->file_1);
                                foreach ($file_1Arr as $fileSingle) :
                                    if (!is_array($fileSingle)) :
                                        $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle;
                                        echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_1">' . $fileSingle . '</a> | ';
                                    endif;
                                endforeach;
                            else:
                                echo $item->file_1;
                            endif; ?>
                        </td>
                        <td>

                            <?php
                            if (!empty($item->file_2)) :
                                $file_2Arr = explode(',', $item->file_2);
                                foreach ($file_2Arr as $fileSingle) :
                                    if (!is_array($fileSingle)) :
                                        $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle;
                                        echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_2">' . $fileSingle . '</a> | ';
                                    endif;
                                endforeach;
                            else:
                                echo $item->file_2;
                            endif; ?>
                        </td>
                        <td>

                            <?php
                            if (!empty($item->file_3)) :
                                $file_3Arr = explode(',', $item->file_3);
                                foreach ($file_3Arr as $fileSingle) :
                                    if (!is_array($fileSingle)) :
                                        $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle;
                                        echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_3">' . $fileSingle . '</a> | ';
                                    endif;
                                endforeach;
                            else:
                                echo $item->file_3;
                            endif; ?>
                        </td>
                        <td>

                            <?php
                            if (!empty($item->file_4)) :
                                $file_4Arr = explode(',', $item->file_4);
                                foreach ($file_4Arr as $fileSingle) :
                                    if (!is_array($fileSingle)) :
                                        $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle;
                                        echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_4">' . $fileSingle . '</a> | ';
                                    endif;
                                endforeach;
                            else:
                                echo $item->file_4;
                            endif; ?>
                        </td>
                        <td>

                            <?php
                            if (!empty($item->file_5)) :
                                $file_5Arr = explode(',', $item->file_5);
                                foreach ($file_5Arr as $fileSingle) :
                                    if (!is_array($fileSingle)) :
                                        $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $fileSingle;
                                        echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_5">' . $fileSingle . '</a> | ';
                                    endif;
                                endforeach;
                            else:
                                echo $item->file_5;
                            endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
<script>
    window.toggleField = function (id, task, field) {

        var f = document.adminForm, i = 0, cbx, cb = f[id];

        if (!cb) return false;

        while (true) {
            cbx = f['cb' + i];

            if (!cbx) break;

            cbx.checked = false;
            i++;
        }

        var inputField = document.createElement('input');

        inputField.type = 'hidden';
        inputField.name = 'field';
        inputField.value = field;
        f.appendChild(inputField);

        cb.checked = true;
        f.boxchecked.value = 1;
        window.submitform(task);

        return false;
    };
</script>


<style>

    #toolbar-trash,
    #toolbar-checkin,
    #toolbar-archive,
    #toolbar-copy {
        display: none !important;
    }

</style>
