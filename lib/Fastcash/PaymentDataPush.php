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
	*   PaymentDataPush class implements the Payment DataPush function. Its a Sender component.
    *   @remarks This function should be used before the DynamicTransaction call.
	*/
	class PaymentDataPush extends BaseSender 
	{   
        /**
        * A Credit card type.
        */
		const Credit = 1;

        /**
        * A Debit card type.
        */
		const Debit = 2;
		
        /**
        * The Visa card flag.
        */
		const Visa = 1;

        /**
        * The Mastercard card flag.
        */
		const Mastercard = 2;

        /**
        * The Diners card flag.
        */
		const Diners = 3;

        /**
        * The Amex card flag.
        */
		const Amex = 4;

        /**
        * The Elo card flag.
        */
		const Elo = 5;

        /**
        * The Discover card flag.
        */
		const Discover = 6;

    	/**
		*   The API function that the class is implementing.
		*/
		private $Function = "payment-data";

        /**
		*  The transaction identifier.
		*/
		private $Tid;

        /**
        *   The card type.
        */
		private $Type;

        /**
        *   The card number.
        */
		private $Number;

        /**
        *   The card verification code (of the back of the card).
        */
		private $Cvv;

        /**
        *   The card valid/expiration date.
        */
		private $ValidDate;

        /**
        *   The card holder name impress on it.
        */
		private $Name;

        /**
        *   The card flag.
        */
		private $Flag;

        /**
        *   The card issuer name, if available.
        */
		private $Issuer;

        /**
        *   The card holder cpf.
        */
		private $Cpf;

        /**
        * A boolean value if the card is validated at your system.
        * If this card has already been used and no fraud has been reported, or you have the scanned documents, send true, else false.
        */
		private $Validated;

        /**
        *   The biggest value that you already billed this card for.
        */
		private $MaxProcessedAmount;

        /**
        *   The last server error.
        */
		private $ServerError;

        /**
		*   Constructor. Calls BaseSender constructor.
        *   Needs the minimum required parameters to send a card for payment datapush.
        *   @param $tid The transaction identifier.
        *   @param $number The card number.
        *   @param $validDate The card valid/expiration date.
        *   @param $cvv The card verification code (of the back of the card).
        *   @param $name The card hold name impress on it.
        *   @param $flag The card flag.
        *   @param $type The card type.
		*/
		function __construct($tid, $number, $validDate, $cvv, $name, $flag, $type = PaymentDataPush::Credit) {
			parent::__construct();

			$this->Tid = $tid;
			$this->Type = $type;
			$this->Number = $number;
			$this->Cvv = $cvv;
			$this->ValidDate = $validDate;
			$this->Name = $name;
			$this->Flag = $flag;
		}

        /**
        *   Main function for the sender components. Sends the call to Fastcash.
        *   Validate the card details and send the call to Fastcash API.
        *   @returns true or false
        */
		public function Send()
		{   
			if ($this->Validate())
			{
				if ($this->CallApi() === true)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}

        /**
        *   Send the call to Fastcash API.
        *   @returns true if the card was accepted or false
        */
		public function CallApi()
		{
			$url = IntegrationData::FastcashAPIUrl . "?function=" . $this->Function . "&tid=" . urlencode($this->Tid) . "&pid=" . IntegrationData::Pid;
			$encXml = $this->GetEncryptedXml();
			$signature = $this->GenerateSignature($encXml);

			$params = "signature=" . $signature . "&data=" . urlencode($encXml);

			$ch = curl_init();
			$user_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; FastcashAPI 4.0-php)";
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

			$result = curl_exec($ch);
			curl_close($ch);

			if ($result === false)
			{
				return false;
			}
			else
			{
				$xml = simplexml_load_string($result);

				$valid = $xml->xml->Valid;
				$accepted = $xml->xml->Accepted;
				$error = $xml->xml->Err;

				if ($valid == true && $accepted == true)
				{
					return true;
				}
				else
				{
					$this->ServerError = $error;

					$this->RaiseError("Card not accepted or invalid: $xml. Error: $error");

					return false;
				}
			}
		}

        /**
        *   Generates the integrity hash signature for the Payment DataPush xml.
        *   @param $encXml The encrypted xml from GetEncryptedXml().
        *   @returns A string hash.
        */
		public function GenerateSignature($encXml)
		{
			return Security::IntegrityHash(IntegrationData::PaymentDataPushSignatureKey, $encXml);
		}

        /**
        *   Gets the serialized Payment DataPush xml and encrypt with Security::EncryptRijndael.
        *   @returns A base64 encode of the encrypted xml
        */
		public function GetEncryptedXml()
		{
			return Security::EncryptRijndael($this->GetXml());
		}

        /**
        *   Gets the serialized Payment DataPush xml.
        *   @returns A xml string
        */
		public function GetXml()
		{
			$xml = "<Payment-Data><Card>";
			$xml .= "<Type>$this->Type</Type>";
			$xml .= "<Number>$this->Number</Number>";
			$xml .= "<CVV>$this->Cvv</CVV>";
			$xml .= "<ValidDate>$this->ValidDate</ValidDate>";
			$xml .= "<Name>$this->Name</Name>";
			$xml .= "<Flag>$this->Flag</Flag>";
			
			if (isset($this->Issuer) && !empty($this->Issuer))
			{
				$xml .= "<Issuer>$this->Issuer</Issuer>";
			}

			if (isset($this->Cpf) && !empty($this->Cpf))
			{
				$xml .= "<CPF>$this->Cpf</CPF>";
			}

			if (isset($this->Validated))
			{
				$xml .= "<Validated>$this->Validated</Validated>";
			}

			if (isset($this->MaxProcessedAmount) && !empty($this->MaxProcessedAmount))
			{
				$xml .= "<MaxProcessedAmount>$this->MaxProcessedAmount</MaxProcessedAmount>";
			}

			$xml .= "</Card></Payment-Data>";

			return $xml;
		}

        /**
        *   Validates the instance fields.
        *   @returns true or false
        */
		public function Validate()
		{
			if (empty($this->Tid))
			{
				$this->RaiseError("Tid is required.", "Tid");
				return false;
			}
			else if (empty($this->Number) || empty($this->ValidDate) || empty($this->Cvv) || empty($this->Name) || empty($this->Flag))
			{
				$this->RaiseError("The parameters Number, Valid Date, Cvv, Name and Flag are required");
				return false;
			}
			else if ($this->Flag < 1 || $this->Flag > 6)
			{
				$this->RaiseError("Invalid Flag value.", "Flag");
				return false;
			}
			else if (strlen($this->Name) < 6)
			{
				$this->RaiseError("Invalid Name value.", "Name");
				return false;
			}
			else if (strlen($this->Cvv) < 3 || strlen($this->Cvv) > 4)
			{
			   $this->RaiseError("Invalid Cvv value.", "Cvv"); 
			   return false;
			}
			else if (strlen($this->ValidDate) != 5 || preg_match("/[0-9][0-9]\/[1-9][1-9]/", $this->ValidDate) < 1)
			{
				$this->RaiseError("Invalid Valid Date value. Format: MM/yy", "ValidDate");
				return false;
			}
			else if (!$this->ValidateCardNumber($this->Number))
			{
				$this->RaiseError("Card Number is invalid.", "Number");
				return false;
			}
			else if (!$this->ValidateCardNumberForFlag($this->Number, $this->Flag))
			{
				$this->RaiseError("Card Number is invalid for the specified flag.", "Number");
				return false;
			}
			else
			{
				return true;
			}
		}

        /**
        *   Gets the server error message returned by Fastcash API.
        */
		public function GetServerError()
		{
			return $this->ServerError;
		}

        /**
        *   Invokes the callback function.
        */
		public function InvokeCallback()
		{
			return false;
		}

        /**
        *   Sets the card issuer institution.
        *   @param $issuer The card issuer.
        */
		function SetIssuer($issuer)
		{
			$this->Issuer = $issuer;
		}

        /**
        *   Sets the card holder cpf.
        *   @param $cpf The card holder cpf.
        */
		function SetCpf($cpf)
		{
			if (strlen($cpf) >  11)
			{
				$cpf = preg_replace("/[^0-9]/", "", $cpf);
			}

			$this->Cpf = $cpf;
		}

        /**
        *   Sets the card validated flag.
        *   If this card has already been used and no fraud has been reported, or you have the scanned documents, send true, else false.
        *   @param $validated 
        */
		function SetValidated($validated)
		{
			if (settype($validated, bool))
			{
				$this->Validated = $validated;
			}
			else
			{
				$this->Validated = false;
			}
		}

        /**
        *   Sets the card validated flag.
        *   The biggest value that you already billed this card for.
        *   @param $amount The amount billed. 
        *   @returns true or false
        */
		function SetMaxProcessedAmount($amount)
		{
			if (settype($amount, float))
			{
				$this->MaxProcessedAmount = $amount;
			}
		}

        /**
        *   Validates the card number using Luhn rule.
        *   @param $number The card number. 
        */
		function ValidateCardNumber($number)
		{
			$sum = 0; 
			$alt = false; 
	
			for($i = strlen($number) - 1; $i >= 0; $i--){ 
				$n = substr($number, $i, 1); 
				if($alt){ 
					$n *= 2; 
					if($n > 9) { 
					$n = ($n % 10) +1; 
					} 
				} 
				$sum += $n; 
				$alt = !$alt; 
			}    

			return ($sum % 10 == 0); 
		}

        /**
        *   Validates the card number for the selected flag.
        *   @param $number The card number.
        *   @param $flag The card flag.
        *   @returns true or false
        */
		function ValidateCardNumberForFlag($number, $flag)
		{
			$len = strlen($number);

			switch($flag)
			{
				case PaymentDataPush::Visa:
				{
					return strpos($number, "4") === 0 && ($len == 13 || $len == 16);
				}
				case PaymentDataPush::Mastercard:
				{
					return (strpos($number, "51") === 0 || strpos($number, "52") === 0 || strpos($number, "53") === 0 || strpos($number, "54") === 0|| strpos($number, "55") === 0) && $len == 16;
				}
				case PaymentDataPush::Diners:
				{
					return (strpos($number, "300") === 0 || strpos($number, "301") === 0 || strpos($number, "302") === 0 || strpos($number, "303") === 0 || strpos($number, "304") === 0 || strpos($number, "305") === 0 || strpos($number, "36") === 0 || strpos($number, "38") === 0) && $len == 14;
				}
				case PaymentDataPush::Amex:
				{
					return (strpos($number, "34") === 0 || strpos($number, "37") === 0) && $len == 15;
				}
				case PaymentDataPush::Elo:
				{
					return strpos($number, "6362") === 0 && $len == 16;
				}
				case PaymentDataPush::Discover:
				{
					return strpos($number, "6011") === 0 && $len == 15;
				}
			}

			return false;
		}
	}
}
?>