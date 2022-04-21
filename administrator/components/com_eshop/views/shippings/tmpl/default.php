<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');

$ordering = ($this->lists['order'] == 'a.ordering');

if ($ordering)
{
	$saveOrderingUrl = 'index.php?option=com_eshop&task=shipping.save_order_ajax';
	JHtml::_('sortablelist.sortable', 'recordsList', 'adminForm', strtolower($this->lists['order_Dir']), $saveOrderingUrl);
}

$customOptions = array(
	'filtersHidden'       => true,
	'defaultLimit'        => JFactory::getApplication()->get('list_limit', 20),
	'searchFieldSelector' => '#filter_search',
	'orderFieldSelector'  => '#filter_full_ordering'
);
JHtml::_('searchtools.form', '#adminForm', $customOptions);
?>
<form action="index.php?option=com_eshop&view=shippings&type=0" method="post" name="adminForm" enctype="multipart/form-data" id="adminForm">
	<div id="j-main-container">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('ESHOP_FILTER_SEARCH_SHIPPINGS_DESC');?></label>
				<input type="text" name="search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->search); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('ESHOP_SEARCH_SHIPPINGS_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span class="icon-search"></span></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value='';this.form.submit();"><span class="icon-remove"></span></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<?php
					echo $this->lists['filter_state'];
					echo $this->pagination->getLimitBox();
				?>
			</div>
		</div>
		<div class="clearfix"></div>
		<table class="adminlist table table-striped" id="recordsList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $this->lists['order_Dir'], $this->lists['order'], null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
					</th>
					<th width="2%" class="center">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" class="text_center" style="min-width:55px">
						<?php echo JHtml::_('searchtools.sort', JText::_('JSTATUS'), 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('searchtools.sort',  JText::_('ESHOP_NAME'), 'a.name', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_TITLE'), 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_AUTHOR') , 'a.author', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_left" width="15%">
						<?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_AUTHOR_EMAIL'), 'a.email', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="10%">
						<?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_PUBLISHED') , 'a.published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="text_center" width="5%">
						<?php echo JHtml::_('searchtools.sort', JText::_('ESHOP_ID') , 'a.id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>		
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row = &$this->items[$i];
				$link 	= JRoute::_( 'index.php?option=com_eshop&task=shipping.edit&cid[]='. $row->id );
				$checked 	= JHtml::_('grid.id',   $i, $row->id );
				$published 	= JHtml::_('jgrid.published', $row->published, $i, 'shipping.');
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="order nowrap center hidden-phone">
						<?php
						$iconClass = '';

						if (!$ordering)
						{
							$iconClass = ' inactive tip-top hasTooltip';
						}
						?>
						<span class="sortable-handler<?php echo $iconClass ?>">
						<i class="icon-menu"></i>
						</span>
						<?php if ($ordering) : ?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $row->ordering ? $row->ordering : '0'; ?>" class="width-20 text-area-order "/>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $checked; ?>
					</td>
					<td class="text_center">
						<div class="btn-group">
							<?php
							echo $published;
							echo $this->addDropdownList(JText::_('ESHOP_DELETE'), 'trash', $i, 'shipping.remove');
							echo $this->renderDropdownList($this->escape($row->name));
							?>
						</div>
					</td>
					<td>
						<a href="<?php echo $link; ?>">
							<?php echo $row->name; ?>
						</a>
					</td>
					<td>
						<?php echo $row->title; ?>
					</td>												
					<td>
						<?php echo $row->author; ?>
					</td>
					<td>
						<?php echo $row->author_email;?>
					</td>
					<td class="text_center">
						<?php echo $published; ?>
					</td>
					<td class="text_center">
						<?php echo $row->id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
		<table class="adminform" style="margin-top: 20px;">
			<tr>
				<td>
					<fieldset class="adminform">
						<legend><?php echo JText::_('ESHOP_INSTALL_NEW_SHIPPING_PLUGIN'); ?></legend>
						<table>
							<tr>
								<td>
									<input type="file" name="plugin_package" id="plugin_package" size="57" class="input_box" />
									<input type="button" class="btn btn-primary" value="<?php echo JText::_('ESHOP_INSTALL'); ?>" onclick="installPlugin();" />
								</td>
							</tr>
						</table>					
					</fieldset>
				</td>
			</tr>		
		</table>
	</div>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" id="filter_full_ordering" name="filter_full_ordering" value="" />
	<?php echo JHtml::_( 'form.token' ); ?>				 
	<script type="text/javascript">
		function installPlugin() {
			var form = document.adminForm;
			if (form.plugin_package.value =="") {
				alert("<?php echo JText::_('ESHOP_CHOOSE_SHIPPING_PLUGIN'); ?>");
				return;	
			}
			
			form.task.value = 'shipping.install';
			form.submit();
		}
	</script>
</form>