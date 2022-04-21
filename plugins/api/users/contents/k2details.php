<?php
/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */
ini_set("allow_url_fopen", 1);
defined('_JEXEC') or die('Restricted access');

use api\model\dao\ContentDao;
use api\model\libs\simple_html_dom;

class UsersApiResourceK2details extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'k2details/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }

    public function post()
    {

        $data = $this->getRequestData();
        if(isset($data['id'])){
            $host = JURI::base();
            $path = "index.php?option=com_k2&view=item&id=".$data['id']."&format=json";

            //  Initiate curl
            $ch = curl_init();
            // Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the url
            curl_setopt($ch, CURLOPT_URL,$host.$path);
            // Execute
            $json = curl_exec($ch);
            // Closing
            curl_close($ch);

            $result = json_decode($json);

             // Fix url for image in content - Thai
             if($result->item->fulltext != "") {
                $result->item->fulltext = $this->fixImageUrl($result->item->fulltext);

            } else {
                $result->item->introtext = $this->fixImageUrl($result->item->introtext);
            }
            // Fix url for image in content - Thai
            foreach($result->item->extra_fields as $extra_field) {
                $extra_field->value = $this->fixImageUrl($extra_field->value);
            }
            foreach($result->items as $item) {
                $item->image = $result->site->url.$item->image;
            }

            $this->plugin->setResponse($result);
        } else {
            ApiError::raiseError('400', 'Invalid request');
            return false;
        }

    }

    public function fixImageUrl($content){

        $html = new DOMDocument();
        // $html->loadHTML($content , LIBXML_HTML_NODEFDTD);
        $domContent = $html->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES' , 'UTF-8'));
        $tags = $html->getElementsByTagName('img');

        if(count($tags) > 0)
        {
            for ($i = 0 ; $i < count($tags) ; $i++){
                $tag = $tags->item($i);
                $url = $tag->getAttribute('src');
                if(mb_substr($url, 0, 11) == '/biznetweb/'){ 
                    $url = substr($url, 10);
                }

                $tag->setAttribute('src', \JURI::root().$url);
            }
        $content = $html->saveHTML();
        }

        return $content;
    }

}
