<?php

namespace api\model\form\erp;
use api\model\AbtractForm;

class ERPUpdateBlockForm extends AbtractForm
{
    public $id_biznet;
    public $block;
    public $is_production;

    public function rule()
    {
        return array(
            'required' => array(
                'id_biznet',
                'block',
                'is_production'
            ),
            'boolean'=> array (
                'is_production'
            )
        );
    }
}
