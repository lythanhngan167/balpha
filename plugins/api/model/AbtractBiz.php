<?php
/**
 * Created by PhpStorm.
 * User: lvson
 * Date: 4/26/2019
 * Time: 10:55 AM
 */

namespace api\model;


class AbtractBiz
{

    public function setAttributes($data)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }
        if (!is_array($data)) {
            return;
        }

        foreach ($data as $key => $property) {
            if (!property_exists($this, $key)) {
                unset($data[$key]);
            }
        }
        if (!empty($data)) {
            $this->_setAttributes($data);
        }
    }

    public function toArray()
    {

        return get_object_vars($this);
    }

    private function _setAttributes($properties)
    {
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
        if (property_exists($this, 'price_formated') && property_exists($this, 'price')) {
            $this->price_formated = number_format($this->price, 0, ",", ".").' '.BIZ_XU;
        }

        return $this;
    }
}
