<?php
namespace Fastcash
{
    class IntegrationData
    {
        /**
        *   Your Integration ID with Fastcash.
        */
        const Pid = 115;

        /**
        *   The Online Credit function key for MD5 integrity signature.
        */
        const OnlineCreditKey = "J19112xjtFpJfSiucevnNiDZNeWmTSmJoxRdQavJJARCDazwLlCssQojVOIzTCoZ";

        /**
        *   The Credit Consult function key for MD5 integrity signature.
        */
        const CreditConsultKey = "J19112xjtFpJfSiucevnNiDZNeWmTSmJoxRdQavJJARCDazwLlCssQojVOIzTCoZ";

        /**
        *   The Cancelation function key for MD5 integrity signature.
        */
        const CancelationKey = "J19112xjtFpJfSiucevnNiDZNeWmTSmJoxRdQavJJARCDazwLlCssQojVOIzTCoZ";

        /**
        *   The Dynamic Transaction function key for DataPush xml integrity signature.
        */
        const DataPushKey = "";

        /**
        *   The Payment-Data Push function key for the xml integrity signature.
        */
        const PaymentDataPushSignatureKey = "";

        /**
        *   The Payment-Data Push Rijndael key for encrypt the data.
        */
        const PaymentDataPushKey = "";

        /**
        *   The Payment-Data Push Rijndael IV block.
        */
        const PaymentDataPushIV = "";

        /**
        *   The Fastcash API url.
        */
        //const FastcashAPIUrl = "https://www.fastcash.com.br/carrinho/api.aspx";
        
        const FastcashAPIUrl = "https://www.fastcash.com.br/api-vnext/api/";
        const FastcashAPIKey = "519b241b9b4178";
        const FastcashAPIKeySecret = "NTE5YjI0MWI5YjQxNzg=";
        
        public static $ProductIds = array("Default"=> 3587);
    }
}

?>