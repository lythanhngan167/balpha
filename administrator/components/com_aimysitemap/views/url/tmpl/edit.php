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
 defined( '_JEXEC' ) or die(); require_once( JPATH_COMPONENT . '/helpers/compat.php' ); $fields = array( 'id', 'title', 'url', 'priority', 'changefreq', 'lang', 'state', 'lock', 'mtime' ); try { JHtml::_( version_compare( JVERSION, '3.3.0', 'lt' ) ? 'behavior.framework' : 'behavior.core' ); JHtml::_( 'formbehavior.chosen', 'select' ); } catch ( Exception $e ) { } ?>

<form action="<?php
 echo JRoute::_( 'index.php?option=com_aimysitemap&layout=edit&' . 'id=' . (int) $this->item->id ); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row-fluid">
        <div class="span-12 form-horizontal">
        <fieldset>
            <?php
 echo JHtml::_( AimySitemapCompatHelper::getJHtmlBootstrapMethodName( 'startPane' ), 'myTab', array( 'active' => 'details' ) ); echo JHtml::_( AimySitemapCompatHelper::getJHtmlBootstrapMethodName( 'addPanel' ), 'myTab', 'details', JText::sprintf( 'AIMY_SM_EDIT_URL_X', $this->item->id, true ) ); ?>
            <?php foreach ( $fields as $field ) : ?>
            <div class="control-group">
                <div class="control-label"><?php
 echo $this->form->getLabel( $field ); ?></div>
                <div class="controls"><?php
 echo $this->form->getInput( $field ); ?></div>
            </div>
            <?php endforeach; ?>
            <?php
 echo JHtml::_( AimySitemapCompatHelper::getJHtmlBootstrapMethodName( 'endPanel' ) ); ?>

            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_( 'form.token' ); ?>

            <?php
 echo JHtml::_( AimySitemapCompatHelper::getJHtmlBootstrapMethodName( 'endPane' ) ); ?>
        </fieldset>
    </div>
</form>

<?php
 
