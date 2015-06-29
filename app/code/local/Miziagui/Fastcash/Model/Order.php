<?php

class Miziagui_Fastcash_Model_Order extends Mage_Core_Model_Abstract {
    
    const STATUS_CAPTURED = 'captured';
    const STATUS_CAPTUREPAYMENT = 'capture payment';
    const STATUS_CONFIRM = 'confirm';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_DENIED = 'denied';
    const STATUS_CREATED = 'created';
    const STATUS_ERROR = 'error';
    const STATUS_PROCESSING = 'processing';
    
    public function _construct() {
        $this->_init('fastcash/order');
    }

}