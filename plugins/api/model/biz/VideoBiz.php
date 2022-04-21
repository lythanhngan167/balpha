<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 11:27 AM
 */

namespace api\model\biz;

use api\model\AbtractBiz;

/**
 * @OA\Schema(required={"id", "username"}, @OA\Xml(name="OrderBiz"))
 */
class VideoBiz extends AbtractBiz
{
    /**
     * @OA\Property(format="int64")
     * @var string
     */
    public $id;
    /**
     * @OA\Property(example="title")
     * @var string
     */
    public $title;
    /**
     * @OA\Property(example="thumb")
     * @var string
     */
    public $thumb;
    

    public function setAttributes($data)
    {
        if(@$data['attribs']){
            $tmp = json_decode($data['attribs']);
            if(@$tmp->video){
                $query_str = parse_url($tmp->video, PHP_URL_QUERY);
                parse_str($query_str, $query_params);
                $data['id'] = $query_params['v'];
                $data['thumb'] = 'http://i3.ytimg.com/vi/'.$data['id'].'/hqdefault.jpg';
            }
        }

        parent::setAttributes($data);
    }
}
