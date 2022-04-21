<?php
/**
 * @version		1.3.1
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
<div class="manufacturer-slide <?php echo $params->get('moduleclass_sfx' ); ?>">
	<div class="bx-wrapper">
		<div class="customNavigation bx-controls-direction">
		   <a class="btn prev bx-prev"><span class="text-hide">Previous</span></a>
		   <a class="btn next bx-next"><span class="text-hide">Next</span></a>
		</div>
		<div class="eshop_manufacturer row-fluid">
			<?php
			foreach ($items as $item)
			{ 
				$viewManufacturerUrl = JRoute::_(EshopRoute::getManufacturerRoute($item->id));
				?>
				<div class="slide">
					<a href="<?php echo $viewManufacturerUrl; ?>" title="<?php echo $item->manufacturer_name; ?>">
						<img src="<?php echo $item->image; ?>" alt="<?php echo $item->manufacturer_name; ?>" />
					</a>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	Eshop.jQuery(document).ready(function($){
		var owl = $(".eshop_manufacturer");
	      owl.owlCarousel({
	    	  pagination : false,
	    	  items : <?php echo $manufacturersShow; ?>,
		  });
	      // Custom Navigation Events
	      $(".next").click(function(){
	        owl.trigger('owl.next');
	      })
	      $(".prev").click(function(){
	        owl.trigger('owl.prev');
	      })
	});
</script>