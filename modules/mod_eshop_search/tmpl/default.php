<?php
/**
 * @version		1.3.3
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2011 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;
?>
<?php defined('_JEXEC') or die('Restricted access'); ?>
	<div class="eshop-search<?php echo $params->get( 'moduleclass_sfx' ) ?>">
        <div class="input-prepend">
            <span class="add-on"><i class="icon-search"></i></span>
            <input class="inputbox product_search" type="text" name="keyword" id="prependedInput" value="" placeholder="<?php echo JText::_('ESHOP_FIND_A_PRODUCT'); ;?>">
        </div>
		<ul id="eshop_result"></ul>
		<input type="hidden" name="live_site" id="live_site" value="<?php echo JURI::root(); ?>">
		<input type="hidden" name="image_width" id="image_width" value="<?php echo $params->get('image_width')?>">
		<input type="hidden" name="image_height" id="image_height" value="<?php echo $params->get('image_height')?>">
		<input type="hidden" name="category_ids" id="category_ids" value="<?php echo $params->get('category_ids') ? implode(',', $params->get('category_ids')) : ''; ?>">
		<input type="hidden" name="description_max_chars" id="description_max_chars" value="<?php echo $params->get('description_max_chars',50); ?>">
	</div>
<script type="text/javascript">
(function($){
	$(document).ready(function(){
		$('#eshop_result').hide();
		$('input.product_search').val('');
		$(window).click(function(){
			$('#eshop_result').hide();
		})
		function search() {
			var query_value = $('input.product_search').val();
			$('b#search-string').html(query_value);
			if(query_value !== ''){
				$('.product_search').addClass('eshop-loadding');
				$.ajax({
					type: "POST",
					url: $('#live_site').val() + "index.php?option=com_eshop&view=search&format=raw&layout=ajax<?php echo EshopHelper::getAttachedLangLink(); ?>",
					data: '&keyword=' + query_value + '&image_width=' + $('#image_width').val() + '&image_height=' + $('#image_height').val() + '&category_ids=' + $('#category_ids').val() + '&description_max_chars=' + $('#description_max_chars').val(),
					cache: false,
					success: function(html){
						$("ul#eshop_result").html(html);
						$('.product_search').removeClass('eshop-loadding');
					}
				});
			}return false;    
		}
		
		$("input.product_search").live("keyup", function(e) {
			//Set Timeout
			clearTimeout($.data(this, 'timer'));
			// Set Search String
			var search_string = $(this).val();
			// Do Search
			if (search_string == '') {
				$('.product_search').removeClass('eshop-loadding');
				$("ul#eshop_result").slideUp();
			}else{
				$("ul#eshop_result").slideDown('slow');
				$(this).data('timer', setTimeout(search, 100));
			};
		});
			
	});
})(jQuery);
</script>

<style>
#eshop_result
{
	 background-color:#ffffff;
	 width:<?php echo $params->get('width_result',270)?>px;
	 position:absolute;
	 z-index:9999;
}
</style>