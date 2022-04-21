<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 7:41 AM
 */

namespace api\model;


abstract class AbtractForm extends AbtractBiz
{
    private $_errors;

    public function rule()
    {
        return array();
    }

    public function validate()
    {
        $this->_errors = array();
        $rule = $this->rule();
        if ($rule) {
            $validator = new Validator($this->toArray(), array(), null, JPATH_PLUGINS.'/api/model/lang');
            $validator->rules($rule);
            if (!$validator->validate()) {
                $this->_errors = $validator->errors();
                return false;
            }
        }
        return true;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function getFirstError(){
        if($this->_errors){
            $error = reset($this->_errors);
            return reset($error);
        }
        return null;
    }

}