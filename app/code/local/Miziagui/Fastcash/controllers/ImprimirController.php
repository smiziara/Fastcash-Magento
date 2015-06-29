<?php

class Miziagui_Fastcash_ImprimirController extends Mage_Core_Controller_Front_Action{
    
    public function depositoAction(){
         
        $order_id = $this->getRequest()->getParam('id');
        $customer_id = $this->getRequest()->getParam('ci');
        
        $mOrder = Mage::getModel('sales/order')->load( $order_id );
        /*@var $mOrder Mage_Sales_Model_Order*/
        
        if( $mOrder->hasData() ){
            if( $customer_id != $mOrder->getCustomerId() ){
                $this->_redirect('no-route');            
                return;
            }
            
            if( $mOrder->getPayment()->getMethod() == 'fastcash_boleto'){
                $fastcashItem = Mage::getModel('fastcash/order')->load( $order_id, 'order_id' );
                if( $fastcashItem->hasData() && $fastcashItem->getType() == 'boleto' ){                    
                    if($fastcashItem->getStatus() == Miziagui_Fastcash_Model_Order::STATUS_CREATED){
                        $itemId = $fastcashItem->getId();
                        $fastcashItem->clearInstance();      
                        // faz primeira tentativa de gerar o boleto
                        try{
                            $mOrder->getPayment()->getMethodInstance()->authorize($mOrder->getPayment(), $mOrder->getGrandTotal());
                            $fastcashItem = Mage::getModel('fastcash/order')->load( $itemId );                                                        
                            //$fastcashItem->setStatus(((Mage::getStoreConfig('fastcash/fcontrol/active') && Mage::getStoreConfig('indexa/'.$mOrder->getPayment()->getMethod().'/mc_fraud_check') ) ? Indexa_Gwap_Model_Order::STATUS_AUTHORIZED : Indexa_Gwap_Model_Order::STATUS_CAPTUREPAYMENT));
                            //$fastcashItem->setErrorCode(null);
                            //$fastcashItem->setErrorMessage(null);
                            //$fastcashItem->setTries(0);
                            //$fastcashItem->setAbandoned(0);
                            //$fastcashItem->setUpdatedAt(Mage::getModel('core/date')->date("Y-m-d H:i:s"));
                            //$fastcashItem->save();
                            //$log->add($gwapItem->getOrderId(), 'Payment', 'authorize()', Indexa_Gwap_Model_Order::STATUS_AUTHORIZED, 'Boleto gerado');
                            
                            //redirectiona para url do boleto
                            if( $fastcashItem->getStatus() == Miziagui_Fastcash_Model_Order::STATUS_CAPTUREPAYMENT ){
                                //Tratamento para bug do chrome, onde o boleto fica cortado (resolução baixa).
                                if(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("CHROME"))){
                                    Mage::register('url_boleto', $fastcashItem->getInfo());
                                    $this->loadLayout();
                                    $this->_initLayoutMessages('customer/session');
                                    $this->renderLayout();                        
                                 } else {
                                    $this->_redirectUrl($fastcashItem->getInfo());                             
                                 }
                                return ;
                            }
                        }catch (Exception $e) {
                            //Salva log
                            $log->add($fastcashItem->getOrderId(), '+ Conversao', 'authorize()', Miziagui_Fastcash_Model_Order::STATUS_ERROR, 'Ocorreu um erro', $e->getMessage());
                            $fastcashItem = Mage::getModel('gwap/order')->load( $itemId );
                            $fastcashItem->setUpdatedAt(Mage::getModel('core/date')->date("Y-m-d H:i:s"));
                            $fastcashItem->save();
                            
                            $url = Mage::getUrl('sales/order/view', array('order_id'=>$order_id));
                            $linkMessage = Mage::helper('gwap')->__('Clique aqui');
                                
                            $this->getResponse()->setBody( sprintf( Mage::helper('gwap')->__('Não foi possível gerar seu boleto no momento. Você pode reimprimir acessando o detalhe de seu pedido. %s.'), '<a href="'.$url.'" target="_blank" class="imprimir_boleto">'.$linkMessage.'</a>' ) );
                            return ;    
                        }
                    //redirectiona para url do boleto                             
                    }elseif($fastcashItem->getStatus() == Miziagui_Fastcash_Model_Order::STATUS_CAPTUREPAYMENT){
                        //Tratamento para bug do chrome, onde o boleto fica cortado (resolução baixa).
                        if(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("CHROME"))){
                            Mage::register('url_boleto', $fastcashItem->getInfo());
                            $this->loadLayout();
                            $this->_initLayoutMessages('customer/session');
                            $this->renderLayout();                        
                         } else {
                            $this->_redirectUrl($fastcashItem->getInfo());                             
                         }
                        
                        return ;
                    }
                    
                }
            }
        }    
        
        $this->_redirect('no-route');
    }
    
}