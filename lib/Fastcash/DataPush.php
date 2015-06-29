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
	*   DataPush class builds the XML to push the user registration details.
	*   The DataPush is sent together with the DynamicTransaction.
	*   @see DynamicTransaction::CreateDataPush
	*/
	class DataPush
	{
		/**
		*   The Internal Id of the user in your system.
		*/
		public $InternalID;

		/**
		*   The Login of the user in your system.
		*/
		public $Login;

		/**
		*   The Name of the user, including the First and Last names.
		*/
		public $Name;

		/**
		*   The CPF of the user.
		*/
		public $CPF;

		/**
		*   The RG of the user.
		*/
		public $RG;

		/**
		*   The Date of Birth of the user.
		*/
		public $DateOfBirth;

		/**
		*   The date of the user registration at your system.
		*/
		public $ClientSince;

		/**
		*   The date of the user last profile update at your system.
		*/
		public $LastUpdate;

		/**
		*   The list of user email addresses.
		*/
		public $Emails;

		/**
		*   The list of user phone numbers.
		*/
		public $Phones;

		/**
		*   The list of user addresses.
		*/
		public $Addresses;

		/**
		*   The user history of orders in your sustem.
		*/
		public $Orders;

		/**
		*   The list of user resources, like document scan images.
		*/
		public $Resources;

		/**
		*   Constructor with the minimum required parameters to send a DataPush.
		*   @param $name The user full name.
		*   @param $email  The user valid main email address.
		*   @param $cpf The user CPF.
		*/
		function __construct($name, $email, $cpf)
		{   
			$this->Name = $name;
			$this->AddEmail($email);
			$this->CPF = $cpf;
		}

		/**
		*   Adds the specified email address to the #$Emails list.
		*   @param $email A valid email address.
		*/
		public function AddEmail($email)
		{
			if (!isset($this->Emails))
			{
				$this->Emails = array();
			}

			if (!in_array($email, $this->Emails))
			{
				array_push($this->Emails, $email);
			}
		}

		/**
		*   Adds a DataPushPhone instance to the #$Phones list.
		*   @param $tel A DataPushPhone instance.
		*   @see DataPushPhone
		*/
		public function AddPhone($tel)
		{
			if (!isset($this->Phones))
			{
				$this->Phones = array();
			}

			if ($tel instanceof DataPushPhone && !in_array($tel, $this->Phones))
			{
				array_push($this->Phones, $tel);
			}
		}

		/**
		*   Adds a DataPushAddress instance to the #$Addresses list.
		*   @param $addr A DataPushAddress instance.
		*   @see DataPushAddress
		*/
		public function AddAddress($addr)
		{
			if (!isset($this->Addresses))
			{
				$this->Addresses = array();
			}

			if ($addr instanceof DataPushAddress && !in_array($addr, $this->Addresses))
			{
				array_push($this->Addresses, $addr);
			}
		}

		/**
		*   Adds a DataPushOrder instance to the #$Orders list.
		*   @param $order A DataPushOrder instance.
		*   @see DataPushOrder
		*/
		public function AddOrder($order)
		{
			if (!isset($this->Orders))
			{
				$this->Orders = array();
			}

			if ($order instanceof DataPushOrder && !in_array($order, $this->Orders))
			{
				array_push($this->Orders, $order);
			}
		}

		/**
		*   Adds a DataPushResource instance to the #$Resources list.
		*   @param $res A DataPushResource instance.
		*   @see DataPushResource
		*/
		public function AddResource($res)
		{
			if (!isset($this->Resources))
			{
				$this->Resources = array();
			}

			if ($res instanceof DataPushResource && !in_array($res, $this->Resources))
			{
				array_push($this->Resources, $res);
			}
		}

		/**
		*   Creates a DataPushPhone instance with the specified type and number, and adds to the #$Phones list.
		*   @param $type The phone type.
		*   @param $number The phone number.
		*   @returns The DataPushPhone instance reference.
		*   @see DataPushPhone
		*/
		public function &CreatePhone($type, $number)
		{
			$tel = new DataPushPhone($type, $number);

			$this->AddPhone($tel);

			return $tel;
		}

		/**
		*   Creates a DataPushAddress instance with the specified parameters, and adds to the #$Addresses list.
		*   @param $city The address city.
		*   @param $state The address state.
		*   @param $zip The address zip code.
		*   @param $address The address.
		*   @param $updated An optional date with the last update or the creation date of this address, at your system.
		*   @returns The DataPushAddress instance reference.
		*   @see DataPushAddress
		*/
		public function &CreateAddress($city, $state, $zip, $address, $updated = null)
		{
			$addr = new DataPushAddress($city, $state, $zip, $address, $updated);

			$this->AddAddress($addr);

			return $addr;
		}

		/**
		*   Creates a DataPushOrder instance with the specified parameters, and adds to the #$Orders list.
		*   @param $id The order id at your system.
		*   @param $date The order date.
		*   @param $name The order name or products list.
		*   @param $value The order total value.
		*   @param $status The order status (0 for canceled or not completed, 1 for completed).
		*   @returns The DataPushOrder instance reference.
		*   @see DataPushOrder
		*/
		public function &CreateOrder($id, $date, $name, $value, $status)
		{
			$order = new DataPushOrder($id, $date, $name, $value, $status);

			$this->AddOrder($order);

			return $order;
		}

		/**
		*   Creates a DataPushResource instance with the specified parameters, and adds to the #$Resources list.
		*   @param $name The name of the resource.
		*   @param $date The date of the resource creation.
		*   @param $desc The description of the resource.
		*   @param $mime The mime-type of the resource.
		*   @param $base64Resource The resource data encoded with Base64. Can be passed as a parameter or set later using DataPushResource::SetBase64Data.
		*   @returns The DataPushResource instance reference.
		*   @see DataPushResource
		*/
		public function &CreateResource($name, $date, $desc, $mime, $base64Resource = null)
		{
			$res = new DataPushResource($name, $date, $desc, $mime);

			if ($base64Resource != null)
				$res->SetBase64Data($base64Resource);

			$this->AddResource($res);

			return $res;
		}

		/**
		*   Gets this DataPush instance serialized as Xml and url-encoded.
		*   @returns A Url-encoded xml string.
		*/
		public function GetXml()
		{
			return urlencode($this->GetRawXml());
		}

		/**
		*   Gets this DataPush instance serialized as Xml.
		*   @returns A xml string.
		*/
		public function GetRawXml()
		{
			$xml = "";

			if (isset($this->InternalID))
				$xml .= "<InternalID>$this->InternalID</InternalID>";

			if (isset($this->Login))
				$xml .= "<Login>$this->Login</Login>";

			if (isset($this->Name))
				$xml .= "<Name>$this->Name</Name>";

			if (isset($this->CPF))
				$xml .= "<CPF>$this->CPF</CPF>";

			if (isset($this->RG))
				$xml .= "<RG>$this->RG</RG>";           

			if (isset($this->DateOfBirth))
				$xml .= "<DateOfBirth>$this->DateOfBirth</DateOfBirth>";

			if (isset($this->ClientSince))
				$xml .= "<ClientSince>$this->ClientSince</ClientSince>";               

			if (isset($this->LastUpdate))
				$xml .= "<LastUpdate>$this->LastUpdate</LastUpdate>";
		
			if (isset($this->Emails) && count($this->Emails) > 0)
			{
				$xml .= "<Emails>";

				foreach($this->Emails as $email)
				{
					$xml .= "<Email>$email</Email>";
				}

				$xml .= "</Emails>";
			}

			if (isset($this->Phones) && count($this->Phones) > 0)
			{
				$xml .= "<Phones>";

				foreach($this->Phones as $tel)
				{
					$xml .= "<Phone type=\"$tel->Type\">$tel->Number</Phone>";
				}

				$xml .= "</Phones>";
			}

			if (isset($this->Addresses) && count($this->Addresses) > 0)
			{
				$xml .= "<Addresses>";

				foreach($this->Addresses as $addr)
				{
					$xml .= "<Address city=\"$addr->City\" state=\"$addr->State\" zip=\"$addr->ZipCode\" update=\"$addr->Update\"><![CDATA[$addr->Address]]></Address>";
				}

				$xml .= "</Addresses>";
			}

			if (isset($this->Orders) && count($this->Orders) > 0)
			{
				$xml .= "<Orders>";

				foreach($this->Orders as $o)
				{
					$xml .= "<Order id=\"$o->Id\" date=\"$o->Date\" name=\"$o->Name\" value=\"$o->Value\" method=\"$o->Method\" loginMethod=\"$o->LoginMethod\" status=\"$o->Status\" />";
				}

				$xml .= "</Orders>";
			}

			if (isset($this->Resources) && count($this->Resources) > 0)
			{
				$xml .= "<Resources>";

				foreach($this->Resources as $r)
				{
					$xml .= "<Resource name=\"$r->Name\" date=\"$r->Date\" description=\"$r->Description\" mimeType=\"$r->MimeType\"><![CDATA[$r->Data]]></Resource>";
				}

				$xml .= "</Resources>";
			}

			$validation = Security::IntegrityHash(IntegrationData::DataPushKey, $xml);

			$xml = "<Data-Push Validation=\"$validation\"><Push>" . $xml . "</Push></Data-Push>";

			return $xml;
		}
	}
}
?>