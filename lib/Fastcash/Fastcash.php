<?php
    date_default_timezone_set('America/Sao_Paulo');
    error_reporting(E_ALL ^ E_WARNING );
    //Guzzle Framework
    require_once Mage::getBaseDir('lib')."/Fastcash/guzzle.phar";
    
    //Fastcash configuration data
    require_once Mage::getBaseDir('lib')."/Fastcash/FastcashIntegrationData.php";
    
    //API Models
    require_once Mage::getBaseDir('lib')."/Fastcash/ClientData.php";    
    require_once Mage::getBaseDir('lib')."/Fastcash/ClientTransactionData.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/ConfirmationData.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/TransactionData.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/ParameterData.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/ConfirmationRequirementField.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/ConfirmationRequirementData.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/ConfirmationRequest.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/TransactionRequest.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/TransactionResponse.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/PaymentMethods.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/PaymentMethodsOptions.php";

    //API
    require_once Mage::getBaseDir('lib')."/Fastcash/BaseClient.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/TransactionClient.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/ConfirmationClient.php";

    //RECEIVER
    require_once Mage::getBaseDir('lib')."/Fastcash/BaseComponent.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/BaseReceiver.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/BaseSender.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/Cancelation.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/CreditConsult.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/DataPush.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/DataPushAddress.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/DataPushOrder.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/DataPushPhone.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/DataPushResource.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/DynamicTransaction.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/OnlineCredit.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/PaymentDataPush.php";
    require_once Mage::getBaseDir('lib')."/Fastcash/Security.php";    
    
    
?>