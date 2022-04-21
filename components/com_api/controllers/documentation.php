<?php
/**
 * @package com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link http://techjoomla.com
 * Work derived from the original RESTful API by Techjoomla (https://github.com/techjoomla/Joomla-REST-API)
 * and the com_api extension by Brian Edgerton (http://www.edgewebworks.com)
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class ApiControllerDocumentation extends ApiController
{


    public function display($cachable = false, $urlparams = array())
    {
        parent::display();
    }

    public function make_document()
    {
        require(JPATH_COMPONENT . "/vendor/autoload.php");
        $jsonFile = JPATH_COMPONENT.'/documentation/api-docs.json';
        $openapi = \OpenApi\scan(JPATH_PLUGINS.'/api');
        /*$openapi->__set('host', 'sonlv.net');
        $openapi->__set('basePath', '/bca/web/');
        $openapi->__set('schemes', 'https');
        $openapi->__set('swagger', '2.0');*/
        $fp = fopen($jsonFile, 'w');
        $content =  $openapi->toJson();
        fwrite($fp, $content);
        fclose($fp);
    }

}
