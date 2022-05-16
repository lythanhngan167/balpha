<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

?>

<?php

$app  = JFactory::getApplication();
$user = JFactory::getUser();
$document = JFactory::getDocument();
// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;


// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$copyright= $app->input->getCmd('copyright', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
// Add Stylesheets
//JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));
$version = '210052022';

if($_SERVER['HTTP_HOST'] == 'localhost'){
	$version = time();
}

$document->addStyleSheet($this->baseurl."/templates/protostar/less/template.css?".$version, array('version'=>'auto'));
$document->addStyleSheet($this->baseurl."/templates/protostar/less/custom.css?".$version, array('version'=>'auto'));
$document->addStyleSheet($this->baseurl."/templates/protostar/less/custom-mobile.css?".$version, array('version'=>'auto'));

//if($option != 'com_eshop'){
JHtml::_('stylesheet', 'bootstrap.min.css', array('version' => 'auto', 'relative' => true));
//}
JHtml::_('stylesheet', 'font-awesome/css/font-awesome.min.css', array('version' => 'auto', 'relative' => true));

//landingpage
if($_REQUEST['Itemid'] == TECH_INSURACNE){
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_272.css');
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min.css');
	$document->addScript(JURI::base().'templates/protostar/js/ladipage.min.js','text/javascript', false, false);
	//$document->addScript(JURI::base().'templates/protostar/js/ladipagepage_272.js','text/javascript', false, false);
}

if($_REQUEST['Itemid'] == FOUNDER_STORY){
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_273.css');
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min.css');
	$document->addScript(JURI::base().'templates/protostar/js/ladipage.min.js','text/javascript', false, false);
	//$document->addScript(JURI::base().'templates/protostar/js/ladipagepage_272.js','text/javascript', false, false);
}

if($_REQUEST['Itemid'] == FOUR_ZERO_INSURACNE || $_REQUEST['Itemid'] == AGENT){
    $document->addStyleSheet(JURI::base() . '/templates/protostar/css/registrations_landingpage725.css?'.$version);
		$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_423.css?'.$version);
		//$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_725.css?'.$version);
		$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min-2.css?'.$version);
    //$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min.css?'.$version);
		$document->addScript(JURI::base().'templates/protostar/js/jquery.magnific-popup.min.js','text/javascript', false, false);
		//$document->addScript(JURI::base().'templates/protostar/js/ladipage.vi.min.js','text/javascript', false, false);
		$document->addScript(JURI::base().'templates/protostar/js/ladipage.min.js?'.$version,'text/javascript', false, false);
}

//shop
if($option == 'com_eshop'){
	$document->addStyleSheet($this->baseurl."/templates/protostar/css/smart_wizard.min.css?".$version, array('version'=>'auto'));
	$document->addStyleSheet($this->baseurl."/templates/protostar/css/smart_wizard_theme_dots.min.css?".$version, array('version'=>'auto'));
	$document->addStyleSheet($this->baseurl."/templates/protostar/less/shop1.css?".$version, array('version'=>'auto'));
	$document->addStyleSheet($this->baseurl."/templates/protostar/less/shop2.css?".$version, array('version'=>'auto'));
	JHtml::_('script', 'jquery.smartWizard.min.js', array('version' => $version, 'relative' => true));
}

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'main.js', array('version' => $version, 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Use of Google Font
if ($this->params->get('googleFont'))
{
	$font = $this->params->get('googleFontName');

	// Handle fonts with selected weights and styles, e.g. Source+Sans+Condensed:400,400i
	$fontStyle = str_replace('+', ' ', strstr($font, ':', true) ?: $font);

	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $font);
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . $fontStyle . "', sans-serif;
	}");
}

//Copyright
$copyright= $this->params->get('copyright');

// Template color
if ($this->params->get('templateColor'))
{
	$this->addStyleDeclaration('
	body.site {
		border-top: 3px solid ' . $this->params->get('templateColor') . ';
		background-color: ' . $this->params->get('templateBackgroundColor') . ';
	}
	a {
		color: ' . $this->params->get('templateColor') . ';
	}
	.nav-list > .active > a,
	.nav-list > .active > a:hover,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.nav-pills > .active > a,
	.nav-pills > .active > a:hover,
	.btn-primary {
		background: ' . $this->params->get('templateColor') . ';
	}');
}

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Adjusting content width
$position7ModuleCount = $this->countModules('position-7');
$position8ModuleCount = $this->countModules('position-8');
$positionBannerAds = $this->countModules('banner-ads');

if ($position7ModuleCount && $position8ModuleCount)
{
	$span = 'span6';
}
elseif ($position7ModuleCount && !$position8ModuleCount)
{
	$span = 'span9';
}
elseif (!$position7ModuleCount && $position8ModuleCount)
{
	$span = 'span9';
}
else
{
	$span = 'span12';
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . htmlspecialchars(JUri::root() . $this->params->get('logoFile'), ENT_QUOTES) . '?t=0319012022" alt="' . $sitename . '" />';
	if($_REQUEST['is_app'] == 1){
		$logo = '<img src="' . JUri::root().'/images/logo-mxh.png'.'?t=0319012022" alt="' . $sitename . '" />';
	}
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PBLV7QF');</script>
<!-- End Google Tag Manager -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<jdoc:include type="head" />
	<?php
	//homepage
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$is_home_page = 0;
	if ($menu->getActive() == $menu->getDefault()) {
		$is_home_page = 1;
	}
	$is_social = 0;
	if ($_REQUEST['option'] == 'com_community') {
		$is_social = 1;
	}
	//landingpage
	$is_landingpage = 0;
	switch ($_REQUEST['Itemid']) {
		case TECH_INSURACNE:
			$is_landingpage = 1;
			break;

		case FOUNDER_STORY:
			$is_landingpage = 1;
			break;

		case FOUR_ZERO_INSURACNE:
			$is_landingpage = 1;
			break;

		case AGENT:
			$is_landingpage = 1;
			break;
		case REFERRAL:
			$is_landingpage = 1;
			$position8ModuleCount = -1;
			break;
		default:
			$is_landingpage = 0;
			break;
	}
	?>

	<style>
	<?php if ($_REQUEST['Itemid'] == REFERRAL) { ?>
		#top_main_menu_mobile{display:none;}
		#mySidenav{display:none !important;}
		.desktop .body{margin-left: 0px;}
	<?php } ?>
	<?php
	//check Group User
	$groups = JAccess::getGroupsByUser($user->id, false);
	$group_id = $groups[0];

			if ($group_id == 2) { ?>
					#mySidenav2 .supporter-loged{
						display: none;
					}
			<?php }
			if ($group_id == 3) { ?>
					#mySidenav2 .register-loged{
						display: none;
					}
			<?php }
			if ($group_id == 4) { ?>
				#mySidenav2 .author-loged{
					display: block;
				}
				#mySidenav2 .supporter-loged{
					display: none!important;
				}

				#mySidenav2 .register-loged{
					display: none;
				}

		<?php }

	?>
	<?php
		if ($group_id == 3 &&
		($_REQUEST['Itemid'] == ACCOUNT_PROFILE_PAGE
		|| $_REQUEST['Itemid'] == ACCOUNT_PROJECT_PAGE)) { ?>
			.content-profile-noti{
				display: block;
			}
	<?php	}
	 ?>
	<?php
	//hide menu login
	if($user->id > 0){ ?>
		li.item-130{
			display:none;
		}
		li.item-335{
			display:block;
			padding-right: 8px;
    	padding-top: 10px;
			min-width: 100px;
    	text-align: center;
		}
		.logo-search-account .right-logo-search-account .mod-list li.item-335 a:before {
    	content: "\f08b";
		}
	<?php	}else{ ?>
		li.item-130{
			display:block;
		}
		li.item-335{
			display:none;
		}
		.logo-search-account .right-logo-search-account .mod-list li.item-335 a:before {
    	content: "\f08b";
		}
		<?php if($_REQUEST['option'] == 'com_k2' && $_REQUEST['view'] == 'item' && $_REQUEST['layout'] == 'item'): ?>
			#system-message-container{
				display: none;
			}
		<?php endif; ?>
	<?php } ?>

	<?php if(!$is_home_page){ ?>
		.footer-module {
		  padding-left: 103px!important;
		}
		div.k2filter-table {
			margin-top: 0px;
		}
		div.k2filter-table {
			width:auto;
			border-radius:none;
			box-shadow:none;
			height:auto;
		}
		.k2filter-cell1 input{
			height:auto!important;
		}
		.k2filter-cell1{
			padding:0px!important;
		}
	<?php } ?>

	<?php if($position8ModuleCount){ ?>
		<?php if(($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')): ?>
		.main-content .left-main-content {
		  flex: 0 0 100%!important;
		}
		<?php endif; ?>

		<?php if((!($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')) && ($position7ModuleCount > 0)): ?>
		.main-content .left-main-content {
		  flex: 0 0 74%!important;
		}
		<?php endif; ?>

		.autobuy-data{
		  flex: 0 0 33.2%!important;
		}

		@media (max-width: 768px) {
			.main-content .left-main-content {
			  flex: 0 0 100%!important;
			}
			.autobuy-data{
			  flex: 0 0 100%!important;
				text-align: center!important;
			}
		}

	<?php } ?>
	<?php if($option == 'com_community'){ ?>

	.footer {
    position: inherit!important;
    z-index: 0;
	}

	<?php } ?>

	<?php if($option != 'com_community'){ ?>
		#joms-chatbar{
			display: none!important;
		}
	<?php } ?>


	<?php
		if($_REQUEST['is_app'] == 1){
		 ?>
		.main-menu{
			display: none!important;
		}
		.right-logo-search-account{
			display: none;
		}
		.center-logo-search-account{
			display: none;
		}
		.left-logo-search-account{
			padding-bottom: 5px;
    	padding-top: 0px;
		}
		.social-bca-vietnam{
			display: none;
		}
		.remind-link{
			display: none;
		}
		.reset-link{
			display: none;
		}
		.col-footer2{
			display: none;
		}
		.footer-module{
			display: none;
		}
		@media (max-width: 768px) {
			.remind-link{
				display: none!important;
			}
			.reset-link{
				display: none!important;
			}
			.body .container {
    		padding: 0px 0px 0px 0px;
			}
			.jomsocial-wrapper .jomsocial {
    		padding: 10px 0px 0px 0px;
			}
			.joms-landing__cover:before {
		    padding-top: 165px;
			}
			.joms-landing__content{
				background: none;
			}
			.jomsocial-wrapper .jomsocial {
    		padding: 0px 0px 0px 0px;
			}
			.left-main-content #content{
				padding-left: 0px;
    		padding-right: 0px;
			}
		}

	<?php } ?>

	<?php if($_REQUEST['Itemid'] == AGENT){ ?>
		.desktop .container {
		    max-width: 100%!important;
		}
		.body#top {
    	padding-top: 0px;
		}
	<?php } ?>


	<?php
		// hoidap FAQ
		if($_REQUEST['Itemid'] == 387 || $_REQUEST['Itemid'] == 382
		|| $_REQUEST['Itemid'] == 386 || $_REQUEST['Itemid'] == 383
		|| $_REQUEST['Itemid'] == 385 || $_REQUEST['Itemid'] == 384
		|| $_REQUEST['Itemid'] == 347){ ?>
			.main-content .container .row{
				display:block!important;
			}
			.right-main-content{
				display:block!important;
				float:left;
				margin-top: 80px;
			}
			.left-main-content{
				display:block!important;
				float:right;
			}
			.itemListCategory h2{
				margin-left:-35%;
			}
			#itemListPrimary .item .groupPrimary .catItemHeader{
				width: 100%;
			}

	<?php } ?>


	<?php
		// error 404
		if($_REQUEST['Itemid'] == 259){ ?>
			.itemRatingBlock{
				display:none;
			}
			.author-bottom{
				display:none;
			}
	<?php } ?>




	</style>

	<?php if($is_home_page == 1){
		$config = JFactory::getConfig();
		$og_title = $config->get('sitename');
		$og_meta_desc = $config->get('MetaDesc');
	?>
	<meta property="og:url" content="<?php echo JURI::base(); ?>" />
	<meta property="og:title" content="<?php echo $og_title; ?>" />
	<meta property="og:image" content="<?php echo JURI::base(); ?>images/bca-logo-blue2.png" />
	<meta property="og:description" content="<?php echo $og_meta_desc; ?>" />
	<meta property="og:type" content="home" />
	<meta property="og:image:width" content="600" />
	<meta property="og:image:height" content="400" />
	<?php } ?>
	<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
	<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "e9060a2d-50ee-4195-927d-313fdff94973",
    });
		OneSignal.getUserId(function(userId) {
    	console.log("OneSignal User ID:", userId);
  	});
  });
</script>
</head>
<body class="<?php if($is_home_page){ ?>homepage<?php } ?> site  <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '')
	. ($this->direction === 'rtl' ? ' rtl' : '');
?>">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBLV7QF"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<div id="mySidenav" class="sidenav">
<a href="javascript:void(0)" class="closebtn">×</a>

<div class="nav-collapse-mobile">
	<div class="welcome-user">
	<jdoc:include type="modules" name="mobile-user" style="xhtml" />
	</div>

	<jdoc:include type="modules" name="position-1" style="none" />
	<jdoc:include type="modules" name="bottom-1" style="xhtml" />
	<jdoc:include type="modules" name="bottom-2" style="xhtml" />
	<jdoc:include type="modules" name="bottom-3" style="xhtml" />
	<jdoc:include type="modules" name="mobile-menu" style="xhtml" />
</div>
</div>


<?php if ($is_landingpage == 1) : ?>
<div class="container-fluid landingpage">
	<section id="sp-banner-body" class="banner-agents-top">
		<jdoc:include type="modules" name="landingpage" style="none" />
	</section>
</div>
<?php endif; ?>

<?php if ($is_landingpage == 0) : ?>
<!-- <div class="container-fluid banner-top">
</div> -->

<?php if ($this->countModules('position-1')) : ?>
	<div class="container-fluid main-menu">
		<div class="container">
			<div class="row">
				<div class="main-menu-wapper">
				<div class="col-xs-12 col-md-12 left-main-menu">
						<nav class="navigation" role="navigation">
							<div class="navbar pull-right">
								<a class="btn btn-navbar collapsed" id="toggle-menu" data-toggle="collapse" data-target=".nav-collapse">
									<span class="element-invisible"><?php echo JTEXT::_('TPL_PROTOSTAR_TOGGLE_MENU'); ?></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</a>
								<input type="hidden" id="toggle-menu-hidden" value="0" />
							</div>

							<div class="col-xs-12 col-md-2 left-logo-search-account">
								<a class="brand pull-left logo-desktop" href="<?php echo $this->baseurl; ?>/">
									<?php //echo $logo; ?>
									<img width="155" height="60" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo-vertical.svg" alt="<?php echo $sitename ?>" />
									<?php if ($this->params->get('sitedescription')) : ?>
										<?php //echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</div>'; ?>
									<?php endif; ?>
								</a>

							</div>
							<div class="nav-collapse">
								<jdoc:include type="modules" name="position-1" style="none" />
							</div>
						</nav>
				</div>
				<div id="mySidenav2" class="sidenav2">
					<div>
						<a href="#idcontact"><button type="button" class="btn btn-primary button-sidenav2" name="button" class="btn-menu-right-mobile">Liên hệ tư vấn</button></a>
						<!-- index.php?Itemid=196 <button type="button" name="button" class="btn-menu-right-mobile btn-primary" <a href="#">Gửi yêu cầu tư vấn</a> </button> -->

						<a href="javascript:void(0)" class="closebtn">×</a>
						<?php
						if($user->id > 0){?>
							<jdoc:include type="modules" name="mobile-right-loged" style="xhtml" />
						<?php }
						else {?>
							<jdoc:include type="modules" name="mobile-right-visiter" style="xhtml" />
						<?php } ?>

						<jdoc:include type="modules" name="mobile-menu" style="xhtml" />
					</div>
				</div>
			</div>
		</div>
	</div>

	</div>
	<?php endif; ?>

	<div class="container-fluid logo-search-account">
		<div class="container">
			<div class="row">

				<!-- <div class="col-xs-12 col-md-6 center-logo-search-account">
					<jdoc:include type="modules" name="position-157" style="xhtml" />
				</div> -->
				<div class="col-xs-12 col-md-4 right-logo-search-account">
					<jdoc:include type="modules" name="position-16" style="xhtml" />
				</div>
		</div>
	</div>
	</div>

	<!-- <div class="container-fluid address-phone-email">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-2 left-address-phone-email">
					<div class="navbars">
						<div id="insurance-category-text"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo JText::_('CATEGORY-INSURANCE'); ?></div>
						<div id="insurance-category-hidden">
							<div class="insurance-category-hidden-wrapper" style="display:none;">
								<?php if(!$is_home_page){ ?>
									<jdoc:include type="modules" name="category" style="xhtml" />
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-8 center-address-phone-email">
					<jdoc:include type="modules" name="position-14" style="xhtml" />
				</div>
				<div class="col-xs-12 col-md-2 btn-request right-address-phone-email">
					<a href="index.php?Itemid=196"><button type="submit" class="btn btn-primary">
						<?php echo JText::_('SEND_REQUEST'); ?> <i class="fa fa-headphones" aria-hidden="true">&nbsp;</i>

					</button></a>
				</div>
		</div>
	</div>
	</div> -->

	<?php endif; ?>

	<?php if($_REQUEST['Itemid'] == AGENT){ ?>
	<div id="footer-landingpage" class="footer-landingpage">
		<jdoc:include type="modules" name="landingpage-footer" />
	</div>
	<?php } ?>

	<?php if(!$is_home_page){ ?>
	<div class="body-wapper">
	<div class="body" id="top">
		<?php if(!$is_home_page && $_REQUEST['Itemid'] != AGENT){ ?>
			<div class="container-fluid background-container">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 background-top-image">
						</div>
				</div>
			</div>
			</div>
		<?php } ?>
		<?php if(!$is_home_page && $_REQUEST['Itemid'] != AGENT){ ?>
			<div class="container-fluid loop-line-container">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 loop-line">
						</div>

				</div>
			</div>
			</div>
		<?php } ?>

		<?php if(!$is_home_page && $_REQUEST['Itemid'] != AGENT){ ?>
			<div class="container-fluid slide-categories">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 category category-no-homepage">
							<jdoc:include type="modules" name="position-15" style="xhtml" />
						</div>

				</div>
			</div>
			</div>
		<?php } ?>


		<?php if(!$is_home_page){ ?>
			<!-- <div class="container-fluid loop-line2-container">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 loop-line2">
						</div>

				</div>
			</div>
			</div> -->
		<?php } ?>
		<?php if(!$is_home_page){ ?>
			<div class="container-fluid breadcrumbs">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-md-12 left-breadcrumbs">
								<jdoc:include type="modules" name="position-3" style="xhtml" />
						</div>
				</div>
			</div>
			</div>
		<?php } ?>
		<?php if($_REQUEST['Itemid'] != REFERRAL){ ?>
		<div class="container-fluid main-content">
			<div class="container">
				<div class="row">
		<?php } ?>
					<?php
					if ($position8ModuleCount) :
						if(!($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')): // profile consultinger
					?>
					<?php
						if ($group_id == 3 || $group_id == 4) { ?>
							<div class="col-xs-12 left1-main-content col-md-3">
							<div id="aside" class="<?php //echo span3; ?>">
								<jdoc:include type="modules" name="position-8" style="well" />
							</div>
							</div>
					<?php	}
					 ?>

						<?php endif; ?>
					<?php endif; ?>
					<div class="col-xs-12 left-main-content
					<?php if ($position7ModuleCount || $position8ModuleCount) : ?>
						<?php
							if((!($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')) && ($position7ModuleCount > 0 || $position8ModuleCount > 0)): // profile consultinger
						?> col-lg-9 col-md-12
						<?php else: ?>
						col-lg-12 col-md-12
					<?php endif; ?>
					<?php else: ?>col-md-12<?php endif; ?>">
						<!-- <?php if (!$position7ModuleCount) : ?>col-md-12<?php endif; ?> -->
						<main id="content" role="main" class=" <?php //echo $span; ?>">
							<!-- Begin Content -->

							<jdoc:include type="message" />

							<?php if($_REQUEST['option'] == 'com_community' || $is_home_page == 1){ ?>
							<div style="color:red;">
								Phiên bản thử nghiệm MXH BCA này sẽ bị xoá và thay thế bởi 1 phiên bản tốt hơn. <br>
								Và trang này sẽ bị đóng lại vào hết ngày 12/4/2021. <br>
								Anh chị Đại lý / TVV vui lòng lưu trử lại nhưng hình ảnh, thông tin cần thiết để cập nhật lên MXH BCA mới. <br>
								Anh chị có thể tự tạo tài khoản theo username yêu thích tại MXH BCA mới tại địa chỉ:<br>
								 <a style="color:red; font-weight:bold;" href="http://mxh.bcavietnam.com">http://mxh.bcavietnam.com</a>
								<br>Xin cảm ơn!

							</div>
							<a href="index.php?Itemid=455"><h3 class="social-bca-vietnam"><i class="fa fa-comments" aria-hidden="true"></i> Cộng đồng BCA</h3></a>
							<?php } ?>
							<?php if ($positionBannerAds): ?>
							<div class="banner-ads">
								<jdoc:include type="modules" name="banner-ads" style="xhtml" />
							</div>
							<?php endif; ?>
							<?php if(($option == 'com_k2' && $view == 'item')){ // for search k2 ?>

							<?php }else{  ?>
								<jdoc:include type="modules" name="search-k2" style="xhtml" />
							<?php } ?>

							<?php if($option == 'com_eshop' && $view == 'frontpage'): ?>
							<jdoc:include type="modules" name="banner-eshop" style="xhtml" />
							<?php endif; ?>

							<jdoc:include type="component" />

							<?php if($option == 'com_eshop' && $view == 'frontpage'): ?>
							<jdoc:include type="modules" name="category-eshop" style="xhtml" />
							<?php endif; ?>
							<div class="clearfix"></div>
							<jdoc:include type="modules" name="position-2" style="none" />
							<div class="content-com_users">
								<?php
									if ( $_REQUEST['Itemid'] == ACCOUNT_PROJECT_PAGE) { //$_REQUEST['Itemid'] == ACCOUNT_PROFILE_PAGE ||
										 ?>
										<jdoc:include type="modules" name="bottom-4" style="xhtml" />
								<?php	}
								 ?>
							</div>

							<!-- End Content -->
						</main>
					</div>

						<?php if ($position7ModuleCount) : ?>
							<div class="col-xs-12 right-main-content col-md-3">
							<div id="aside" class="<?php //echo span3; ?>">

								<jdoc:include type="modules" name="position-7" style="well" />
							</div>
							</div>
						<?php endif; ?>

		<?php if($_REQUEST['Itemid'] != REFERRAL){ ?>
			</div>
		</div>
		</div>
		<?php } ?>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid slide-categories">
		<div class="container-fluid">
			<div class="row">
				<?php if($is_home_page == 1){ ?>
				<!-- <div class="social-mobile"><a href="index.php?Itemid=455"><h3 class="social-bca-vietnam"><i class="fa fa-comments" aria-hidden="true"></i> Cộng đồng BCA</h3></a></div> -->
				<?php } ?>

				<div class="col-xs-12 col-md-12 col-lg-12 banner-1">
					<jdoc:include type="modules" name="banner-1" style="xhtml" />
				</div>


		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid loop-line-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 loop-line">
				</div>

		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid loop-line2-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 loop-line2">
				</div>

		</div>
	</div>
	</div>
<?php } ?>


<?php if($is_home_page){ ?>
	<div class="container-fluid slide-categories">
		<div class="container">
			<div class="row">


				<div class="col-xs-12 category">
					<jdoc:include type="modules" name="position-15" style="xhtml" />
					<jdoc:include type="modules" name="category" style="xhtml" />
				</div>


		</div>
	</div>
	</div>
<?php } ?>
<?php if($is_home_page){ ?>
	<!-- <div class="container-fluid loan">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-loan">
					<jdoc:include type="modules" name="position-9" style="xhtml" />
				</div>

		</div>
	</div>
	</div> -->
<?php } ?>
<?php if($is_home_page){ ?>
	<div class="container-fluid comparation">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-comparation left-comparation1">

					<div class="box-service">
						<div class="image"></div>
						<div class="info">
							<a href="#" class="item-center"><span class="special-item-center">BẢO HIỂM</span> <span class="special-item-center">CÔNG NGHỆ</span> <span class="special-item-center">4.0</span></a>
							<div class="item item-special-1">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100.004" viewBox="0 0 100 100.004">
  <g id="ic-05" transform="translate(-1016.518 -609.787)">
    <path id="Path_262" data-name="Path 262" d="M1066.64,618.243a41.425,41.425,0,1,0,29.486,12.18A41.425,41.425,0,0,0,1066.64,618.243Zm-7.469,57.8h0a5.662,5.662,0,0,1-1.5-.255,7.1,7.1,0,0,1-1.226-.936l-9.377-9.37a3.506,3.506,0,0,1-1.136-2.912,3.74,3.74,0,0,1,1.136-2.839,3.8,3.8,0,0,1,3.021-1.092,3.747,3.747,0,0,1,2.843,1.092l6.236,6.447,21.262-21.175a3.888,3.888,0,0,1,2.912-1.143,4.022,4.022,0,0,1,2.836,1.143,3.679,3.679,0,0,1,1.139,2.912,3.272,3.272,0,0,1-1.139,2.752l-24.025,24.193a9.684,9.684,0,0,0-1.318.936,6.014,6.014,0,0,1-1.7.255Zm7.469,33.744h0a50,50,0,1,1,35.23-14.641A50,50,0,0,1,1066.64,709.791Z" transform="translate(0)" fill="#fff" fill-rule="evenodd"/>
  </g>
</svg>


									</div></a>
									<div class="box-service-item-info">
										<h3 class="">GIẢI PHÁP DATACENTER</h3>
										<p>Tìm kiếm Data nóng, Khách hàng có nhu cầu, Data không trùng lặp, được remarketing.</p>
									</div>
								</div>
							</div> <div class="item item-special-2">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="66" height="139.152" viewBox="0 0 66 139.152">
  <g id="ic-06" transform="translate(-1744.988 -545.892)">
    <path id="Path_366" data-name="Path 366" d="M1764,666.78V583.873a11.125,11.125,0,0,1,3.354-8.215,14.624,14.624,0,0,1,10.589-4.13,12.509,12.509,0,0,1,13.481,11.462c.026.295.041.589.041.883v71.44a3.942,3.942,0,0,1-3.735,4.145,2.9,2.9,0,0,1-.36.005,3.491,3.491,0,0,1-3.644-3.323,3.77,3.77,0,0,1,.061-.827v-71.44a4.284,4.284,0,0,0-2.618-4.059,8.2,8.2,0,0,0-8.367,1.182,3.886,3.886,0,0,0-1.223,2.872l.066,82.825c.34,1.8,2.618,10.655,16.743,10.655a16.339,16.339,0,0,0,11.959-3.963,10,10,0,0,0,3-6.6l.081-85.565c-.081-1.193-.786-27.7-25.876-27.7a22.833,22.833,0,0,0-16.648,6.414,33.11,33.11,0,0,0-8.25,21.112v85.524a3.624,3.624,0,0,1-1.055,2.613,3.917,3.917,0,0,1-2.766,1.192,3.648,3.648,0,0,1-2.709-1.192,3.752,3.752,0,0,1-1.141-2.613l.091-85.575a40.912,40.912,0,0,1,10.655-26.658,29.431,29.431,0,0,1,21.817-8.428c25.877,0,33.2,23.046,33.436,35.126V666.9a18.518,18.518,0,0,1-5.337,12.06A23.854,23.854,0,0,1,1788.368,685c-17.9,0-23.847-11.609-24.4-18.225Z" transform="translate(0.001 0)" fill="#fff" fill-rule="evenodd"/>
  </g>
</svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="">QUY TRÌNH GIAO KẾT HỢP ĐỒNG ĐẶC BIỆT</h3>
										<p>Minh bạch, tinh gọn; với sự tham gia của bên thứ ba - đại diện về mặt pháp lý; quy trình được ghi âm, ghi hình.</p>
									</div>
								</div>
							</div>
							<div class="item item-special-3">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="80" height="100.8" viewBox="0 0 80 100.8">
  <g id="ic-07" transform="translate(-488.869 -865.026)">
    <path id="Path_115" data-name="Path 115" d="M502.028,865.026H555.7v37.313h13.167v63.487h-80V902.339h13.159Zm7.993,37.313H547.6V872.245H510.021Z" transform="translate(0 0)" fill="#fff" fill-rule="evenodd"/>
  </g>
</svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="">SIÊU THỊ BẢO HIỂM ĐA DẠNG CÁC SẢN PHẨM</h3>
										<p>Hơn 200 sản phẩm bảo hiểm Nhân thọ và Phi nhân thọ; đối tác là các doanh nghiệp bảo hiểm uy tín hàng đầu Việt Nam; trao quyền chủ động lựa chọn sản phẩm cho Khách hàng.</p>
									</div>
								</div>
							</div>
							<div class="item item-special-4">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="100" height="93.097" viewBox="0 0 100 93.097">
  <g id="ic-01" transform="translate(-250 -1472)">
    <path id="Path_381" data-name="Path 381" d="M539.741,472.393h.01c-2.494-.316-2.494-7.269-2.494-7.269a34.763,34.763,0,0,0,8.877-16.787c4.224,0,6.912-10.261,2.687-14.028.153-3.614,5.457-29.757-21.449-29.757-26.642,0-21.46,26.143-21.256,29.757-4.357,3.777-1.67,14.028,2.494,14.028a35.514,35.514,0,0,0,8.877,16.787s0,6.953-2.454,7.269c-8,1.222-34.49,10.618-34.49,25.257h100C580.543,483.021,547.8,473.625,539.741,472.393Z" transform="translate(-230.543 1067.447)" fill="#fff"/>
  </g>
</svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="">ĐỘI NGŨ TƯ VẤN TÀI CHÍNH</h3>
										<p>Kiến thức chuyên môn bài bản; khách quan, trung thực, tận tâm; có kinh nghiệm kỹ năng vững chắc.</p>
									</div>
								</div>
							</div>
							<div class="item item-special-5">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="99.058" viewBox="0 0 100 99.058">
  <defs>
    <clipPath id="clip-path">
      <rect id="Rectangle_22" data-name="Rectangle 22" width="100" height="99.058" fill="#fff"/>
    </clipPath>
  </defs>
  <g id="ic-11" clip-path="url(#clip-path)">
    <path id="Path_26" data-name="Path 26" d="M67.373,95.123H37.4A13.4,13.4,0,0,0,24,108.52H80.771a13.4,13.4,0,0,0-13.4-13.4" transform="translate(-2.388 -9.462)" fill="#fff"/>
    <rect id="Rectangle_21" data-name="Rectangle 21" width="17.458" height="10.15" transform="translate(41.271 72.669)" fill="#fff"/>
    <path id="Path_27" data-name="Path 27" d="M97.47,0H2.53A2.537,2.537,0,0,0,0,2.531v64.36a2.537,2.537,0,0,0,2.53,2.53H97.47a2.537,2.537,0,0,0,2.53-2.53V2.531A2.537,2.537,0,0,0,97.47,0M90.286,57.148A2.564,2.564,0,0,1,87.7,59.679H12.3a2.564,2.564,0,0,1-2.583-2.531V12.274A2.564,2.564,0,0,1,12.3,9.744H87.7a2.564,2.564,0,0,1,2.583,2.53Z" transform="translate(0 0)" fill="#fff"/>
  </g>
</svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="balpha-academy">HỌC VIỆN BẢO HIỂM SỐ B-Alpha ACADEMY</h3>
										<p>Tiên phong đào tạo nhân lực bảo hiểm; chuyển giao nền tảng công nghệ; trao kiến thức, niềm tin cho mọi người dân Việt Nam.</p>
									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
					<!-- <jdoc:include type="modules" name="position-13" style="xhtml" /> -->


		</div>
	</div>
	</div>
<?php } ?>
<?php if($is_home_page){ ?>
	<div class="container-fluid partner">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-partner">
					<jdoc:include type="modules" name="position-12" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
<?php } ?>



<?php if($is_home_page){ ?>
	<div class="container-fluid customers">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-customers">
					<jdoc:include type="modules" name="position-18" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid comparation">
		<div class="container">
				<div class="row">
					<div class="col-xs-12 col-md-12 left-comparation">
						<jdoc:include type="modules" name="position-11" style="xhtml" />
					</div>
				</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-comparation">
					<div class="box-service-2">
						<div class="col-image1">
							<div class="content-dk content-image">
								<a class="btn-dk" href="https://insurance.bcavietnam.com/ctv-bao-hiem-online">Đăng ký ngay</a>
								<p>Hotline:<a class="hotline-balpha" href="tel:02866708870">02866708870</a></p>
							</div>

						</div>
						<div class="col-image11">
							<div class="corner-image">
								<img width="38" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/corner.png" alt="" />
							</div>
						</div>
						<div class="bg-info1"></div>
						<div class="col-info">
							<h2 class="">
							<img width="220" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo-text-balpha.png?t=123" alt="<?php echo $sitename ?>" />
							</h2>
							<h3 class="">Nền tảng phân phối bảo hiểm online</h3>
							<ul>
								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="99.058" viewBox="0 0 100 99.058">
  <defs>
    <clipPath id="clip-path">
      <rect id="Rectangle_22" data-name="Rectangle 22" width="100" height="99.058" fill="#fff"/>
    </clipPath>
  </defs>
  <g id="ic-11" clip-path="url(#clip-path)">
    <path id="Path_26" data-name="Path 26" d="M67.373,95.123H37.4A13.4,13.4,0,0,0,24,108.52H80.771a13.4,13.4,0,0,0-13.4-13.4" transform="translate(-2.388 -9.462)" fill="#fff"/>
    <rect id="Rectangle_21" data-name="Rectangle 21" width="17.458" height="10.15" transform="translate(41.271 72.669)" fill="#fff"/>
    <path id="Path_27" data-name="Path 27" d="M97.47,0H2.53A2.537,2.537,0,0,0,0,2.531v64.36a2.537,2.537,0,0,0,2.53,2.53H97.47a2.537,2.537,0,0,0,2.53-2.53V2.531A2.537,2.537,0,0,0,97.47,0M90.286,57.148A2.564,2.564,0,0,1,87.7,59.679H12.3a2.564,2.564,0,0,1-2.583-2.531V12.274A2.564,2.564,0,0,1,12.3,9.744H87.7a2.564,2.564,0,0,1,2.583,2.53Z" transform="translate(0 0)" fill="#fff"/>
  </g>
</svg>

								</span>Kinh doanh bảo hiểm online mọi lúc mọi nơi</li>

								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" width="100" height="129.465" viewBox="0 0 100 129.465">
  <g id="ic-04" transform="translate(-162.156 -591.67)">
    <path id="Path_157" data-name="Path 157" d="M234.637,646.178v-1l-.838-.361-1.219.361-.833,1.345v1h1Zm-24.647,4.149-.351.647H208.41v.722h.356v.341l.682-.2.537-.341.13-.527.692-.156.191-.5h-1Zm-3.277,1.174-.191.682,1.069-.15v-.682l-.527-.376Zm50.052,10.974.161-.191c0,.191.186.5.186.7ZM199.834,650.97l.351-.5.677.5-.5.376Zm1.365,1.214v.873h-1.505l-.341-.722v-.642h.156Zm-6.157-1.556h1.505l-1.857,2.765-.828-.532.156-1Zm3.618,1.706V653.4l-.682.5h-1.41l.191-.828.692-.2.191-.346Zm-.341-2.007v-1.059l.833.853Zm.677.306v.868l-.677.532-.873.15v-1.556Zm-1.214-1.174v.868h-2.745l-1-.4.336-.472,1.214-.527h1.862v.527Zm-6.147-3.432,1-.5,1.224.2-.537,1.505-1.034.326Zm10.442,58.081.156.191-.156,3.6,2.052,3.6,2.579,2.213,2.584.206.346-.908-1.9-1.711v-.863l.331-1.029.161-.838-1.355-.186-.7-.838,1.029-1,.2-.677-1.224-.341.191-.873,1.706-.2.346-.146h-4.631Zm-13.046-63.224s.818-.176,1-.176v1.029l-2.193.17-.376-.5Zm-1.565-2.068v-.15h.873l.186-.371h1.505v.718l-.5.683h-2.052Zm58.462,5.324v-2.047a13.672,13.672,0,0,1,2.238,2.393l-.888,1.365h-3.061l-.2-.662Zm7.025,8.585.331-.376c.341.723.687,1.4,1.039,2.233l-.5-.151-.858.151Zm-78.668-.873A32.8,32.8,0,0,1,176,648.782v.878Zm-1.711,1.9,1.556-1.9h0Zm-1.555,1.9v3.9l1.867.893v3.593l1.746,2.94,1.505.346.186-1.24-1.716-2.574-.331-2.544h1l.346,2.735,2.549,3.623-.682,1,1.556,2.383,3.959,1v-.677l1.505.151-.161,1.214,1.189.146,1.9.531,2.715,3.091,3.467.166.166,2.92-2.248,1.505-.156,2.619-.341,1.365,3.417,4.275.2,1.505,1.365.191c.151,0,2.74,2.042,2.74,2.042v7.527l1,.341-.191.683c.341,0,.687.186,1.039.186.713,0,1.194.19,1.9.19a7.716,7.716,0,0,0,1.892.156c.5,0,1.174.191,1.711.191h3.076a3.9,3.9,0,0,1,1.36-.191h.682l1.555-2.047-.677-2.75.863-1.535,2.559.17,1.711-1.365.5-5.309,1.9-2.574.316-1.555-1.716-.5-1.159-1.9h-3.964l-3.071-1.169-.191-2.258-1.034-1.892h-2.735l-1.711-2.539L203,673.941v.838l-2.74.186-.863-1.4-2.735-.5-2.243,2.574-3.608-.677-.351-3.939-2.579-.5,2.75-3.733-.351-1.235-3.262,2.409-2.238-.341-.682-1.706.351-1.726,1.2-2.047,2.745-1.365,5.329-.186v1.681l1.892.863-.186-2.725,1.35-1.37,2.745-1.907.186-1.219,2.75-2.875,2.92-1.556-.346-.151,2.057-1.892.677.191.331.5.7-.853.151-.156h-.687l-.833-.346v-.883l.341-.341H208.8l.527.186.346.853.5-.176,1.385-.191.336-.677.677.186v.853l-.677.346v.864l2.579.682v.156l.5-.156v-1L213.1,648.6l-.191-.5,1.741-.527v-1.505l-1.741-1V642.3l-2.388,1.214h-.833l.146-2.062-3.071-.687-1.365,1.029v2.936l-2.248.848-1,1.9-1.029.135v-2.544l-2.248-.186-1-.878-.5-1.505,3.9-2.409,1.9-.532.331,1.385,1.034-.176v-.677l1.214-.19v-.151l-.5-.2v-.682l1.365-.15.873-.868.2-.346,3.071-.341,1.174,1-3.427,1.9,4.3,1.043.692-1.385h1.862l.677-1.4-1.335-.306v-1.555l-4.32-1.892-2.69.341-1.746.873.186,2.042-1.706-.15-.351-1.214,1.706-1.555-2.915-.156-.843.341-.527,1,1.179.2-.141,1-1.9.146-.341.682-2.74.156s0-1.505-.151-1.505,2.042-.186,2.042-.186l1.716-1.505-.868-.341-1.184,1.029h-2.057l-1.2-1.711h-2.4L190.7,636.1H193.1l.346.677-.682.537,2.745.146.341.838-2.93-.155-.151-.683-1.892-.341-1.034-.527H187.6a43.351,43.351,0,0,1,26.062-8.53,44.178,44.178,0,0,1,29.509,11.119l-.532.878-2.052.848-.868,1,.191,1,1,.2.682,1.57,1.706-.722.341,2.087h-.5l-1.555-.19-1.555.341-1.706,2.233-2.213.356-.356,1.867.858.341-.151,1.214-2.444-.346-2.032.346-.346,1.214.346,2.389,1.224.7h2.047l1.36-.211.5-1.169,2.082-2.765,1.505.376,1.355-1.365.191,1,3.613,2.429-.5.5-1.505-.146.682.833.838.341,1.224-.5v-1.369l.5-.191-.5-.5-2.248-1.219-.693-1.886h1.907l.677.718,1.711,1.365v1.706l1.706,1.857.677-2.389,1.224-.677.156,2.042,1.219,1.234h2.388c.351,1.154.677,2.388,1.174,3.6l-.291.156H252.6l-2.067-1.706-2.228.341v1.365h-.888l-.672-.5-3.919-1v-2.594l-4.968.361-1.561.848h-2.052l-1-.186-2.414,1.385v2.574L226,664.739l.341,1.4h1l-.331,1.505-.692.2v3.754l4.3,4.978h1.892v-.356h3.422l.888-.873h1.847l1.034,1,2.92.381-.356,3.578,3.091,5.645-1.716,3.1.2,1.551,1.179,1.179v3.6l1.711,2.253v2.936h1.556a44.318,44.318,0,0,1-74.89-9.288l-1.234-1c-.672-.873-1.36-1.555-2.032-2.574a18.9,18.9,0,0,1-1.872-2.413,18.446,18.446,0,0,1-1.731-2.73l-.356-.677a48.385,48.385,0,0,0,90.691,12.7,53.957,53.957,0,0,0,4.11-10.648c.151-1,.341-1.9.5-2.92a43.583,43.583,0,0,0,.682-8.395v-.5a40.613,40.613,0,0,0-.151-4.28,49.526,49.526,0,0,0-3.262-13.232,1.129,1.129,0,0,0-.351-.833,47.5,47.5,0,0,0-9.067-14.05c-.156-.381-.5-.537-.692-.878-.677-.677-1.556-1.505-2.228-2.233a49.282,49.282,0,0,0-32.741-12.484,47.57,47.57,0,0,0-32.736,12.831,38.658,38.658,0,0,0-5.62,5.981,54.4,54.4,0,0,0-7.768,14.747l-.135,2.785v2.725a19.372,19.372,0,0,1,.135,2.75l.542,2.915c0,.346.191.868.191,1.169l.5,1.56c.151.677.341,1.209.532,1.887v-.868a45.174,45.174,0,0,1,3.417-17.472Zm19.946-44.056a.582.582,0,0,0,.677.13s-.2.341-.532.868c0,0-1.044,1.54-.692,2.072h0c.356.5,2.248.146,2.248.146a3.429,3.429,0,0,1,1.034-.146c-.206,0-.351.341-.351.677l1.029,3.071,2.378-2.047.191-2.579v-.361a6.568,6.568,0,0,0,.717-.472l8.395-5.173,4.441,12.871c.2.341.346.341.688.156l2.393-2.052a1.717,1.717,0,0,0,.341-1L211.531,604.2l8.229-5.64a6.99,6.99,0,0,0,2.544-3.608,1.686,1.686,0,0,0-.331-1.038v-.191a5.105,5.105,0,0,0-.838-.667,6.612,6.612,0,0,0-4.285,1l-8.41,5.173-11.661-7.371c-.191-.191-.677-.191-1-.191l-2.745,1.385a.6.6,0,0,0-.216.818l.025.035,10.141,9.077-8.244,5.66c0,.156-.5.326-.5.326h-.5l-2.4-.848-2.93,1.4h.186Zm-3.091,1.836a5.947,5.947,0,0,0-1.064.873,25.629,25.629,0,0,0-3.417,2.594c-.677.5-1.505,1.375-2.393,2.047a33.477,33.477,0,0,0-2.584,2.544c-.828,1-1.857,2.087-2.88,3.126-.873,1.169-1.756,2.388-2.765,3.6a48.111,48.111,0,0,0-2.74,4.265c-.5.683-.843,1.551-1.38,2.233a26.052,26.052,0,0,1-1.224,2.389,52.331,52.331,0,0,0-2.188,5.329,18.283,18.283,0,0,0-.883,2.75,15.838,15.838,0,0,0-.833,2.9c-.446,1.987-.793,4-1.039,6.021a17.106,17.106,0,0,0-.326,3.071v4.641l.141,1.716a19.708,19.708,0,0,0,.351,3.071l.677,3.106a5.955,5.955,0,0,0,.341,1.505l.346,1.556c.341,1,.687,2.042,1.034,3.071l1.375,2.885a29.845,29.845,0,0,0,1.545,2.8c.537.813,1,1.706,1.706,2.71a26.006,26.006,0,0,0,1.912,2.409,20.531,20.531,0,0,0,2.042,2.233,37.9,37.9,0,0,0,4.446,4.109,40.587,40.587,0,0,0,9.759,5.63,43.17,43.17,0,0,0,9.609,2.935c1.555.166,3.076.547,4.441.683a8.836,8.836,0,0,1,2.087.171h5.474a4.92,4.92,0,0,1,1.555-.171,4.231,4.231,0,0,0,1.375-.136c1,0,1.711-.19,2.388-.19a7.666,7.666,0,0,1,1.9-.356c.828-.191,1.36-.326,1.36-.326a6.732,6.732,0,0,1-1.55.135,11.114,11.114,0,0,1-1.711.356,10.57,10.57,0,0,0-2.388.2h-6.523c-.5,0-1.189-.2-1.872-.2s-1.4-.166-2.087-.166c-1.365-.191-2.74-.5-4.255-.853a43.588,43.588,0,0,1-9.273-3.256A38.079,38.079,0,0,1,179.1,691.6c-1.36-1.209-2.74-2.574-4.1-3.939-.532-.838-1.224-1.506-1.706-2.2a18.334,18.334,0,0,1-1.711-2.574,36.376,36.376,0,0,1-4.827-10.8l-.331-1.555c-.161-.346-.161-.838-.356-1.365l-.5-2.94a17,17,0,0,0-.191-3.071v-2.91c0-.893.191-1.857.191-2.905a17.081,17.081,0,0,0,.346-2.765c.351-1.856.838-3.753,1.375-5.64a11.558,11.558,0,0,1,.868-2.6,14.978,14.978,0,0,1,.833-2.534,32.868,32.868,0,0,1,2.258-4.651,24.776,24.776,0,0,0,1.164-2.228c.537-.647.883-1.365,1.365-2.047a32.092,32.092,0,0,1,2.78-3.753,38.906,38.906,0,0,1,2.74-3.282c.823-1,1.887-1.856,2.725-2.75a19.707,19.707,0,0,1,2.4-2.213c.868-.677,1.55-1.2,2.233-1.721,1.375-1.029,2.589-1.867,3.221-2.394.893-.5,1.42-.833,1.42-.833Zm47.97,32.274v.622l-1,.2-.191,1.556h1.395l1.716-.191.828-1-1.029-.361-.5-.682-.647-1.174-.351-1.766-1.555.2-.341.7v.677l.692.527Z" transform="translate(0 0)" fill="#fff" fill-rule="evenodd"/>
  </g>
</svg>

								</span>B-Alpha - Nền tảng phân phối bảo hiểm online</li>

								<li><span>
									<svg id="ic-12" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="91.59" viewBox="0 0 100 91.59">
  <defs>
    <clipPath id="clip-path">
      <rect id="Rectangle_24" data-name="Rectangle 24" width="99.999" height="91.59" fill="#fff"/>
    </clipPath>
  </defs>
  <path id="Path_30" data-name="Path 30" d="M57.225,13.358l7.253,3.574L0,54.654l3.356,5.735L68.239,22.43l-.845,8.311L100,0Z" transform="translate(0 0)" fill="#fff"/>
  <g id="Group_46" data-name="Group 46" transform="translate(0.001)">
    <g id="ic3-3" clip-path="url(#clip-path)">
      <path id="Path_31" data-name="Path 31" d="M26.756,78.362H3.045a2.311,2.311,0,0,0-2.3,2.3v6.719a2.31,2.31,0,0,0,2.3,2.3H26.756a2.311,2.311,0,0,0,2.3-2.3V80.667a2.312,2.312,0,0,0-2.3-2.3" transform="translate(-0.124 -13.115)" fill="#fff"/>
      <path id="Path_32" data-name="Path 32" d="M26.756,96.4H3.045a2.311,2.311,0,0,0-2.3,2.3v6.719a2.31,2.31,0,0,0,2.3,2.3H26.756a2.311,2.311,0,0,0,2.3-2.3V98.7a2.311,2.311,0,0,0-2.3-2.3" transform="translate(-0.124 -16.133)" fill="#fff"/>
      <path id="Path_33" data-name="Path 33" d="M66.721,78.362H43.01a2.312,2.312,0,0,0-2.3,2.3v6.719a2.311,2.311,0,0,0,2.3,2.3H66.721a2.311,2.311,0,0,0,2.3-2.3V80.667a2.312,2.312,0,0,0-2.3-2.3" transform="translate(-6.813 -13.115)" fill="#fff"/>
      <path id="Path_34" data-name="Path 34" d="M66.721,96.4H43.01a2.311,2.311,0,0,0-2.3,2.3v6.719a2.311,2.311,0,0,0,2.3,2.3H66.721a2.311,2.311,0,0,0,2.3-2.3V98.7a2.311,2.311,0,0,0-2.3-2.3" transform="translate(-6.813 -16.133)" fill="#fff"/>
      <path id="Path_35" data-name="Path 35" d="M66.721,60.329H43.01a2.312,2.312,0,0,0-2.3,2.3v6.719a2.312,2.312,0,0,0,2.3,2.3H66.721a2.312,2.312,0,0,0,2.3-2.3V62.634a2.312,2.312,0,0,0-2.3-2.3" transform="translate(-6.813 -10.097)" fill="#fff"/>
      <path id="Path_36" data-name="Path 36" d="M110.935,78.362H87.225a2.311,2.311,0,0,0-2.3,2.3v6.719a2.311,2.311,0,0,0,2.3,2.3h23.711a2.31,2.31,0,0,0,2.3-2.3V80.667a2.311,2.311,0,0,0-2.3-2.3" transform="translate(-14.213 -13.115)" fill="#fff"/>
      <path id="Path_37" data-name="Path 37" d="M110.935,96.4H87.225a2.311,2.311,0,0,0-2.3,2.3v6.719a2.311,2.311,0,0,0,2.3,2.3h23.711a2.31,2.31,0,0,0,2.3-2.3V98.7a2.311,2.311,0,0,0-2.3-2.3" transform="translate(-14.213 -16.133)" fill="#fff"/>
      <path id="Path_38" data-name="Path 38" d="M110.935,60.329H87.225a2.311,2.311,0,0,0-2.3,2.3v6.719a2.311,2.311,0,0,0,2.3,2.3h23.711a2.311,2.311,0,0,0,2.3-2.3V62.634a2.311,2.311,0,0,0-2.3-2.3" transform="translate(-14.213 -10.097)" fill="#fff"/>
      <path id="Path_39" data-name="Path 39" d="M110.935,42.3H87.225a2.311,2.311,0,0,0-2.3,2.3v6.718a2.311,2.311,0,0,0,2.3,2.306h23.711a2.311,2.311,0,0,0,2.3-2.306V44.6a2.311,2.311,0,0,0-2.3-2.3" transform="translate(-14.213 -7.079)" fill="#fff"/>
    </g>
  </g>
</svg>

								</span>Nguồn khách hàng không giới hạn với DataCenter</li>

								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" width="100" height="117.388" viewBox="0 0 100 117.388">
	  <g id="ic-08" transform="translate(-360.574 -546.022)">
	    <path id="Path_167" data-name="Path 167" d="M410.54,553.934a61.682,61.682,0,0,1,19.072,3.082,53.875,53.875,0,0,1,16.114,8.794,41.644,41.644,0,0,1,11.043,13.45,35.08,35.08,0,0,1,3.805,15.823v1.739c0,.269-.1.633-.1.833v.76l-.255,2-.1,1.593-1.4-.765-1.7-.965a18.924,18.924,0,0,0-2.276-.983,9.787,9.787,0,0,0-2.276-.765,15.839,15.839,0,0,0-2.526-.455,12.706,12.706,0,0,0-2.458-.15,19.242,19.242,0,0,0-3.951.41,17.173,17.173,0,0,0-3.814,1.079,30.331,30.331,0,0,0-3.578,2.012,17.525,17.525,0,0,0-3.086,2.563l-.983,1.138-.869.969-.774-.969-.806-1.243a27.7,27.7,0,0,0-3.869-4.1,22.367,22.367,0,0,0-4.552-3.082,34.911,34.911,0,0,0-5.194-1.857,24.688,24.688,0,0,0-10.888,0,34.546,34.546,0,0,0-5.189,1.857,24.631,24.631,0,0,0-4.552,3.082,27.194,27.194,0,0,0-3.86,4.1l-.81,1.243-.774.969-.883-.969-.974-1.138a18.624,18.624,0,0,0-3.073-2.563,30.98,30.98,0,0,0-3.605-2.012,16.583,16.583,0,0,0-3.8-1.079,19.177,19.177,0,0,0-3.951-.41,13.282,13.282,0,0,0-2.476.15,22.515,22.515,0,0,0-2.513.455,10.144,10.144,0,0,0-2.312.765,16.693,16.693,0,0,0-2.208.983l-1.743.965-1.366.765-.109-1.593-.25-2v-.76c0-.2-.1-.455-.1-.833v-1.739a35.434,35.434,0,0,1,3.787-15.823,42.51,42.51,0,0,1,11.006-13.45,54.555,54.555,0,0,1,16.173-8.794,61.786,61.786,0,0,1,19.063-3.082ZM394.363,654.407h13.619V595.178a21.9,21.9,0,0,1,2.563-.1,25.2,25.2,0,0,1,2.622.1V654.4h13.56v9.008H394.363ZM410.79,546.022h-.505a2.273,2.273,0,0,0-2.3,2.244v4.679h5.18v-4.552a2.307,2.307,0,0,0-2.249-2.372Z" transform="translate(0 0)" fill="#fff" fill-rule="evenodd"/>
	  </g>
	</svg>


								</span>Không áp lực doanh số, doanh số được tích luỹ theo thời gian</li>

								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" width="100" height="93.097" viewBox="0 0 100 93.097">
  <g id="ic-01" transform="translate(-250 -1472)">
    <path id="Path_381" data-name="Path 381" d="M539.741,472.393h.01c-2.494-.316-2.494-7.269-2.494-7.269a34.763,34.763,0,0,0,8.877-16.787c4.224,0,6.912-10.261,2.687-14.028.153-3.614,5.457-29.757-21.449-29.757-26.642,0-21.46,26.143-21.256,29.757-4.357,3.777-1.67,14.028,2.494,14.028a35.514,35.514,0,0,0,8.877,16.787s0,6.953-2.454,7.269c-8,1.222-34.49,10.618-34.49,25.257h100C580.543,483.021,547.8,473.625,539.741,472.393Z" transform="translate(-230.543 1067.447)" fill="#fff"/>
  </g>
</svg>

								</span>Hơn 1000 chuyên gia sẵn sàng tư vấn hỗ trợ cho bạn</li>

							</ul>
							<div class="content-dk content-dk-mobile">
								<a class="btn-dk" href="https://insurance.bcavietnam.com/ctv-bao-hiem-online">Đăng ký ngay</a>
								<p class="holine-mobile">Hotline:<a class="hotline-balpha" href="tel:02866708870">02866708870</a></p>



							</div>
						</div>
					</div>
				</div>

		</div>
	</div>
	</div>
<?php } ?>


<?php if($is_home_page){ ?>
	<div class="container-fluid knowledge">
		<div class="container">
			<div class="row">
				<div class="knowledge-description">
					<jdoc:include type="modules" name="position-17" style="xhtml" />
				</div>
				<div class="col-xs-12 knowledge-wapper">
					<jdoc:include type="modules" name="position-10" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid request-consulting">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 left-consulting">
					<jdoc:include type="modules" name="position-4" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
	<div class="container-fluid request-consulting">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 right-consulting">
					<jdoc:include type="modules" name="position-5" style="xhtml" />
				</div>
		</div>
	</div>
	</div>
<?php } ?>
<?php if ($is_landingpage == 0) : ?>
	<div class="container-fluid footer-module">
		<div class="container">
			<div class="row">

				<div class="col-xs-12 right-footer-module">
					<jdoc:include type="modules" name="newsletter" style="xhtml" />
				</div>
		</div>
	</div>
	</div>
	<div class="container-fluid footer-module footer2">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-4 col-footer2">
					<!-- <div class="col-xs-3 col-md-3 left-footer-module">
						<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo-footer.png?t=123" alt="<?php echo $sitename ?>" />
					</div> -->
					<jdoc:include type="modules" name="bottom-1" style="xhtml" />
				</div>
				<div class="col-xs-12 col-md-4 col-footer2">
					<jdoc:include type="modules" name="bottom-2" style="xhtml" />
				</div>
				<!-- <div class="col-xs-12 col-md-2 col-footer2">
					<jdoc:include type="modules" name="bottom-3" style="xhtml" />
				</div>
				<div class="col-xs-12 col-md-3 col-footer2">
					<jdoc:include type="modules" name="bottom-4" style="xhtml" />
				</div> -->
				<div class="col-xs-12 col-md-4 col-footer2 end">
					<jdoc:include type="modules" name="bottom-5" style="xhtml" />
				</div>
		</div>
	</div>
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="container-fluid">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">

			<jdoc:include type="modules" name="footer" style="xhtml" />
			<p class="pull-right">
				<!-- <a href="#top" id="back-top">
					<?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?>
				</a> -->
				<a href="#" class="sp-scroll-up" aria-label="Scroll Up"><span class="fa fa-angle-up" aria-hidden="true"></span></a>
			</p>

			<p class="copyright">
				&copy; <?php //echo date('Y'); ?> <?php echo $copyright; ?>
			</p>
			<div class="table-relative-left" id="table-relative-left">
				<a href="#" class="sp-scroll-up-table" aria-label="Scroll Up" style="display: none;"><i class="fa fa-list-ul" aria-hidden="true"></i></a>
			</div>
		</div>
		</div>
	</footer>

	<?php endif; ?>
	<jdoc:include type="modules" name="debug" style="none" />
	<div id="overlay"></div>



	<?php
	//landingpage
	if($_REQUEST['Itemid'] == FOUNDER_STORY){ ?>
		<script id="script_viewport" type="text/javascript">
			window.ladi_viewport = function() {
				var width = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
				var widthDevice = width;
				var is_desktop = width >= 768;
				var content = "";
				if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
					window.ladi_is_desktop = is_desktop;
				}
				if (!is_desktop) {
					widthDevice = 420;
				} else {
					widthDevice = 960;
				}
				content = "width=" + widthDevice + ", user-scalable=no";
				var scale = 1;
				if (!is_desktop && widthDevice != window.screen.width && window.screen.width > 0) {
					scale = window.screen.width / widthDevice;
				}
				if (scale != 1) {
					content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
				}
				var docViewport = document.getElementById("viewport");
				if (!docViewport) {
					docViewport = document.createElement("meta");
					docViewport.setAttribute("id", "viewport");
					docViewport.setAttribute("name", "viewport");
					document.head.appendChild(docViewport);
				}
				docViewport.setAttribute("content", content);
			};
			window.ladi_viewport();
		</script>

		<script id="script_event_data" type="text/javascript">
			(function() {
				var run = function() {
					if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
						setTimeout(run, 100);
						return;
					}
					window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
					window.LadiPageScript.runtime.ladipage_id = '5ee0bc9a4005525abf796aaf';
					window.LadiPageScript.runtime.isMobileOnly = false;
					window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["xuhuongkinhdoanh.xyz"];
					window.LadiPageScript.runtime.DOMAIN_FREE = ["pagedemo.me", "demopage.me", "pro5.me", "procv.to", "ladi.me"];
					window.LadiPageScript.runtime.bodyFontSize = 12;
					window.LadiPageScript.runtime.time_zone = 7;
					window.LadiPageScript.runtime.eventData =
						"%7B%22BUTTON32%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22BUTTON118%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22GROUP123%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22BUTTON151%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22VIDEO163%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FeV8h9ROf1Ec%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO169%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F8IPFhDMY2f4%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO175%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FW8XoQSGalkw%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22BUTTON177%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22FORM226%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Atrue%2C%22option.form_auto_complete%22%3Atrue%7D%2C%22FORM_ITEM229%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22FORM_ITEM230%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM231%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22BUTTON287%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION290%22%7D%7D%2C%22GROUP302%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22FORM_ITEM327%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A4%7D%7D";
					window.LadiPageScript.run(true);
					window.LadiPageScript.runEventScroll();
				};
				run();
			})();
		</script>

	<?php } ?>


	<?php if($_REQUEST['Itemid'] == TECH_INSURACNE){ ?>
		<script id="script_viewport" type="text/javascript">
			window.ladi_viewport = function() {
				var width = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
				var widthDevice = width;
				var is_desktop = width >= 768;
				var content = "";
				if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
					window.ladi_is_desktop = is_desktop;
				}
				if (!is_desktop) {
					widthDevice = 420;
				} else {
					widthDevice = 960;
				}
				content = "width=" + widthDevice + ", user-scalable=no";
				var scale = 1;
				if (!is_desktop && widthDevice != window.screen.width && window.screen.width > 0) {
					scale = window.screen.width / widthDevice;
				}
				if (scale != 1) {
					content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
				}
				var docViewport = document.getElementById("viewport");
				if (!docViewport) {
					docViewport = document.createElement("meta");
					docViewport.setAttribute("id", "viewport");
					docViewport.setAttribute("name", "viewport");
					document.head.appendChild(docViewport);
				}
				docViewport.setAttribute("content", content);
			};
			window.ladi_viewport();
		</script>

		<script id="script_lazyload" type="text/javascript">
			(function() {
				var list_element_lazyload = document.querySelectorAll(
					'.ladi-section-background, .ladi-image-background, .ladi-button-background, .ladi-headline, .ladi-video-background, .ladi-countdown-background, .ladi-box, .ladi-frame, .ladi-form-item-background, .ladi-gallery-view-item, .ladi-gallery-control-item, .ladi-spin-lucky-screen, .ladi-spin-lucky-start, .ladi-list-paragraph ul li'
					);
				var style_lazyload = document.getElementById('style_lazyload');
				for (var i = 0; i < list_element_lazyload.length; i++) {
					var rect = list_element_lazyload[i].getBoundingClientRect();
					if (rect.x == "undefined" || rect.x == undefined || rect.y == "undefined" || rect.y == undefined) {
						rect.x = rect.left;
						rect.y = rect.top;
					}
					var offset_top = rect.y + window.scrollY;
					if (offset_top >= window.scrollY + window.innerHeight || window.scrollY >= offset_top + list_element_lazyload[i].offsetHeight) {
						list_element_lazyload[i].classList.add('ladi-lazyload');
					}
				}
				style_lazyload.parentElement.removeChild(style_lazyload);
				var currentScrollY = window.scrollY;
				var stopLazyload = function(event) {
					if (event.type == "scroll" && window.scrollY == currentScrollY) {
						currentScrollY = -1;
						return;
					}
					window.removeEventListener('scroll', stopLazyload);
					list_element_lazyload = document.getElementsByClassName('ladi-lazyload');
					while (list_element_lazyload.length > 0) {
						list_element_lazyload[0].classList.remove('ladi-lazyload');
					}
				};
				window.addEventListener('scroll', stopLazyload);
			})();
		</script>
		<script id="script_event_data" type="text/javascript">
			(function() {
				var run = function() {
					if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
						setTimeout(run, 100);
						return;
					}
					window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
					window.LadiPageScript.runtime.ladipage_id = '5eb11e84c310210e1b17f103';
					window.LadiPageScript.runtime.isMobileOnly = false;
					window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["bcavietnam.com"];
					window.LadiPageScript.runtime.DOMAIN_FREE = ["pagedemo.me", "demopage.me", "ladi.me", "pro5.me", "procv.to"];
					window.LadiPageScript.runtime.bodyFontSize = 12;
					window.LadiPageScript.runtime.time_zone = 7;
					window.LadiPageScript.runtime.eventData =
						"%7B%22BUTTON199%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP203%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22FRAME204%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME209%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME214%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME219%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON228%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP266%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP272%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP278%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP284%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON297%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP299%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME341%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME347%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME353%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME359%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP479%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP484%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FORM_ITEM466%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22FORM_ITEM465%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM464%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22BUTTON462%22%3A%7B%22type%22%3A%22button%22%2C%22desktop.style.animation-name%22%3A%22shake%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22shake%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FORM461%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Atrue%2C%22option.form_auto_complete%22%3Atrue%7D%2C%22FORM_ITEM662%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A4%7D%2C%22GROUP683%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22VIDEO685%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FeV8h9ROf1Ec%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO686%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F8IPFhDMY2f4%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO688%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FW8XoQSGalkw%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22FORM696%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Atrue%2C%22option.form_auto_complete%22%3Atrue%7D%2C%22FORM_ITEM699%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22FORM_ITEM700%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM701%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22FORM_ITEM724%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A4%7D%2C%22VIDEO726%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FqDEQrpiQFtI%22%2C%22option.video_type%22%3A%22youtube%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22BUTTON727%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22BOX190%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BOX191%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP730%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%7D";
					window.LadiPageScript.run(true);
					window.LadiPageScript.runEventScroll();
				};
				run();
			})();
		</script>

	<?php } ?>

	<?php
	if ($_REQUEST['test'] == 1) {
		print_r($_REQUEST);
	}
	if ($_REQUEST['Itemid'] == AGENT || $_REQUEST['Itemid'] == FOUR_ZERO_INSURACNE) { ?>

		<style>
		<?php
		if ($_REQUEST['Itemid'] == AGENT) {  ?>
			#SECTION625{
				display: none;
			}
			#footer-landingpage #SECTION625{
				display: block!important;
			}
		<?php } ?>
		</style>

	  <script type="text/javascript">
	    window.ladi_viewport = function() {
	      var screen_width = window.ladi_screen_width || window.screen.width;
	      var width = window.outerWidth > 0 ? window.outerWidth : screen_width;
	      var widthDevice = width;
	      var is_desktop = width >= 768;
	      var content = "";
	      if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
	        window.ladi_is_desktop = is_desktop;
	      }
	      if (!is_desktop) {
	        widthDevice = 420;
	      } else {
	        widthDevice = 960;
	      }
	      content = "width=" + widthDevice + ", user-scalable=no";
	      var scale = 1;
	      if (!is_desktop && widthDevice != screen_width && screen_width > 0) {
	        scale = screen_width / widthDevice;
	      }
	      if (scale != 1) {
	        content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
	      }
	      var docViewport = document.getElementById("viewport");
	      if (!docViewport) {
	        docViewport = document.createElement("meta");
	        docViewport.setAttribute("id", "viewport");
	        docViewport.setAttribute("name", "viewport");
	        document.head.appendChild(docViewport);
	      }
	      docViewport.setAttribute("content", content);
	    };
	    window.ladi_viewport();
	    window.ladi_fbq_data = [];
	    window.ladi_fbq = function(track_name, conversion_name, data, event_data) {
	      window.ladi_fbq_data.push([track_name, conversion_name, data, event_data]);
	    };
	  </script>

		<!-- <script id="script_viewport" type="text/javascript">
			window.ladi_viewport = function() {
				var width = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
				var widthDevice = width;
				var is_desktop = width >= 768;
				var content = "";
				if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
					window.ladi_is_desktop = is_desktop;
				}
				if (!is_desktop) {
					widthDevice = 420;
				} else {
					widthDevice = 960;
				}
				content = "width=" + widthDevice + ", user-scalable=no";
				var scale = 1;
				if (!is_desktop && widthDevice != window.screen.width && window.screen.width > 0) {
					scale = window.screen.width / widthDevice;
				}
				if (scale != 1) {
					content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
				}
				var docViewport = document.getElementById("viewport");
				if (!docViewport) {
					docViewport = document.createElement("meta");
					docViewport.setAttribute("id", "viewport");
					docViewport.setAttribute("name", "viewport");
					document.head.appendChild(docViewport);
				}
				docViewport.setAttribute("content", content);
			};
			window.ladi_viewport();
		</script> -->

		<script id="script_event_data" type="text/javascript">
	    (function() {
	      var run = function() {
	        if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
	          setTimeout(run, 100);
	          return;
	        }
	        window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
	        window.LadiPageScript.runtime.ladipage_id = '61e7ee18c71f2700381ed90d';
	        window.LadiPageScript.runtime.publish_platform = 'LADIPAGEDNS';
	        window.LadiPageScript.runtime.is_mobile_only = false;
	        window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["bcavietnam.com"];
	        window.LadiPageScript.runtime.DOMAIN_FREE = [];
	        window.LadiPageScript.runtime.bodyFontSize = 12;
	        window.LadiPageScript.runtime.store_id = "5e4cf269a9c6692c79a6477c";
	        window.LadiPageScript.runtime.time_zone = 7;
	        window.LadiPageScript.runtime.currency = "VND";
	        window.LadiPageScript.runtime.convert_replace_str = true;
	        window.LadiPageScript.runtime.desktop_width = 960;
	        window.LadiPageScript.runtime.mobile_width = 420;
	        window.LadiPageScript.runtime.eventData =
	          "%7B%22SECTION_POPUP%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22SECTION369%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22IMAGE375%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE377%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22IMAGE380%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE382%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22IMAGE385%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP386%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE387%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE390%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP391%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE392%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE394%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE395%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION396%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22HEADLINE397%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE398%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE399%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceIn%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22SHAPE400%22%3A%7B%22type%22%3A%22shape%22%2C%22desktop.style.animation-name%22%3A%22shake%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22shake%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22GROUP401%22%3A%7B%22type%22%3A%22group%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION550%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22IMAGE402%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION1701%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE403%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION404%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22HEADLINE410%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH411%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE414%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH415%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE421%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH422%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE428%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH429%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE434%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE435%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION436%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22PARAGRAPH439%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE440%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH442%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE443%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH445%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE446%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE447%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE448%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE449%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION450%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22HEADLINE452%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22VIDEO454%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FjuUR1SEF-Ak%22%2C%22option.video_type%22%3A%22youtube%22%7D%2C%22VIDEO456%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FTFRJGdhvis0%22%2C%22option.video_type%22%3A%22youtube%22%7D%2C%22VIDEO459%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FynsHKzm5PH0%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22VIDEO462%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FDt1sMA6gXWo%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22HEADLINE464%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE465%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE466%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE467%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE468%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE469%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH470%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH471%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH472%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH473%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION474%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22VIDEO477%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FDS-7UDHY7xo%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%2C%22desktop.option.video_autoplay%22%3Atrue%2C%22mobile.option.video_autoplay%22%3Atrue%7D%2C%22HEADLINE479%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE480%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE481%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP482%22%3A%7B%22type%22%3A%22group%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION550%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22IMAGE483%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE484%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION485%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22IMAGE486%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP488%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22HEADLINE490%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH491%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE496%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH497%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE502%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH503%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE508%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH509%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE513%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE514%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP515%22%3A%7B%22type%22%3A%22group%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION550%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%220s%22%7D%2C%22IMAGE516%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE517%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION518%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22HEADLINE519%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInLeft%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceInLeft%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE520%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE521%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE522%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE523%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH528%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH533%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH538%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH543%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH548%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION550%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22HEADLINE552%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInLeft%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInLeft%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22SECTION553%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22HEADLINE558%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE559%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE563%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE564%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE569%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22COUNTDOWN573%22%3A%7B%22type%22%3A%22countdown%22%2C%22option.countdown_type%22%3A%22countdown%22%2C%22option.countdown_minute%22%3A27%7D%2C%22COUNTDOWN_ITEM574%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22day%22%7D%2C%22COUNTDOWN_ITEM575%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22hour%22%7D%2C%22COUNTDOWN_ITEM576%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22minute%22%7D%2C%22COUNTDOWN_ITEM577%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22seconds%22%7D%2C%22HEADLINE578%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE579%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE580%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE581%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM582%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%225eb50e7381e7d47b21eef07a%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.thankyou_value%22%3A%22https%3A%2F%2Finsurance.bcavietnam.com%2Fthank-you-page%22%2C%22option.form_captcha%22%3Atrue%2C%22option.is_form_coupon%22%3Afalse%2C%22option.is_form_login%22%3Afalse%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22BUTTON583%22%3A%7B%22type%22%3A%22button%22%2C%22option.is_submit_form%22%3Afalse%2C%22option.is_buy_now%22%3Afalse%2C%22option.data_setting.type_dataset%22%3A%22COLLECTION%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%220.2s%22%2C%22mobile.style.animation-name%22%3A%22pulse%22%2C%22mobile.style.animation-delay%22%3A%220.2s%22%7D%2C%22BUTTON_TEXT583%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM585%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM586%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM587%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION588%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22HEADLINE591%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE594%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE597%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE600%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH603%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22PARAGRAPH604%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE605%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION606%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22GALLERY609%22%3A%7B%22type%22%3A%22gallery%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.option.gallery_control.autoplay%22%3Atrue%2C%22desktop.option.gallery_control.autoplay_time%22%3A4%2C%22mobile.option.gallery_control.autoplay%22%3Atrue%2C%22mobile.option.gallery_control.autoplay_time%22%3A4%7D%2C%22HEADLINE610%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE612%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE615%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE619%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM620%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22select%22%2C%22option.input_tabindex%22%3A4%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM618%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22select%22%2C%22option.input_tabindex%22%3A5%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE617%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22NOTIFY368%22%3A%7B%22type%22%3A%22notify%22%2C%22option.data_setting.type%22%3A%22default%22%2C%22option.sheet_id%22%3A%221WXZsybVDWLghgDB7D7AbROmuwiHKYk_feIeDBVDZkKQ%22%2C%22option.time_show%22%3A0%2C%22option.time_delay%22%3A10%2C%22desktop.option.position%22%3A%22bottom_center%22%2C%22mobile.option.position%22%3A%22bottom_center%22%7D%2C%22IMAGE366%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE364%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE363%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE361%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE360%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE359%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE358%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE355%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE354%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE353%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE351%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE350%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE349%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM348%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_default_value%22%3A%221%22%2C%22option.input_tabindex%22%3A4%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22BUTTON_TEXT346%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22BUTTON346%22%3A%7B%22type%22%3A%22button%22%2C%22option.is_submit_form%22%3Afalse%2C%22option.is_buy_now%22%3Afalse%2C%22option.data_setting.type_dataset%22%3A%22COLLECTION%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM345%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM344%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM343%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM342%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%225eb50e7381e7d47b21eef07a%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.thankyou_value%22%3A%22https%3A%2F%2Finsurance.bcavietnam.com%2Fthank-you-page%22%2C%22option.form_captcha%22%3Atrue%2C%22option.is_form_coupon%22%3Afalse%2C%22option.is_form_login%22%3Afalse%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE339%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22IMAGE338%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE337%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22pulse%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22HEADLINE336%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE331%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE330%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE328%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE323%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION322%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22IMAGE621%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE623%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE636%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH637%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE644%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE645%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE648%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH649%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE656%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE657%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE669%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE670%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE671%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION664%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22IMAGE676%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH677%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE684%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE685%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE687%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH689%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE690%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH691%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH692%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE693%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE695%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION696%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22PARAGRAPH705%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInUp%22%2C%22desktop.style.animation-delay%22%3A%220.2s%22%2C%22mobile.style.animation-name%22%3A%22fadeInUp%22%2C%22mobile.style.animation-delay%22%3A%220.2s%22%7D%2C%22PARAGRAPH720%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH722%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInUp%22%2C%22desktop.style.animation-delay%22%3A%220.2s%22%2C%22mobile.style.animation-name%22%3A%22fadeInUp%22%2C%22mobile.style.animation-delay%22%3A%220.2s%22%7D%2C%22VIDEO728%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3Deduh6m_O9Lg%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%7D";
	        window.LadiPageScript.run(true);
	        window.LadiPageScript.runEventScroll();
	      };
	      run();
	    })();
	  </script>

		<!-- <script id="script_event_data" type="text/javascript">
		  (function() {
		    var run = function() {
		      if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
		        setTimeout(run, 100);
		        return;
		      }
		      window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
		      window.LadiPageScript.runtime.ladipage_id = '5eb11e84c310210e1b17f103';
		      window.LadiPageScript.runtime.isMobileOnly = false;
		      window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["bcavietnam.com"];
		      window.LadiPageScript.runtime.DOMAIN_FREE = [];
		      window.LadiPageScript.runtime.bodyFontSize = 12;
		      window.LadiPageScript.runtime.store_id = "5e4cf269a9c6692c79a6477c";
		      window.LadiPageScript.runtime.time_zone = 7;
		      window.LadiPageScript.runtime.currency = "VND";
		       window.LadiPageScript.runtime.eventData =
		         "%7B%22BUTTON199%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22FRAME204%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME209%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME214%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME219%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON228%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP266%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP272%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP278%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP284%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON297%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP299%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME341%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME347%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME353%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME359%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP479%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP484%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FORM_ITEM466%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22FORM_ITEM465%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM464%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22BUTTON462%22%3A%7B%22type%22%3A%22button%22%2C%22desktop.style.animation-name%22%3A%22shake%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22shake%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FORM461%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Afalse%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Afalse%2C%22option.form_auto_complete%22%3Afalse%7D%2C%22GROUP683%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22VIDEO685%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2Feduh6m_O9Lg%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22VIDEO686%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2Fd9Qs8BJhvu8%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22VIDEO688%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F5147exHSpWM%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22FORM696%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Afalse%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Afalse%2C%22option.form_auto_complete%22%3Afalse%7D%2C%22FORM_ITEM699%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22FORM_ITEM700%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM701%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22VIDEO726%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F1uqsSlYZ7os%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON727%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22BOX190%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BOX191%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP730%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22VIDEO734%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FDS-7UDHY7xo%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22COUNTDOWN743%22%3A%7B%22type%22%3A%22countdown%22%2C%22option.countdown_type%22%3A%22daily%22%2C%22option.countdown_daily_start%22%3A%2200%3A00%3A00%22%2C%22option.countdown_daily_end%22%3A%2223%3A59%3A59%22%7D%2C%22COUNTDOWN_ITEM744%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22day%22%7D%2C%22COUNTDOWN_ITEM745%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22hour%22%7D%2C%22COUNTDOWN_ITEM746%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22minute%22%7D%2C%22COUNTDOWN_ITEM747%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22seconds%22%7D%2C%22COUNTDOWN748%22%3A%7B%22type%22%3A%22countdown%22%2C%22option.countdown_type%22%3A%22daily%22%2C%22option.countdown_daily_start%22%3A%2200%3A00%3A00%22%2C%22option.countdown_daily_end%22%3A%2223%3A59%3A59%22%7D%2C%22COUNTDOWN_ITEM749%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22day%22%7D%2C%22COUNTDOWN_ITEM750%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22hour%22%7D%2C%22COUNTDOWN_ITEM751%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22minute%22%7D%2C%22COUNTDOWN_ITEM752%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22seconds%22%7D%7D";
		      window.LadiPageScript.run(true);
		      window.LadiPageScript.runEventScroll();
		    };
		    run();
		  })();
		</script> -->

	<?php
		}
	?>

	<script>
	jQuery(document).ready(function(){
	    <?php if($_REQUEST['Itemid'] == 295 ||
	    $_REQUEST['Itemid'] == 296 ||
	    $_REQUEST['Itemid'] == 297 ||
	    $_REQUEST['Itemid'] == 298
	    ){
	    ?>
	    jQuery('.item-294.plus').trigger('click');
	    <?php
	    }
	    ?>

			<?php
			// fix Social for App mobile
			if($_REQUEST['is_app'] == 1){
			?>
	    jQuery('a').each(function() {
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					//this.href + '?is_app=1'
					jQuery(this).attr('href', res_end);
				}
	    });
			var logout_form_href = jQuery('#jomsocial-logout-form').attr('action');
			jQuery('#jomsocial-logout-form').attr('action',logout_form_href+'?is_app=1');
			jQuery('.left-logo-search-account a').attr('href','<?php echo $this->baseurl; ?>/mxh.html?is_app=1');

			<?php
	    }
	    ?>
	});
	</script>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">

        <iframe id="iframeYoutube" width="560" height="315"  src="https://www.youtube.com/embed/5147exHSpWM" frameborder="0" allowfullscreen></iframe>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<script>
jQuery(document).ready(function(){
  jQuery("#myModal").on("hidden.bs.modal",function(){
    jQuery("#iframeYoutube").attr("src","#");
  })
	jQuery(".item-335 a").click(function(){
  	jQuery("#jomsocial-logout-form").submit();
		<?php if($is_social == 0){ ?>
		window.location.href = "<?php echo $this->baseurl; ?>/mxh.html";
		<?php } ?>
	});

	jQuery("#noti-general-a").click(function(){
		setTimeout(function() {
			jQuery('#noti-general-li ul a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});



	jQuery("#noti-friendrequest-a").click(function(){
		setTimeout(function() {
			jQuery('#noti-friendrequest-li ul a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});


	jQuery("#noti-mobile-friendrequest-a").click(function(){
		setTimeout(function() {
			jQuery('.mfp-wrap  a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});


	jQuery("#noti-mobile-general-a").click(function(){
		setTimeout(function() {
			jQuery('.mfp-wrap a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});
})

function changeVideo(vId){
  var iframe=document.getElementById("iframeYoutube");
  iframe.src="https://www.youtube.com/embed/"+vId;

  jQuery("#myModal").modal("show");
}
</script>

<?php if($_REQUEST['option'] != 'com_community' && $_REQUEST['Itemid'] != TECH_INSURACNE && $_REQUEST['Itemid'] != FOUNDER_STORY && $_REQUEST['Itemid'] != FOUR_ZERO_INSURACNE && $_REQUEST['Itemid'] != AGENT){ ?>
				<!-- <div class="fb-customerchat" attribution=setup_tool page_id="362028714601667" logged_in_greeting="Xin chào! Bạn cần giúp đỡ?" logged_out_greeting="Xin chào! Bạn cần giúp đỡ?"></div> -->
				<!-- <script id='autoAdsMaxLead-widget-script' src='https://cdn.autoads.asia/scripts/autoads-maxlead-widget.js?business_id=C5905BB564BF40B29E8D19BC4EE69188' type='text/javascript' charset='UTF-8' async></script> -->

<?php } ?>

<!-- Global site tag (gtag.js) - Google Analytics --> <script async src="https://www.googletagmanager.com/gtag/js?id=UA-170614096-1"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'UA-170614096-1'); </script>
<?php if($_REQUEST['Itemid'] != AGENT): ?>
	<!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "102967654972383");
      chatbox.setAttribute("attribution", "biz_inbox");

      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v11.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
<?php endif; ?>
<style>
.box-service-2 .col-image1{
    clip-path: polygon(0 0, calc(100% - 430px) 0, 100% 100%, 0 100%);
}
.box-service-2 .bg-info1{
	height: calc(100% - 150px);
}
.slide-categories .banner-1{
  height: calc(100vw * (560/(1280 + 0)))!important;
}

<?php if($is_home_page){ ?>
.loop-line{
  height: 13px;
  width: 100%;
  z-index: 99;
	margin-top: -95px;

}
.loop-line::after{
  content: "";
  display: block;
  bottom: 0;
  left: 0;
  height: 8vw;
  width: 100%;
  background: white;
  border-radius: calc(12% + 70px) 0 0 0;
  border-top: solid 8px #038A96;
  box-shadow: inset 0 5px 0 #F58C29;
}
<?php } ?>

<?php if(!$is_home_page){ ?>
.loop-line{
  height: 13px;
  width: 100%;
  z-index: 99;
	margin-top: -113px;

}
.loop-line::after{
  content: "";
  display: block;
  bottom: 0;
  left: 0;
  height: 8vw;
  width: 100%;
  background: white;
  border-radius: calc(15% + 100px) 0 0 0;
  border-top: solid 8px #038A96;
  box-shadow: inset 0 5px 0 #F58C29;
}
<?php } ?>


@media (max-width: 960px) {
	.box-service-2 .bg-info1{
		height: calc(100% - 0px);
	}
	.box-service-2 .col-image1{
	    clip-path: polygon(0 0, calc(100% - 0px) 0, 100% 100%, 0 100%);
	}
	.slide-categories .banner-1{
	  height: calc(100vw * (800/(1280 + 50)))!important;
	}
}



</style>
</body>
</html>
