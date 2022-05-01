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
$version = '1318042022';

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
									<?php echo $logo; ?>

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
				<div class="container">
					<div class="row">
						<div class="col-xs-12 background-image">
							<img width="1280" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/banner-top2.png" alt="<?php echo $sitename ?>" />
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
						?> col-lg-9 col-md-9
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
				<div class="col-xs-12 col-md-12 left-comparation">

					<div class="box-service">
						<div class="image"></div>
						<div class="info">
							<a href="#" class="item-center">BẢO HIỂM </br>CÔNG NGHỆ 4.0</a>
							<div class="item">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="61.983" height="61.857" viewBox="0 0 61.983 61.857"><defs><clipPath id="a1"><rect width="61.983" height="61.857" transform="translate(0)" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#a1)"><path d="M30.758,66.476l-.245-.5.278-.487,3.882-6.834A7.038,7.038,0,1,0,40.5,64.63l-6.667,3.788-.446.253-.476-.19a4.043,4.043,0,0,1-2.152-2.005" transform="translate(-11.638 -25.628)" fill="#f78d2d"/><path d="M56.378,60.192l1.666.543a12.906,12.906,0,0,0-1.55-2.955l-1.846,1.847Z" transform="translate(-23.917 -25.288)" fill="#f78d2d"/><path d="M28.915,47.9a12.983,12.983,0,1,0,12.347,8.983l-1.63.926-.9.51a10.16,10.16,0,1,1-7.089-7.211l.467-.819.965-1.7a12.91,12.91,0,0,0-4.162-.689" transform="translate(-6.975 -20.965)" fill="#f78d2d"/><path d="M48.277,52.889,50.16,51a12.963,12.963,0,0,0-2.98-1.6l.551,1.754Z" transform="translate(-20.649 -21.62)" fill="#f78d2d"/><path d="M41.049,53.906A19.14,19.14,0,1,1,33.881,39L35.9,36.989a22,22,0,1,0,3.073,3.1l-2.015,2.015a19.016,19.016,0,0,1,4.1,11.8" transform="translate(0 -13.99)" fill="#f78d2d"/><path d="M70.307.277a6.786,6.786,0,0,0-1.447,2.991L67.9,8.134,66.55,9.483l.962-4.867a3.111,3.111,0,0,0,.066-.584c0-.6-.29-.736-.754-.272A6.8,6.8,0,0,0,65.377,6.75l-.962,4.867-1.348,1.348L64.029,8.1a3.1,3.1,0,0,0,.066-.583c0-.6-.29-.737-.753-.273a6.8,6.8,0,0,0-1.448,2.991L60.932,15.1,42.764,33.268l-1.072-3.416L35.709,40.384a3,3,0,0,0,1.586,1.478l10.462-5.944-3.384-1.1L62.511,16.677l4.867-.961a6.821,6.821,0,0,0,2.992-1.448.76.76,0,0,0,.276-.47c0-.263-.408-.36-1.132-.218l-4.868.963L65.993,13.2l4.866-.962a6.794,6.794,0,0,0,2.992-1.448.76.76,0,0,0,.277-.468c0-.264-.409-.362-1.134-.218l-4.868.962,1.348-1.348,4.868-.962A6.793,6.793,0,0,0,77.333,7.3a.76.76,0,0,0,.278-.469c0-.264-.409-.362-1.133-.218L69.773,7.94l-.1-.1,1.324-6.7A3.105,3.105,0,0,0,71.06.549c0-.6-.29-.737-.753-.273" transform="translate(-15.628 0)" fill="#f78d2d"/></g></svg>


									</div></a>
									<div class="box-service-item-info">
										<h3 class="">GIẢI PHÁP DATACENTER</h3>
										<p>Tìm kiếm Data nóng, Khách hàng có nhu cầu, Data không trùng lặp, được remarketing.</p>
									</div>
								</div>
							</div> <div class="item">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="61.865" height="61.857" viewBox="0 0 61.865 61.857"><defs><clipPath id="a2"><rect width="61.865" height="61.857" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#a2)"><path d="M6.61,59.57a7.855,7.855,0,0,0,11.083,0L30.423,46.838a1.567,1.567,0,1,0-2.216-2.217L15.475,57.353a4.721,4.721,0,0,1-6.649,0L4.508,53.035a4.706,4.706,0,0,1,0-6.649L46.389,4.5a4.721,4.721,0,0,1,6.65,0l4.318,4.318a4.707,4.707,0,0,1,0,6.649L35.377,37.452a4.721,4.721,0,0,1-6.65,0l-.836-.836a4.707,4.707,0,0,1,0-6.649L46.345,11.514A1.567,1.567,0,1,0,44.128,9.3L25.675,27.751a7.845,7.845,0,0,0,0,11.082l.836.836a7.854,7.854,0,0,0,11.082,0L59.574,17.688a7.845,7.845,0,0,0,0-11.081L55.255,2.287a7.857,7.857,0,0,0-11.083,0L2.291,44.169a7.847,7.847,0,0,0,0,11.083Zm9.5-.679a6.3,6.3,0,0,1-7.41.371,6.3,6.3,0,0,0,7.41-.371M30.264,40.132a6.224,6.224,0,0,1-2.646-1.572,6.224,6.224,0,0,0,2.646,1.572" transform="translate(0)" fill="#f78d2d"/></g></svg>
									</div></a>
									<div class="box-service-item-info">
										<h3 class="">QUY TRÌNH GIAO KẾT HỢP ĐỒNG ĐẶC BIỆT</h3>
										<p>Minh bạch, tinh gọn; với sự tham gia của bên thứ ba - đại diện về mặt pháp lý; qui trình được ghi âm, ghi hình.</p>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="84.215" height="61.857" viewBox="0 0 84.215 61.857"><defs><clipPath id="ac"><rect width="84.215" height="61.857" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#ac)"><path d="M31.691,49.54a1.072,1.072,0,0,0,1.016.733c12.855,0,26.148,0,39.334.033h0a1.07,1.07,0,0,0,0-2.141c-12.925-.033-25.951-.033-38.566-.033-1.5-4.546-2.828-8.965-4.23-13.633-.739-2.46-1.5-5-2.29-7.547-.635-2.288-1.286-4.4-2.175-7.067C23.042,14.117,19.539,2.811,19.5,2.7a1.073,1.073,0,0,0-1.023-.754H16.4A2.391,2.391,0,0,0,14.008,0H2.425A2.368,2.368,0,0,0,0,2.3V3.74a2.369,2.369,0,0,0,2.425,2.3H14.008A2.392,2.392,0,0,0,16.4,4.086h1.289c.816,2.637,3.57,11.541,5.048,16.433a.23.23,0,0,1,.01.028c.88,2.642,1.524,4.733,2.152,6.994,0,.01.006.019.008.029.784,2.541,1.549,5.086,2.289,7.547,1.416,4.715,2.882,9.592,4.493,14.424" transform="translate(0 0)" fill="#f78d2d"/><path d="M101.31,21.277H47.027a2.37,2.37,0,0,0-2.426,2.3v1.437a2.369,2.369,0,0,0,2.426,2.3H47.8l8.148,27.705a1.069,1.069,0,0,0,1.027.769H91.362a1.07,1.07,0,0,0,1.027-.769l8.148-27.705h.773a2.367,2.367,0,0,0,2.425-2.3V23.579a2.368,2.368,0,0,0-2.425-2.3" transform="translate(-19.52 -9.312)" fill="#f78d2d"/><path d="M53.8,90.177a5.573,5.573,0,1,0,5.574,5.573A5.58,5.58,0,0,0,53.8,90.177" transform="translate(-21.105 -39.467)" fill="#f78d2d"/><path d="M124.822,90.177A5.573,5.573,0,1,0,130.4,95.75a5.579,5.579,0,0,0-5.574-5.573" transform="translate(-52.191 -39.467)" fill="#f78d2d"/></g></svg></div></a>
									<div class="box-service-item-info">
										<h3 class="">SIÊU THỊ BẢO HIỂM ĐA DẠNG CÁC SẢN PHẨM</h3>
										<p>Hơn 200 sản phẩm bảo hiểm Nhân thọ và Phi nhân thọ; đối tác là các doanh nghiệp bảo hiểm uy tín hàng đầu Việt Nam; trao quyền chủ động lựa chọn sản phẩm cho Khách hàng.</p>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="54.958" height="61.857" viewBox="0 0 54.958 61.857"><defs><clipPath id="a3"><rect width="54.958" height="61.857" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#a3)"><path d="M25.616,25.214c2.917,6.935,8.475,10.709,12.182,10.709,3.642,0,8.746-3.647,11.7-10.493a31.908,31.908,0,0,0,1.771-5.862,23.346,23.346,0,0,0,.75-6.457C52.024,6,45.655,0,37.8,0S23.576,6,23.576,13.111a31.024,31.024,0,0,0,.331,4.823,28.286,28.286,0,0,0,1.709,7.28" transform="translate(-10.318)" fill="#f78d2d"/><path d="M52.631,74.977c-2.844-3.04-10.838-2.278-14.79-4.367-1.757,3.287-1.78,8.688-4.7,8.688a2.5,2.5,0,0,1-.39-.031c-4.4-.659-2.2-11.869-5.275-11.869s-.879,11.21-5.274,11.869a2.513,2.513,0,0,1-.391.031c-2.916,0-2.94-5.4-4.7-8.688C13.168,72.7,5.174,71.937,2.34,74.977.8,76.624,0,86.823,0,91.355H54.958c0-4.532-.795-14.731-2.327-16.378" transform="translate(0 -29.498)" fill="#f78d2d"/></g></svg>
									</div></a>
									<div class="box-service-item-info">
										<h3 class="">ĐỘI NGŨ TƯ VẤN TÀI CHÍNH</h3>
										<p>Kiến thức chuyên môn bài bản; khách quan, trung thực, tận tâm; có kinh nghiệm kỹ năng vững chắc.</p>
									</div>
								</div>
							</div>
							<div class="item">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="68.844" height="61.857" viewBox="0 0 68.844 61.857"><defs><clipPath id="ad"><rect width="68.844" height="61.857" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#ad)"><path d="M39.911,10.923h1.651c.81,0,1.494-.368,1.494-.8v-4.5a1,1,0,0,1,.984-.984H58.431a1,1,0,0,1,.984.984v4.545c0,.4.7.755,1.493.755h1.652c.8,0,1.494-.352,1.494-.755V5.623A5.628,5.628,0,0,0,58.431,0H44.039a5.629,5.629,0,0,0-5.622,5.623v4.423c0,.476.685.877,1.494.877" transform="translate(-16.814)" fill="#f78d2d"/><path d="M56,72.5h4.549A1.5,1.5,0,0,0,62.048,71V69.3a1.5,1.5,0,0,0-1.494-1.493H56A1.5,1.5,0,0,0,54.51,69.3V71A1.5,1.5,0,0,0,56,72.5" transform="translate(-23.857 -29.678)" fill="#f78d2d"/><path d="M2.614,0h2.31A2.614,2.614,0,0,1,7.537,2.614v0A2.614,2.614,0,0,1,4.923,5.229H2.616A2.616,2.616,0,0,1,0,2.614v0A2.614,2.614,0,0,1,2.614,0Z" transform="translate(30.653 45.397)" fill="#f78d2d"/><path d="M66.053,70.649c-.4.15-.757.276-1.086.387A88.678,88.678,0,0,1,41.6,75.6v2.9A4.608,4.608,0,0,1,37,83.1H33.964a4.608,4.608,0,0,1-4.6-4.6V75.6A87.835,87.835,0,0,1,4.323,70.437c-.223-.064-.629-.221-1.608-.614l-.126-.05-.163-.065V88.586a3.772,3.772,0,0,0,3.767,3.769H64.774a3.772,3.772,0,0,0,3.767-3.769v-18.9c-.263.106-.546.219-.841.336Z" transform="translate(-1.062 -30.498)" fill="#f78d2d"/><path d="M65.191,24.478H3.654A3.658,3.658,0,0,0,0,28.133V44.465a1.526,1.526,0,0,0,.735,1.129c.011,0,.674.3,1.753.725a85.735,85.735,0,0,0,25.823,5.638V49.211c0-1.821,2.747-3.062,4.592-3.062H35.94c2.143,0,4.589,1.406,4.54,3.544v2.263a86.514,86.514,0,0,0,22.578-4.416c2.221-.765,5.027-1.96,5.055-1.972a1.476,1.476,0,0,0,.73-1.1V28.133a3.659,3.659,0,0,0-3.654-3.655" transform="translate(0 -10.713)" fill="#f78d2d"/></g></svg></div></a>
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
							<h3 class="">Nên tảng phân phối bảo hiểm online</h3>
							<ul>
								<li><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="31.699" viewBox="0 0 32 31.699"><defs><clipPath id="a"><rect width="32" height="31.699" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#a)"><path d="M37.882,95.123H28.291A4.287,4.287,0,0,0,24,99.41H42.169a4.287,4.287,0,0,0-4.287-4.287" transform="translate(-17.087 -67.712)" fill="#f78d2d"/><rect width="5.586" height="3.248" transform="translate(13.207 23.254)" fill="#f78d2d"/><path d="M31.19,0H.81A.812.812,0,0,0,0,.811v20.6a.812.812,0,0,0,.81.81H31.19a.812.812,0,0,0,.81-.81V.811A.812.812,0,0,0,31.19,0m-2.3,18.287a.82.82,0,0,1-.826.81H3.935a.82.82,0,0,1-.826-.81V3.928a.821.821,0,0,1,.826-.81h24.13a.821.821,0,0,1,.826.81Z" transform="translate(0 -0.001)" fill="#f78d2d"/></g></svg></span>Kinh doanh bảo hiểm online mọi lúc mọi nơi</li>

								<li><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="37.676" height="32" viewBox="0 0 37.676 32"><defs><clipPath id="a"><rect width="37.676" height="32" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#a)"><path d="M20.625,62.85c-.06.093-.119.188-.181.278a8.478,8.478,0,0,1-.611.816c-.1.013-.213.023-.319.035-.187.021-.372.043-.565.063.1-.182.191-.379.284-.579.122-.266.241-.541.352-.834.019-.054.041-.109.061-.166.347-.036.683-.08,1.018-.126.026-.352.055-.731.089-1.166q-.347.042-.7.077c.018-.065.037-.132.055-.2q-.479-.01-.968-.04c-.025.108-.05.219-.077.325-.278.018-.563.029-.846.043-.2.01-.4.025-.609.032.033-.173.067-.35.1-.529q-.45-.054-.9-.124c-.022.188-.043.371-.065.551-.005.04-.01.082-.015.122h-.007c-.164,0-.33,0-.493,0l-.457,0h-.045c-.034-.284-.069-.582-.1-.885-.326-.066-.651-.139-.976-.218.064.374.13.737.2,1.081-.111,0-.218-.013-.33-.019-.323-.016-.647-.033-.963-.056l-.161-.01c-.109-.427-.209-.879-.3-1.35-.005-.027-.011-.056-.016-.082q-.559-.174-1.115-.374c.028.125.056.249.086.37.111.466.23.916.358,1.345-.124-.013-.247-.03-.37-.044q-.535-.063-1.053-.138l-.125-.018c-.193-.431-.374-.889-.543-1.367-.109-.307-.211-.624-.308-.947q-.679-.312-1.338-.666c.017.06.035.116.053.176.123.419.254.828.4,1.223q.259.717.558,1.377c-.152-.029-.3-.061-.447-.092-.282-.059-.559-.121-.827-.188a17.633,17.633,0,0,1-1.251-2.677c-.045-.119-.092-.236-.135-.358.06.021.124.04.185.06q.6.2,1.246.38l.08.023a21.149,21.149,0,0,1-3.258-2.187l-.1-.045c-.309-.136-.607-.276-.886-.422-.12-.061-.234-.122-.346-.185-.066-.241-.126-.486-.182-.732a17.2,17.2,0,0,1-1.489-1.741c.034.3.073.6.122.893q.051.314.113.622l-.079-.067a6.356,6.356,0,0,1-.973-.977,15.244,15.244,0,0,1-.2-2.016A15.7,15.7,0,0,1,.106,48.742a15.892,15.892,0,0,0,.117,4.49q.094.556.227,1.1.159.658.369,1.289a15.707,15.707,0,0,0,2.162,4.24c.047.067.1.127.148.193a15.745,15.745,0,0,0,5.968,4.9,16.834,16.834,0,0,0,2.869,1,16,16,0,0,0,2.281.472c.167.022.335.042.5.059.252.025.506.043.761.058.223.011.446.019.671.022.064,0,.127,0,.191,0,.169,0,.337-.011.505-.017l.126,0A15.891,15.891,0,0,0,24.047,64.6a15.775,15.775,0,0,0,3.207-2.264,15.617,15.617,0,0,0,1.972-2.165,56.172,56.172,0,0,1-8.755,4.953c.06-.921.1-1.583.154-2.277M3.733,58.691c-.092-.061-.184-.123-.267-.183a15.122,15.122,0,0,1-1.78-4.2,8.143,8.143,0,0,0,.908.87c.054.045.105.089.161.133.095.352.2.7.317,1.036.143.424.305.835.477,1.237.211.493.436.973.685,1.428-.182-.105-.346-.211-.5-.316M5.5,61.108c-.331-.342-.652-.694-.951-1.066.148.09.3.175.467.261.2.3.408.578.621.851.124.16.248.32.376.471-.177-.166-.344-.342-.513-.517m.971-1.121c-.16-.056-.317-.113-.468-.17a16.8,16.8,0,0,1-1.715-3.5c.348.2.724.379,1.114.556.073.034.143.069.218.1.054.17.114.336.173.5.147.421.3.834.474,1.232.214.5.444.982.691,1.439-.166-.052-.328-.106-.486-.161M8.85,63.074l-.03-.01A13.352,13.352,0,0,1,7.385,61.65c-.065-.073-.128-.152-.192-.227s-.15-.174-.224-.266l.1.033c.212.076.429.151.652.221.084.128.171.252.258.375a13,13,0,0,0,1.069,1.331l.025.027c-.077-.023-.148-.047-.219-.07m1.97.536c-.081-.071-.161-.15-.241-.226a11.352,11.352,0,0,1-1.354-1.549c.151.036.31.067.464.1.2.045.4.092.6.132.025.046.052.088.077.132a12.1,12.1,0,0,0,.83,1.265c.041.054.081.11.122.161.027.034.051.065.078.1-.2-.037-.39-.074-.575-.115m1.925.326-.152-.018a9.04,9.04,0,0,1-.59-.789c-.182-.266-.358-.551-.528-.85.267.04.545.072.82.107.1.012.195.027.294.039l.209.027c.022.058.044.12.066.178.139.368.291.707.448,1.03.059.122.116.249.178.363-.255-.026-.5-.056-.744-.086m1.29,1.314c.093.01.186.02.281.027a4.092,4.092,0,0,0,.295.322,5.051,5.051,0,0,1-.576-.349m.283-1.16a12.433,12.433,0,0,1-.592-1.551c.445.034.9.061,1.361.076q.134.6.262,1.1c.031.127.063.25.094.367l.016.062h-.024c-.2-.006-.4-.019-.594-.03-.175-.009-.354-.014-.525-.026m1.067,1.594c-.063-.059-.129-.125-.2-.2-.044-.046-.086-.1-.129-.154.25.015.5.022.76.028.046.131.088.25.129.353-.188-.005-.378-.015-.563-.027m.838-.317v-.007h0Zm.152-1.208h-.2c-.02,0-.04,0-.06,0h-.036c-.058-.446-.127-.957-.2-1.525.114,0,.232,0,.346,0s.235,0,.353,0c-.049.381-.1.733-.139,1.058l-.061.466m.886,1.325a2.341,2.341,0,0,1-.217.217c-.18.008-.36.013-.543.013.039-.1.081-.22.127-.354.258,0,.509-.014.76-.026-.042.053-.086.1-.127.15m.577.115a4.013,4.013,0,0,0,.29-.314c.093-.01.185-.02.278-.027a5.476,5.476,0,0,1-.568.342m.287-1.493c-.215.013-.437.019-.657.028-.161.006-.319.015-.483.02.043-.157.086-.324.129-.5.08-.321.162-.666.244-1.038q.69-.022,1.361-.073a12.787,12.787,0,0,1-.594,1.559" transform="translate(0 -34.563)" fill="#f78d2d"/><path d="M32.7,17.62a15.548,15.548,0,0,0,.082-1.607A15.95,15.95,0,0,0,29.8,6.728a15.7,15.7,0,0,0-3.873-3.8A16.035,16.035,0,0,0,11.726.789a17.285,17.285,0,0,0-2.3.845A15.4,15.4,0,0,0,7.422,2.762a2.548,2.548,0,0,0-.509.295,1.293,1.293,0,0,0-.271.255,15.921,15.921,0,0,0-2.2,2.012,15.611,15.611,0,0,0-1.131,1.4A15.128,15.128,0,0,0,2.174,8.57c-.06-.141-.115-.286-.173-.43A20.243,20.243,0,0,1,.782,3.478,20.853,20.853,0,0,0,.491,8.039c.6,12.008,12.639,17.453,21.685,17.372-.129,1.628-.185,2.443-.292,4.072a56.406,56.406,0,0,0,14.971-9.919c.386-.352.77-.71,1.152-1.076a38.066,38.066,0,0,1-5.31-.868M30.94,11.66a15.161,15.161,0,0,1,.575,3.051c.015.168.033.335.041.505a4.619,4.619,0,0,1-.911.92c0-.041,0-.082,0-.124a17.923,17.923,0,0,0-.36-3.612,4.143,4.143,0,0,0,.518-.552c.048-.063.093-.125.135-.188M29.306,8a15.252,15.252,0,0,1,1.449,3.081c-.025.034-.049.071-.076.108s-.057.063-.083.094a3.224,3.224,0,0,1-.46.444A17.339,17.339,0,0,0,28.964,8.4c.033-.033.061-.067.093-.1A2.906,2.906,0,0,0,29.306,8M27.148,9.59c.317-.154.611-.312.882-.478.021.047.039.1.059.144a16.862,16.862,0,0,1,1,3.144c-.04.022-.086.042-.127.063-.319.166-.665.327-1.042.481a20.273,20.273,0,0,0-.77-3.354m-.639-4.948a15.277,15.277,0,0,1,2.383,2.723c.043.063.087.127.131.193a1.835,1.835,0,0,1-.286.283c-.013.011-.023.021-.036.031-.054-.1-.11-.2-.165-.3-.067-.123-.133-.25-.2-.369A15.3,15.3,0,0,0,26.5,4.66Zm-.737.744a16.1,16.1,0,0,1,1.722,2.631c.077.147.158.29.232.441-.1.046-.2.092-.3.137-.172.076-.35.15-.537.221A18.134,18.134,0,0,0,25.43,5.761l-.012-.022c-.02-.033-.038-.068-.058-.1.141-.081.28-.166.412-.253M24.26,9.047A21.612,21.612,0,0,0,23.4,6.416q.53-.157,1.011-.344c.079.138.154.282.231.425a18.179,18.179,0,0,1,1.175,2.686c-.086.026-.178.052-.266.078-.344.1-.7.2-1.08.293l-.074.019c-.042-.178-.09-.352-.135-.526m.472,2.188c-.039-.228-.079-.456-.123-.68.524-.156,1.021-.323,1.481-.5.051.176.1.356.145.536a20.815,20.815,0,0,1,.535,2.775l-.044.013c-.364.118-.749.227-1.145.333-.177.047-.351.1-.534.141-.065-.894-.172-1.767-.315-2.614M23.5,3.155a12.824,12.824,0,0,1,1.754,1.619,3.137,3.137,0,0,1-.353.157,12.9,12.9,0,0,0-1.543-1.858c-.035-.034-.068-.071-.1-.1.082.058.163.125.245.186m-2.4-.89c.265.195.534.412.8.653a11.6,11.6,0,0,1,2.013,2.343l-.248.065c-.1.025-.195.05-.295.073-.124.03-.248.059-.377.087q-.328-.692-.7-1.306l-.033-.057a11.665,11.665,0,0,0-.8-1.162c-.173-.229-.353-.443-.533-.641.054-.017.109-.034.165-.054m-.106,5.56c-.058-.292-.118-.579-.182-.859.549-.081,1.077-.175,1.581-.29q.131.351.251.719a23.681,23.681,0,0,1,.65,2.421l-.183.033c-.357.068-.725.131-1.1.189-.212.032-.422.067-.64.095q-.157-1.2-.376-2.308m-.927-5.3a8.833,8.833,0,0,1,.714.933A12.336,12.336,0,0,1,21.5,4.666c.169.32.333.654.487,1.007-.162.026-.33.049-.5.072-.311.044-.627.084-.953.118-.188-.682-.393-1.321-.617-1.905a12.966,12.966,0,0,0-.584-1.318c.246-.031.489-.07.73-.115M18.291,7.213c.511-.026,1.011-.068,1.5-.119.056.3.107.607.157.92q.17,1.077.294,2.254l-.1.007c-.361.036-.731.062-1.1.087-.119.008-.233.021-.353.027-.076-.762-.163-1.491-.257-2.188-.046-.337-.093-.669-.142-.988m.5,4.362c.531-.037,1.05-.088,1.557-.148.083,1.02.139,2.082.163,3.176-.365.035-.743.06-1.12.086-.148.01-.289.025-.439.033-.016-.744-.053-1.463-.1-2.17-.02-.328-.039-.659-.065-.977m.319-7.339c.16.528.312,1.1.449,1.715-.131.01-.269.014-.4.022-.307.019-.614.038-.93.051l-.133.007c-.074-.427-.152-.832-.229-1.219-.152-.759-.307-1.438-.456-2.021.39-.012.775-.031,1.155-.066.12.274.235.575.345.893.069.2.136.4.2.618m-2.5,7.414c.345,0,.687,0,1.025-.017.056,1.006.1,2.054.117,3.133-.368.009-.739.01-1.113.007h-.066c-.411,0-.821-.01-1.226-.022.013-.689.037-1.359.065-2.019.017-.371.032-.743.052-1.1.378.012.76.022,1.145.022m-1.07-1.22q.076-1.087.17-2.09c.034-.371.069-.737.106-1.088.2.007.39.009.588.012.3,0,.591-.005.881-.015.035.335.068.681.1,1.033q.1,1.032.175,2.156H17.5c-.291.006-.583.011-.88.011l-.214,0c-.287,0-.574-.006-.856-.016M18.839,1.4h-.014c-.09,0-.178.014-.269.019-.023-.029-.045-.054-.068-.082a4.279,4.279,0,0,0-.319-.352,5.626,5.626,0,0,1,.67.414M17.345.863a1.974,1.974,0,0,1,.246.241c.032.034.063.074.1.113.062.074.124.153.185.241-.273.012-.551.019-.831.024-.04-.126-.078-.241-.114-.346s-.072-.2-.1-.287c.175,0,.348.007.523.015m-.791.36c.01.08.022.171.032.261h-.061c.012-.09.022-.181.029-.261m.022,1.586.181,0c.042.319.087.666.135,1.037.085.661.178,1.4.271,2.212-.116,0-.231,0-.347,0l-.4,0c-.156,0-.309,0-.462-.007h0v0c.144-1.266.287-2.361.4-3.245l.222,0M15.519,1.1l.019-.02a2.414,2.414,0,0,1,.207-.2c.18-.012.361-.022.541-.027-.027.072-.056.155-.087.241-.04.116-.084.243-.13.387-.28-.007-.556-.014-.831-.024a3.73,3.73,0,0,1,.281-.354m-.791.006c.072-.042.144-.085.214-.12-.037.035-.073.076-.109.114-.093.1-.184.2-.275.317-.093-.007-.188-.012-.281-.02.156-.111.305-.205.452-.291m-.606,2.736c.134-.408.276-.783.425-1.122.378.034.762.054,1.152.068-.085.335-.173.7-.261,1.1-.142.641-.286,1.356-.423,2.136H15c-.289-.01-.573-.024-.855-.041-.2-.012-.4-.024-.589-.038.136-.612.284-1.185.446-1.713.04-.134.081-.263.123-.391m-1.03,7.595c.4.048.81.09,1.226.12-.078,1.006-.137,2.056-.162,3.14-.352-.021-.694-.048-1.039-.076-.172-.013-.35-.021-.519-.037.016-.679.048-1.341.086-1.994.024-.4.047-.8.079-1.182,0,0,0-.008,0-.011.109.014.219.025.329.038m-.221-1.192q.109-1.04.257-2c.059-.393.122-.779.191-1.15.488.048.992.088,1.5.114-.152.977-.287,2.039-.4,3.164-.326-.019-.639-.051-.956-.08-.2-.017-.4-.029-.6-.049m-.536-6.789a8.46,8.46,0,0,1,.721-.94c.239.046.482.085.725.117A13.066,13.066,0,0,0,13.2,3.958c-.223.584-.429,1.22-.614,1.9-.247-.025-.49-.053-.727-.084s-.488-.066-.724-.1A13.74,13.74,0,0,1,12.218,3.64c.04-.06.077-.123.117-.182M8.06,13.9c-.329-.076-.646-.158-.955-.242-.264-.072-.524-.147-.772-.226.084-.705.2-1.391.351-2.059q.1-.47.228-.926c.038-.141.074-.285.115-.424q.34.13.7.254c.252.086.511.168.778.248A27.739,27.739,0,0,0,8.06,13.9M7.983,9.358q-.351-.095-.68-.194A18.69,18.69,0,0,1,8.05,7.33c.143-.306.294-.6.45-.888.065-.118.126-.24.193-.354.161.062.327.122.5.179s.34.111.516.164A22,22,0,0,0,8.72,9.546c-.254-.061-.5-.123-.737-.188m1.452,2.751c.047-.365.1-.726.159-1.081.011-.067.022-.136.033-.2.342.084.7.162,1.059.235.305.061.616.12.933.173-.107,1.038-.18,2.12-.214,3.23-.348-.041-.688-.088-1.024-.138-.388-.057-.769-.118-1.139-.186.041-.693.108-1.369.193-2.031m2.314-2c-.306-.042-.6-.089-.9-.138-.349-.059-.691-.12-1.023-.186.142-.651.307-1.277.488-1.879q.165-.548.351-1.064c.019-.051.036-.105.055-.156q.392.087.8.16.381.068.775.125c-.221.975-.406,2.027-.552,3.137m-.534-7.193c.273-.246.545-.465.814-.665.056.022.112.039.163.056a8.215,8.215,0,0,0-.543.65,13.455,13.455,0,0,0-1.528,2.524c-.154-.032-.305-.067-.454-.1S9.353,5.3,9.2,5.259A13.034,13.034,0,0,1,10.2,3.946a10.129,10.129,0,0,1,1.018-1.028M9.86,2.969A12.849,12.849,0,0,0,8.213,4.928c-.061-.024-.122-.049-.177-.073l-.009,0c-.055-.022-.106-.047-.157-.072l-.009,0a12.47,12.47,0,0,1,2-1.805M6.925,5.926c.13-.176.26-.352.394-.518.056.037.114.07.171.106s.1.066.156.1a1.006,1.006,0,0,0,.093.049A17.78,17.78,0,0,0,6.233,8.8c-.192-.071-.371-.143-.545-.218-.1-.043-.2-.087-.3-.131A16.732,16.732,0,0,1,6.443,6.617q.235-.356.482-.691m-2.163.755A15.034,15.034,0,0,1,6.446,4.85,15.431,15.431,0,0,0,4.776,7.2c-.131.222-.253.453-.375.687a2.483,2.483,0,0,1-.287-.25L4.1,7.622l-.013-.015c.212-.318.439-.625.675-.926M2.853,9.963l-.025-.046c.068-.155.134-.311.207-.462.081-.17.17-.336.258-.5.166-.313.338-.623.525-.923.005.009.013.016.019.024a2.9,2.9,0,0,0,.313.349,17.175,17.175,0,0,0-.883,2.28c-.143-.233-.28-.474-.413-.719M4.425,12.33c-.1-.122-.2-.242-.3-.369q.181-.758.425-1.478c.083-.246.17-.489.264-.728.087-.222.175-.442.27-.657.1.058.2.114.3.171.184.1.374.2.578.3a20.5,20.5,0,0,0-.787,3.447c-.1-.042-.2-.088-.3-.132-.158-.178-.311-.362-.462-.55m2.562,2.517c-.153-.119-.306-.239-.456-.364.156.055.312.11.473.162.324.1.655.2,1,.295-.006.209-.008.422-.011.632-.344-.227-.678-.47-1.005-.724m3.308,2c-.156-.072-.31-.147-.464-.223-.215-.106-.428-.218-.638-.333,0-.093-.006-.183-.006-.276,0-.207,0-.413.008-.618,0-.056,0-.113,0-.168.367.079.744.152,1.128.219.346.061.7.116,1.055.166,0,.134,0,.266,0,.4,0,.435.009.865.021,1.293q-.56-.214-1.1-.461m2.773,1.015c-.155-.044-.308-.091-.462-.138-.007-.259-.008-.524-.013-.787,0-.307-.013-.61-.013-.921V15.76c.509.054,1.031.1,1.559.131v.122c0,.724.019,1.431.046,2.127q-.564-.126-1.117-.281m3.6.7q-.645-.072-1.282-.177-.02-.653-.031-1.322c-.006-.346-.013-.69-.014-1.043,0-.022,0-.042,0-.063q.606.022,1.227.026c.4,0,.8,0,1.2-.012v.049c0,.9-.023,1.777-.053,2.635q-.526-.036-1.049-.093m2.757.151c-.176,0-.35,0-.526-.005q.044-.884.058-1.8c0-.3.014-.588.014-.888v-.1c.529-.034,1.05-.078,1.558-.136,0,.08,0,.158,0,.239,0,.91-.028,1.8-.068,2.673q-.519.02-1.039.021m3.7-3.251c-.11.97-.2,1.8-.324,3.042-.326.041-.654.075-.982.1l-.158.012q.055-1.031.067-2.1c0-.169.008-.334.008-.5,0-.134,0-.263,0-.395.144-.022.282-.049.423-.072.022-.188.044-.384.069-.592q.392.226.786.44l.124-.026c0,.029-.007.059-.01.088m.184-1.586c-.014.121-.028.238-.041.351-.182.033-.36.066-.546.1-.332.054-.669.105-1.013.15-.025-.8-.075-1.576-.138-2.339-.024-.289-.044-.583-.073-.866.7-.115,1.363-.25,2-.4.044.249.083.5.12.754.093.636.164,1.289.217,1.955.014.181.029.362.04.545l-.1.019c-.154-.086-.309-.171-.463-.26m1.869,1.007c.593-.17,1.158-.357,1.688-.557.034.451.049.911.054,1.373q-.876-.381-1.741-.816m3.175,1.4-.228-.089c0-.059,0-.118,0-.177,0-.574-.023-1.138-.065-1.7-.012-.161-.019-.325-.035-.486l.183-.087a11.547,11.547,0,0,0,1.023-.563c.013.082.023.165.035.248.063.427.111.861.141,1.3.03.422.045.849.041,1.283,0,.223-.006.442-.012.662-.364-.126-.725-.259-1.085-.4m2.267.779c0-.008,0-.016,0-.023a6.142,6.142,0,0,0,.958-.96c0,.419-.022.834-.057,1.245q-.454-.124-.9-.262" transform="translate(-0.331 0)" fill="#f78d2d"/></g></svg></span>B-Alpha - Nền tảng phân phối bảo hiểm online</li>

								<li><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="34.938" height="32" viewBox="0 0 34.938 32"><defs><clipPath id="a"><rect width="34.938" height="32" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#a)"><path d="M9.83,78.362H1.546a.808.808,0,0,0-.8.805v2.348a.807.807,0,0,0,.8.8H9.83a.808.808,0,0,0,.805-.8V79.167a.808.808,0,0,0-.805-.805" transform="translate(-0.525 -55.566)" fill="#f78d2d"/><path d="M9.83,96.4H1.546a.807.807,0,0,0-.8.8v2.348a.807.807,0,0,0,.8.8H9.83a.808.808,0,0,0,.805-.8V97.2a.807.807,0,0,0-.805-.8" transform="translate(-0.525 -68.354)" fill="#f78d2d"/><path d="M49.794,78.362H41.51a.808.808,0,0,0-.805.805v2.348a.808.808,0,0,0,.805.8h8.284a.808.808,0,0,0,.805-.8V79.167a.808.808,0,0,0-.805-.805" transform="translate(-28.864 -55.566)" fill="#f78d2d"/><path d="M49.794,96.4H41.51a.807.807,0,0,0-.805.8v2.348a.808.808,0,0,0,.805.8h8.284a.808.808,0,0,0,.805-.8V97.2a.807.807,0,0,0-.805-.8" transform="translate(-28.864 -68.354)" fill="#f78d2d"/><path d="M49.794,60.329H41.51a.808.808,0,0,0-.805.805v2.347a.808.808,0,0,0,.805.805h8.284a.808.808,0,0,0,.805-.805V61.134a.808.808,0,0,0-.805-.805" transform="translate(-28.864 -42.779)" fill="#f78d2d"/><path d="M94.009,78.362H85.725a.808.808,0,0,0-.805.805v2.348a.807.807,0,0,0,.805.8h8.284a.807.807,0,0,0,.8-.8V79.167a.808.808,0,0,0-.8-.805" transform="translate(-60.216 -55.566)" fill="#f78d2d"/><path d="M94.009,96.4H85.725a.807.807,0,0,0-.805.8v2.348a.807.807,0,0,0,.805.8h8.284a.807.807,0,0,0,.8-.8V97.2a.807.807,0,0,0-.8-.8" transform="translate(-60.216 -68.354)" fill="#f78d2d"/><path d="M94.009,60.329H85.725a.808.808,0,0,0-.805.805v2.347a.808.808,0,0,0,.805.805h8.284a.808.808,0,0,0,.8-.805V61.134a.808.808,0,0,0-.8-.805" transform="translate(-60.216 -42.779)" fill="#f78d2d"/><path d="M94.009,42.3H85.725a.808.808,0,0,0-.805.805v2.347a.807.807,0,0,0,.805.806h8.284a.807.807,0,0,0,.8-.806V43.1a.808.808,0,0,0-.8-.805" transform="translate(-60.216 -29.992)" fill="#f78d2d"/></g></svg></span>Nguồn khách hàng không giới hạn với DataCenter</li>

								<li><span><svg xmlns="http://www.w3.org/2000/svg" width="32" height="39.011" viewBox="0 0 32 39.011"><path d="M35.7,20.185,24.634,31.251,13.568,20.185H10.5L23.547,33.237V49.864h-5.8v2.173H31.517V49.864h-5.8V33.237L38.773,20.185Z" transform="translate(-6.773 -13.026)" fill="#f78d2d"/><path d="M3.2,0,0,3.2,1.106,4.3,3.2,2.21,12.082,11.1l.04-.041,5.738,5.739,7.652-7.652H12.339L4.3,1.105Z" transform="translate(0 0)" fill="#f78d2d"/></svg></span>Không áp lực doanh số, doanh số được tích luỹ theo thời gian</li>

								<li><span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="28.957" height="32.593" viewBox="0 0 28.957 32.593"><defs><clipPath id="a"><rect width="28.957" height="32.593" fill="#f78d2d"/></clipPath></defs><g clip-path="url(#a)"><path d="M24.651,13.285c1.537,3.654,4.465,5.643,6.419,5.643,1.919,0,4.608-1.922,6.167-5.529a16.812,16.812,0,0,0,.933-3.089,12.3,12.3,0,0,0,.4-3.4C38.565,3.161,35.209,0,31.07,0s-7.494,3.161-7.494,6.908a16.346,16.346,0,0,0,.174,2.541,14.9,14.9,0,0,0,.9,3.836" transform="translate(-16.591 0)" fill="#f78d2d"/><path d="M27.732,71.391c-1.5-1.6-5.711-1.2-7.793-2.3-.926,1.732-.938,4.578-2.475,4.578a1.32,1.32,0,0,1-.206-.016C14.942,73.3,16.1,67.4,14.479,67.4S14.016,73.3,11.7,73.652a1.324,1.324,0,0,1-.206.016c-1.537,0-1.549-2.846-2.475-4.578-2.081,1.1-6.293.7-7.786,2.3C.422,72.259,0,77.633,0,80.021H28.958c0-2.388-.419-7.762-1.226-8.63" transform="translate(0 -47.428)" fill="#f78d2d"/></g></svg></span>Hơn 1000 chuyên gia sẵn sàng tư vấn hỗ trợ cho bạn</li>

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
  height: calc(100vw * (500/(1280 + 0)))!important;
}
@media (max-width: 960px) {
	.box-service-2 .bg-info1{
		height: calc(100% - 0px);
	}
	.box-service-2 .col-image1{
	    clip-path: polygon(0 0, calc(100% - 0px) 0, 100% 100%, 0 100%);
	}
	.slide-categories .banner-1{
	  height: calc(100vw * (700/(1280 + 0)))!important;
	}
}

</style>
</body>
</html>
