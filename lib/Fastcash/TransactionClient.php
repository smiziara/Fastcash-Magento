<?php
 /**
  * @author Fastcash <cash@fastcash.com.br>
  * @copyright 2013 Fastcash
  * @license MIT
  */
  /*
  * DO NOT MODIFY THIS CLASS. 
  * This class may be updated in the future by us.
  */
namespace Fastcash
{
    use Guzzle\Http\Client;
    use Guzzle\Http\Exception\ServerErrorResponseException;
    use Guzzle\Http\Exception\ClientErrorResponseException;
    
    class TransactionClient extends BaseClient
    {
        public $Error;
        public $ErrorBody;
        
        function __construct() {
            parent::__construct();
        }
        
        public function Send($transactionRequest)
        {
            if ($transactionRequest instanceOf TransactionRequest)
            {
                try
                {
                    $request = $this->Client->post("Transaction", null, json_encode($transactionRequest));
                    $request->setHeader("Content-type", "text/json");
                    $json = $request->send()->json();
                    
                    $response = new TransactionResponse($json);
                    
                    $this->Error = null;
                    $this->ErrorBody = null;
                    
                    return $response;
                }
                catch(ServerErrorResponseException $e)
                {
                    $this->Error = $e->getMessage();
                }
                catch(ClientErrorResponseException $ce)
                {
                    $this->Error = $ce->getMessage();
                    $this->ErrorBody = $ce->getResponse()->getBody();
                }
            }
            
            return null;
        }
    }
}
?>