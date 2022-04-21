<?php
/*
 * Copyright (c) 2017-2020 Aimy Extensions, Netzum Sorglos Software GmbH
 * Copyright (c) 2014-2017 Aimy Extensions, Lingua-Systems Software GmbH
 *
 * https://www.aimy-extensions.com/
 *
 * License: GNU GPLv2, see LICENSE.txt within distribution and/or
 *          https://www.aimy-extensions.com/software-license.html
 */
 defined( '_JEXEC' ) or die(); $btns = array( 'dashboard' => array( 'route' => 'option=com_aimysitemap', 'text' => 'AIMY_SM_LINK_DASHBOARD', 'icon' => 'book' ), 'crawl' => array( 'route' => 'option=com_aimysitemap&amp;view=crawl', 'text' => 'AIMY_SM_LINK_CRAWL', 'icon' => 'go' ), 'manage' => array( 'route' => 'option=com_aimysitemap&view=urls', 'text' => 'AIMY_SM_LINK_MANAGE', 'icon' => 'list' ), 'notify' => array( 'route' => 'option=com_aimysitemap&amp;view=notify', 'text' => 'AIMY_SM_LINK_NOTIFY', 'icon' => 'megaphone' ), 'robotstxt' => array( 'route' => 'option=com_aimysitemap&view=robotstxt', 'text' => 'AIMY_SM_LINK_ROBOTSTXT', 'icon' => 'edit-file' ), 'options' => array( 'route' => 'option=com_config&view=component&component=com_aimysitemap', 'text' => 'JOPTIONS', 'icon' => 'wrench' ) ); ?>

<div class="clearfix row-fluid" id="aimy-footer">
    <div class="span4">
        <a href="https://www.aimy-extensions.com/"><img src="<?php
 echo JUri::root() . '/media/com_aimysitemap/aimy-logo_100x50.png'; ?>" alt="Aimy Extensions Logo" width="100" height="50" /></a>
        <br/>
        Aimy Sitemap  Version 27.3
        <br/>
        <a href="https://www.aimy-extensions.com/joomla/sitemap.html" target="_blank">https://www.aimy-extensions.com/joomla/sitemap.html</a>
    </div>
    <div class="span4">
        <p>
            <strong>Voice your opinion:</strong>
            <br/>
            Please take a minute and
            <br/>
            <a href="<?php
 echo 'https://extensions.joomla.org/extensions/extension/', 'structure-a-navigation/site-map/aimy-sitemap'; ?>" target="_blank"><strong>rate us</strong></a>
            <br/>
            on Joomla!'s Extensions Directory!
        </p>
    </div>
    <div class="span4">
        <strong>Need help?</strong>
        <br/>
        <p>
            Have a look at the Aimy Sitemap's
            <a href="https://www.aimy-extensions.com/joomla/sitemap.html#tab-faq" target="_blank">FAQ</a>,
            <br/>
            the
            <a href="https://www.aimy-extensions.com/joomla/sitemap.html#user-manual" target="_blank">Online User
                Manual</a>
            or
            <a href="http://static.aimy-extensions.com/images/products/sitemap/com-aimy-sitemap.pdf?r=27.3">Download the PDF Version</a>.
        </p>
    </div>
    <div class="clear span12" id="aimy-footer-quicklinks">
    <?php foreach ( $btns as $name => $btn ) : ?>
        <a href="<?php
 echo JRoute::_( 'index.php?' . $btn[ 'route' ] ); ?>" class="btn btn-secondary"><?php
 if ( isset( $btn[ 'icon' ] ) ) { echo '<i class="aimy-icon-', $btn[ 'icon' ], '"></i>&nbsp;'; } echo JText::_( $btn[ 'text' ] ); ?></a>
    <?php endforeach; ?>
    </div>

</div><!-- /.row -->

<?php
 
