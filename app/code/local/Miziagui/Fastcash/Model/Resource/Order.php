<?php

class Miziagui_Fastcash_Model_Resource_Order extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct() {
        $this->_init('fastcash/order','id');
    }

}