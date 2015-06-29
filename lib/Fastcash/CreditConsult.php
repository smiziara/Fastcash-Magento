<?php
 /**
  * @author Fastcash <cash@fastcash.com.br>
  * @copyright 2012 Fastcash
  * @license MIT
  */
  /*
  * DO NOT MODIFY THIS CLASS. 
  * This class may be updated in the future by us.
  */
namespace Fastcash
{
	/**
	*   CreditConsult class implements the Credit Consult function. Its a Receiver component.
	*/
	class CreditConsult extends BaseReceiver 
	{   
		/**
		*   Constructor. Calls BaseReceiver constructor.
		*/
		function __construct() {
			parent::__construct();
		}

		/**
		*   Main function that reads the Http parameters and process the request.
		*/
		public function Listen()
		{
			$this->ReceiveParameters();

			if ($this->Function == "credit-consult")
			{
				if ($this->ValidateParameters() === true)
				{
					$this->InvokeCallback();
				}
				else
				{
					$this->Respond(false, $this->GetLastError());
				}
			}
		}

		/**
		*   Validate function. Validate all component input parameters
		*   @returns True or False
		*/
		protected function Validate()
		{
			return true;
		}

		/**
		*   Builds the integrity signature of the parameters.
		*   @see Security::IntegrityHash
		*/
		protected function BuildSignature()
		{
			return Security::IntegrityHash($this->Tid, IntegrationData::CreditConsultKey, IntegrationData::Pid);
		}

		/**
		*   Invokes the callback function and process the result using BaseReceiver::ProcessResult.
		*   @see BaseComponent::SetCallback
		*/
		protected function InvokeCallback()
		{
			if (!empty($this->CallbackClass) && !empty($this->CallbackFn))
			{
				$result = call_user_func(array($this->CallbackClass,$this->CallbackFn), $this, $this->Tid, $this->Custom);

				$this->ProcessResult($result);
			}
			else
			{
				$this->RaiseError("CreditConsult callback not implemented!");
				$this->Respond(false, $this->GetLastError());
			}
		}

		/**
		*   Responds the Request to Fastcash, echoing the response Xml.
		*/
		protected function Respond($success, $errorMsg = null)
		{
			$pid = IntegrationData::Pid;
			$cv = ($success == true ? 1 : 0);
			$signature = $this->BuildSignature();
			$msg = ($errorMsg == null ? "" : $errorMsg);

			$xml = "<xml>
						<tid>$this->Tid</tid>
						<publisher_id>$pid</publisher_id>
						<cv>$cv</cv>
						<dsignature>$signature</dsignature>
						<err>$msg</err>
					</xml>";

			echo $xml;
		}
	}
}
?>