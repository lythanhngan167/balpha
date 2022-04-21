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
JFactory::getApplication()->input->set('tmpl', 'component');
$doc = JFactory::getDocument();

JHTML::stylesheet(JURI::root() . 'components/com_api/libraries/swagger_dist/swagger-ui.css');


JHTML::script(JURI::root() . 'components/com_api/libraries/swagger_dist/swagger-ui-bundle.js');
JHTML::script(JURI::root() . 'components/com_api/libraries/swagger_dist/swagger-ui-standalone-preset.js');

$doc_path = JURI::root() . 'components/com_api/documentation/api-docs.json';
$initjs = <<<EOT
    window.onload = function() {
      // Begin Swagger UI call region
      const ui = SwaggerUIBundle({
        url: "$doc_path",
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        plugins: [
          SwaggerUIBundle.plugins.DownloadUrl
        ],
        layout: "StandaloneLayout"
      })
      // End Swagger UI call region

      window.ui = ui
    }
EOT;

$doc->addScriptDeclaration($initjs);
?>
<div id="swagger-ui"></div>
<style>
    .renderedMarkdown, .parameters-col_description, .response-col_description{
        min-width: 500px;
    }
    .response-col_links{ min-width: 150px;}
</style>
