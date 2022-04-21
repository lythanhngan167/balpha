<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:50 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;
use api\model\libs\simple_html_dom;
/**
 * @OA\Schema(required={"id", "title"}, @OA\Xml(name="ContentBiz"))
 */
class ContentBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;
    /**
     * @OA\Property(example="title")
     * @var string
     */
    public $title;
    /**
     * @OA\Property(example="introtext")
     * @var string
     */
    public $introtext;
    /**
     * @OA\Property(example="fulltext")
     * @var string
     */
    public $fulltext;

    /**
     * @OA\Property(example="publish_up")
     * @var string
     */
    public $publish_up;

    /**
     * @OA\Property(example="hit")
     * @var int
     */
    public $hits;

    /**
     * @OA\Property(example="images")
     * @var string
     */
    public $images;

    /**
     * @OA\Property(example="show_toolbar")
     * @var boolean
     */
    public $show_toolbar;

    public function setAttributes($data){
        $data['publish_up'] = date('d __ m Y', strtotime($data['publish_up']));
        $data['publish_up'] = str_replace('__', 'tháng', $data['publish_up']);
        $tmp = json_decode($data['images']);
        $data['images'] = null;
        if($tmp->image_intro){
            $data['images'] = \JURI::root().$tmp->image_intro;
        }
        $hideToolbar = array(177, 178, 179, 180);
        $data['show_toolbar'] = !in_array($data['catid'], $hideToolbar);

        parent::setAttributes($data);
        $this->_prepareImageUrl();
    }

    private function _prepareImageUrl(){
        $this->introtext = str_replace('="images', '="'.\JURI::root().'images', $this->introtext);
        $this->fulltext = str_replace('="images', '="'.\JURI::root().'images', $this->fulltext);
        $this->introtext = nl2br($this->introtext);
        $this->fulltext = nl2br($this->fulltext);
    }

    public function fixImageUrl($content){
        $html = new simple_html_dom();
        $html->load($content);
        $elements = $html->find('img');
        foreach ($elements as $k => $value) {
            $tmp = explode(':', $value->src);
            $value->with = null;
            $value->height = null;
            switch (strtolower($tmp[0])){
                case 'http':
                case 'https':
                    break;

                default:
                    $value->src = \JURI::root().$value->src;
            }
        }
        $content = $html->save();
        $html->clear();
        return $content;

    }
}
