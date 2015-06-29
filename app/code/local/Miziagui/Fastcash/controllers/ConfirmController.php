<?php
require_once Mage::getBaseDir('lib')."/Fastcash/Fastcash.php";

class Miziagui_Fastcash_ConfirmController extends Mage_Core_Controller_Front_Action
{
    public function indexAction(){
        $orderId = $this->getRequest()->getParam('fcorder');
        if($orderId){
            Mage::register('fastcash_confirm_id', $orderId);
        } else {
            Mage::getSingleton('customer/session')->addError("Erro: Pedido não encontrado.");
            $this->_redirect('customer/account/holdedorders/');                
        }                        
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function ordersAction(){                       
        $this->loadLayout();
        $this->renderLayout();
    }    
    
    public function sendAction(){
        if(Mage::getSingleton('customer/session')->isLoggedIn()){
            $orderId = $this->getRequest()->getParam('fcorder');
            $confirm = $this->getRequest()->getParams();
            if($orderId){
                $order = Mage::getModel('sales/order')->load($orderId);
                $order->getPayment()->getMethodInstance()->prepareCapture($order,$confirm);
                Mage::getSingleton('customer/session')->addSuccess("Pedido de confirmação enviado com sucesso.");
                $this->_redirect('customer/account/holdedorders/r/1');  
            } else {
                Mage::getSingleton('customer/session')->addError("Erro: Pedido não encontrado.");
                $this->_redirect('customer/account/holdedorders/');                
            }
        }
    } 
    
    public function collectAction(){
        
        $params = $this->getRequest()->getParams();

        header("Content-Type: text/xml");
        header("Cache-Control: no-cache, must-revalidate, proxy-revalidate");

        $function = null;
        $handler = null;

        //        if (!Fastcash\Security::VerifyIP($_SERVER["REMOTE_ADDR"]))
        //        {
        //            die($_SERVER["REMOTE_ADDR"]);
        //        }

        if (isset($params["function"])){
            $function = $params["function"];
        } else {
            die();
        }

        switch($function)
        {
            case "credit":
            {
                $handler = new Fastcash\OnlineCredit();
                $handler->SetCallback(get_class($this),"OnOnlineCreditReceived");

                break;
            }
            case "credit-consult":
            {
                $handler = new Fastcash\CreditConsult();
                $handler->SetCallback(get_class($this),"OnCreditConsultReceived");

                break;
            }
            case "transaction-cancelation":
            {
                $handler = new Fastcash\Cancelation();
                $handler->SetCallback(get_class($this),"OnCancelationReceived");

                break;
            }
        }

        if ($handler != null){
            $handler->Listen();
        }        
        
    }    
     
    public function collect2Action(){
        
        $params = $this->getRequest()->getParams();

        header("Content-Type: text/xml");
        header("Cache-Control: no-cache, must-revalidate, proxy-revalidate");

        $function = null;
        $handler = null;

        if (isset($params["function"])){
            $function = $params["function"];
        } else {
            die();
        }

        switch($function)
        {
            case "credit":
            {
                $handler = new Fastcash\OnlineCredit();
                $handler->SetCallback(get_class($this),"OnOnlineCreditReceived");

                break;
            }
            case "credit-consult":
            {
                $handler = new Fastcash\CreditConsult();
                $handler->SetCallback(get_class($this),"OnCreditConsultReceived");

                break;
            }
            case "transaction-cancelation":
            {
                $handler = new Fastcash\Cancelation();
                $handler->SetCallback(get_class($this),"OnCancelationReceived");

                break;
            }
        }

        if ($handler != null){
            $handler->Listen();
        }        
        
    }    
  
    /**
    *   Callback function for the OnlineCredit component.
    *   @param $sender The OnlineCredit class instance reference.
    *   @param $tid Your transaction identifier, sent with the DynamicTransaction call.
    *   @param $prodId The Fastcash product id used at the DynamicTransaction.
    *   @param $quant The quant (quantity) parameter sent with the DynamicTransaction call.
    *   @param $valueReceived The value that we received as payment for the transaction. Validate this parameter to double check that the price was not changed at the communication.
    *   @param $custom The custom parameter, sent optionally with the DynamicTransaction call.
    */
    static public function OnOnlineCreditReceived($sender, $tid, $prodId, $quant, $valueReceived, $custom){
        //TODO: Implement your logic for the OnlineCredit function:
        //Validate the parameters, and trigger your function that deliver the product to the client.
        $gateway = Mage::getModel('fastcash/order')->load($tid, 'transaction_id');
        try{
            if($gateway->getStatus() == Miziagui_Fastcash_Model_Order::STATUS_CREATED
                    || $gateway->getStatus() ==  Miziagui_Fastcash_Model_Order::STATUS_CONFIRMED){
                
                $gateway->setStatus(Miziagui_Fastcash_Model_Order::STATUS_CAPTURED);
                $order = Mage::getModel('sales/order')->load($gateway->getOrderId());
                Mage::log('OnOnlineCreditReceived: '.$order->getId(),null,'fastcash_collect.log');
                //Gera invoice e manda e-mail
                $invoice = $order->prepareInvoice()->register();
                $invoice->setEmailSent(false);
                $invoice->setState(Mage_Sales_Model_Order_Invoice::STATE_PAID);
                $invoice->getOrder()->setTotalPaid($order->getGrandTotal());
                $invoice->getOrder()->setBaseTotalPaid($order->getBaseGrandTotal());
                $invoice->getOrder()->setCustomerNoteNotify(true);
                $invoice->getOrder()->setIsInProcess(true);
                Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder())->save();
                $invoice->sendEmail(true, 'Pedido realizado com sucesso');
                
                //Altera o status do pedido
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();        
                $gateway->save();
            }
        } catch(Exception $e){
            Mage::log('Erro: Fastcash OnOnlineCreditReceived - Pedido: '.$gateway->getOrderId().' - Msg: '.$e->getMessage(),null,'fastcash_error.log');
            return array(false, $e->getMessage());
        }
        
        //Return true/false or an array(false, "Error message")
        return true;
    }

    /**
    *   Callback function for the CreditConsult component.
    *   @param $sender The CreditConsult class instance reference.
    *   @param $tid Your transaction identifier, sent with the DynamicTransaction call.
    *   @param $custom The custom parameter, sent optionally with the DynamicTransaction call.
    */
    static public function OnCreditConsultReceived($sender, $tid, $custom){
        //TODO: Implement your logic for the CreditConsult function:
        //Check your system to verify if the realtime and most updated status of the $tid.
        //We call this function when needed to double check the delivery.
        
        $gateway = Mage::getModel('fastcash/order')->load($tid, 'transaction_id');
        try{
            if($gateway->getStatus() == 'captured'){
                return true;
            }
        } catch(Exception $e){
            Mage::log('Erro: Fastcash OnCreditConsultReceived - Pedido: '.$gateway->getOrderId().' - Msg: '.$e->getMessage(),null,'fastcash_error.log');
            return array(false, $e->getMessage());
        }
        
        //Return true/false or an array(false, "Error message")
        return false;
    }

    /**
    *   Callback function for the Cancelation component.
    *   @param $sender The Cancelation class instance reference.
    *   @param $tid Your transaction identifier, sent with the DynamicTransaction call.
    *   @param $custom The custom parameter, sent optionally with the DynamicTransaction call.
    *   @param $source The source of the cancelation. 0 for the User, 1 for Fastcash system.
    *   @param $reason The reason of the cancelation, if available.
    */
    static public function OnCancelationReceived($sender, $tid, $custom, $source, $reason){
        //TODO: Implement your logic for the Cancelation function:
        //Check to see if the $tid has now yet been approved by the OnlineCredit
        //If its still pending, cancel the $tid.
        //This function may be called more than once, so ensure that it will not cause any problems.

        $gateway = Mage::getModel('fastcash/order')->load($tid, 'transaction_id');
        try{
            if($gateway->getStatus() != 'captured'){
                $order = Mage::getModel('sales/order')->load($gateway->getOrderId());
                //Altera o status do pedido
                $order->cancel()->save();
                
                $gateway->setStatus(Mage_Sales_Model_Order::STATE_CANCELED);
                $gateway->save();
                
                Mage::log('OnCancelationReceived: '.$order->getId(),null,'fastcash_collect.log');                
                return true;
            }
        } catch(Exception $e){
            Mage::log('Erro: Fastcash OnCancelationReceived - Pedido: '.$gateway->getOrderId().' - Msg: '.$e->getMessage(),null,'fastcash_error.log');
            return array(false, $e->getMessage());
        }        
        
        //Return true/false or an array(false, "Error message")
        return false;
    }    
    
}