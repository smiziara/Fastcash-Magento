<?php

class Miziagui_Fastcash_Helper_Payment extends Mage_Core_Helper_Abstract
{
    public $order;
    public $fcOrder;
    
    public function loadOrder($id){
        $fastcash = Mage::getModel('fastcash/order')->load($id);
        if($fastcash->hasData()){
            $this->order = Mage::getModel('sales/order')->load($fastcash->getOrderId());
            $this->fcOrder = $fastcash;
        }else{
            return false;
        }
        return $this;
    } 
    
    public function getConfigAuth(){
        return new Varien_Object(Mage::getStoreConfig('fastcash/auth'));
    }    
    
    public function getOrder(){
        return $this->order;
    }     

    public function getFcOrder(){
        return $this->fcOrder;
    }     
    
    public function getTID(){
        return $this->fcOrder->getTransactionId();
    }         
    
    public function getPrice(){
        return number_format($this->getOrder()->getGrandTotal(), 2, ',', '');
    }             

    public function getProductName(){
        return 'Produto Teste';
    }             

    public function getCellphone(){
        return str_replace(' ', '', preg_replace('/[()-]*/', '', $this->getOrder()->getBillingAddress()->getCellPhone()));
    }                 

    public function getCustomerName(){
        return trim(utf8_decode($this->getOrder()->getBillingAddress()->getFirstname()).' '.utf8_decode($this->getOrder()->getBillingAddress()->getLastname()));
    }    
    
    public function getCustomerEmail(){
        return trim($this->getOrder()->getBillingAddress()->getEmail()) ? trim(utf8_decode($this->getOrder()->getBillingAddress()->getEmail())) : trim(utf8_decode($this->getOrder()->getCustomerEmail()));
    }     

    public function getCustomerCpf(){
        
        $cpf = $this->getOrder()->getCustomerTaxvat();
        if(!$cpf){
            $customer = Mage::getModel('customer/customer')->load($this->getOrder()->getCustomerId());
            $customerCpfs = explode(",", Mage::getStoreConfig('fastcash/auth/cpf'));
            $cpf = null;

            foreach ($customerCpfs as $customerCpf) {
                $metodo = 'get' . ucfirst($customerCpf);
                if (!$cpf && $customer->$metodo()) {
                    $cpf = (string) preg_replace('/[^0-9]/', '', $customer->$metodo());
                }
            }        
        }
        $cpf = str_replace(array('.','-',' '), array('', '', ''), $cpf);
        return $cpf;
    }    
    
    public function getMethods(){
        
        $methods = explode(",",Mage::getStoreConfig('fastcash/fastcash_payment/methods'));
        if(sizeof($methods)==2){
            return "PaymentOptions=transference&ShowHeader=true";
        }elseif($methods[0]=='transference'){
            return "PaymentOptions=transference&ShowHeader=false";
        }elseif($methods[0]=='deposit'){
            return "PaymentOptions=deposit&ShowHeader=false";
        }
        
    } 
            
    
}