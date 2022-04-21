<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('jquery.framework');
Jhtml::_('bootstrap.framework');

JHtml::script("media/com_joomprofile/js/joomprofile.js");

JHtml::script("components/com_joomprofile/extensions/profile/templates/js/search.js");
JHtml::stylesheet('media/com_joomprofile/css/joomprofile.css');
?>
<link href="media/com_joomprofile/css/font-awesome.css" rel="stylesheet">
<link href="components/com_joomprofile/extensions/profile/templates/css/search.css" rel="stylesheet">
<style>
    .jp-wrap input[type=text]{
        height: auto !important;
        margin-bottom: auto !important;
    }
    .jp-wrap select{
        width: 100% ;
    }
</style>
<?php echo $data->assets;?>
<div class="jp-wrap">
<div id="jp-search" class="jp-search clearfix" style="display: block;">
    <div class="f90pro-wrapper clearfix">

    <?php if(empty($data->searchFields)):?>
		<div class="row-fluid">
			<div class="muted text-center">
				<i class="fa fa-ban fa-5x"></i>
				<h1><?php echo JText::_('COM_JOOMPROFILE_SEARCH_NOT_ALLWOED');?></h1>
			</div>
		</div>
	<?php else:?>
		<script>
			(function($){
				$(document).ready(function(){
					joomprofile.search.update(0, '');
				});
			})(joomprofile.jQuery);
		</script>

        <?php if(isset($data->keyword_search) && $data->keyword_search) : ?>
            <div class="well">
                <div class="row form-select-search" id="jp-search-criteria-accord"></div>
                <div class="d-none search-name">
                    <div class="col-lg-4 ">
                        <form action="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=search.display');?>">
                            <div class="input-group">
                                <?php echo JHtml::_('form.token'); ?>
                                <input
                                        value="<?php echo $data->searchword; ?>"
                                        name="searchword"
                                        type="text"
                                        class="form-control"
                                        placeholder="<?php echo JText::_('COM_JOOMPROFILE_SEARCH_QUERY');?>"
                                        aria-label="Input group example"
                                        aria-describedby="btnGroupAddon">
                                <div class="input-group-prepend">
                                    <button class="btn input-group-text" type="submit" id="btnGroupAddon">
                                        <i class="fa fa-search"> </i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif;?>

        <div class="row-fluid" id="jp-search-form">
<!--            <div class="span2 col-lg-3 col-md-3 col-sm-3 col-xs-12 ">-->
<!--				-->
<!--            </div>-->
             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	             <div class="row-fluid  clearfix jps-sort-bar">
                     <div class="pull-left">
                         <div class="jp-search-filters span12">
                         </div>
                     </div>
					<div class="pull-right">
						<span class="muted"><?php echo JText::_('COM_JOOMPROFILE_SEARCH_SORT_BY');?></span> &nbsp;
						<select name="jps_sort_by" id="jps-sort-by" class="input-small">
						<?php $config = $this->app->getConfig();?>
							<option value="name" <?php echo (isset($config['search_orderby']) && $config['search_orderby'] == 'name') ?  'selected' : '';?>><?php echo JText::_('COM_JOOMPROFILE_SEARCH_SORT_BY_NAME');?></option>
							<option value="registerDate" <?php echo (isset($config['search_orderby']) && $config['search_orderby'] == 'registerDate') ?  'selected' : '';?>><?php echo JText::_('COM_JOOMPROFILE_SEARCH_SORT_BY_NAME_REGISTRATION_DATE');?></option>
						</select>&nbsp;
						<span class="muted"><?php echo JText::_('COM_JOOMPROFILE_SEARCH_SORT_IN');?></span> &nbsp;
						<select name="jps_sort_in" class="input-mini" id="jps-sort-in">
							<option value="asc" <?php echo (isset($config['search_ordering']) && $config['search_ordering'] == 'asc') ?  'selected' : '';?>><?php echo JText::_('COM_JOOMPROFILE_SEARCH_SORT_IN_ASC');?></option>
							<option value="desc" <?php echo (isset($config['search_ordering']) && $config['search_ordering'] == 'desc') ?  'selected' : '';?>><?php echo JText::_('COM_JOOMPROFILE_SEARCH_SORT_IN_DESC');?></option>
						</select>&nbsp;
						<!-- <span style="padding-right:15px;" class="muted"><?php //echo JText::_('COM_JOOMPROFILE_SEARCH_SORT_ORDER');?></span> -->
					</div>
				</div>
				<div class="row-fluid">
	                <div class="jp-search-userlist jps-users-list clearfix">
	                </div>
	                <div>
						<button class="btn input-block-level no-search jp-search-loadmore" type="button" data-page="2" style="display:none;">
							<?php echo JText::_('COM_JOOMPROFILE_LOAD_MORE');?>
						</button>
					</div>
				</div>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>
</div>
<?php
