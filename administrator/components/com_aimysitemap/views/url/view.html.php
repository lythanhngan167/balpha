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
 defined( '_JEXEC' ) or die(); class AimySitemapViewUrl extends JViewLegacy { protected $item; protected $form; public function display( $tpl = null ) { $this->item = $this->get( 'Item' ); $this->form = $this->get( 'Form' ); $errors = $this->get( 'Errors' ); if ( is_array( $errors ) && count( $errors ) ) { JError::raiseError( 500, implode( "\n", $errors ) ); return false; } $this->addToolbar(); parent::display( $tpl ); } protected function addToolbar() { JFactory::getApplication()->input->set( 'hidemainmenu', true ); JToolbarHelper::title( JText::_( 'AIMY_SM_EDIT_URL' ), '' ); JToolbarHelper::save( 'url.save' ); if( empty( $this->item->id ) ) { JToolbarHelper::cancel( 'url.cancel' ); } else { JToolbarHelper::cancel( 'url.cancel', 'JTOOLBAR_CLOSE' ); } } } 
