<?php

class Miziagui_Fastcash_CheckoutController extends Mage_Core_Controller_Front_Action
{
    public function failureAction(){
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');        
        $this->renderLayout();
    }    
     
    public function successAction(){
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');        
        $this->renderLayout();
    }    
    
}
 