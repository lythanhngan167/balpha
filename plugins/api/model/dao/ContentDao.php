<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:53 AM
 */

namespace api\model\dao;

use api\model\AbtractDao;
use api\model\biz\ContentBiz;
use api\model\biz\VideoBiz;

class ContentDao extends AbtractDao
{
    public $select = array(
        'id',
		'catid',
        'title',
        'introtext',
        'IF(`fulltext` <> \'\', `fulltext` , introtext) AS `fulltext`',
        'publish_up',
        'images',
        'hits'
    );

    public function getTable()
    {
        return '#__content';
    }

    public function getContent($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'state = 1'
            )
        );
        if (isset($params['where']) && $params['where']) {
            foreach ($params['where'] as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        if($params){
            foreach ($params as $k => $item) {
                if($k === 'where'){
                    continue;
                }
                $paramsDefault[$k] = $item;
            }
        }
        $list = array();
        $result = $this->getList($paramsDefault);

        if ($result) {
            foreach ($result as $item) {
                $biz = new ContentBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }

    public function getContentInfo($params = array())
    {
        $paramsDefault = array(
            'no_quote' => true,
            'select' => implode(',', $this->select),
            'where' => array(
                'state = 1'
            )
        );
        if ($params) {
            foreach ($params as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        $result = $this->get($paramsDefault);
        if ($result) {
            $biz = new ContentBiz();
            $biz->setAttributes($result);
            return $biz;
        }
        return null;
    }

    public function getVideos($params = array()){
        $paramsDefault = array(
            'select' => array(
                'title',
                'attribs'
            ),
            'where' => array(
                'state = 1'
            )
        );
        if ($params) {
            foreach ($params as $item) {
                $paramsDefault['where'][] = $item;
            }
        }
        $result = $this->getList($paramsDefault);
        $list = array();
        if ($result) {
            foreach ($result as $item) {
                $biz = new VideoBiz();
                $biz->setAttributes($item);
                $list[] = $biz;
            }
        }
        return $list;
    }
}
