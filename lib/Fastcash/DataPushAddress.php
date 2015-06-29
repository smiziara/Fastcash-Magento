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
	*   DataPushAddress represents an user address to be sent at DataPush.
	*   @see DynamicTransaction::CreateDataPush
	*   @see DataPush::CreateAddress
	*/
	class DataPushAddress
	{
		/**
		*   The address city.
		*/
		public $City;

		/**
		*   The address state.
		*/
		public $State;

		/**
		*   The address zip code.
		*/
		public $ZipCode;

		/**
		*   The address last update or creation date.
		*/
		public $Update;

		/**
		*   The address string.
		*/
		public $Address;

		/**
		*   Constructor. Initializes a new instance of DataPushAddress.
		*   @param $city The address city.
		*   @param $state The address state.
		*   @param $zip The address zip code.
		*   @param $address The address string.
		*   @param $updated  The address last update or creation date.
		*/
		function __construct($city, $state, $zip, $address, $updated)
		{
			$this->City = $city;
			$this->State = $state;
			$this->ZipCode = $zip;
			$this->Address = $address;
			$this->Update = $updated;
		}
	}
}
?>