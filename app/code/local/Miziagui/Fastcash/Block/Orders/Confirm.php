<?php

class Miziagui_Fastcash_Block_Orders_Confirm extends Mage_Core_Block_Template
{
    public function _getHelper(){
        return Mage::helper('fastcash/payment');        
    }    
    
    public function loadData(){
        $id = $this->getRequest()->getParam('fcorder',0);        
        return $this->_getHelper()->loadOrder($id);        
    }
    
}
