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
	*   DataPushOrder represents an user order to be sent at DataPush.
	*   @see DynamicTransaction::CreateDataPush
	*   @see DataPush::CreateOrder
	*/
	class DataPushOrder
	{
		/**
		*   The order id at your system.
		*/
		public $Id;

		/**
		*   The order date.
		*/
		public $Date;

		/**
		*   The order name or products.
		*/
		public $Name;

		/**
		*   The order total value.
		*/
		public $Value;

		/**
		*   The order payment method.
		*/
		public $Method;

		/**
		*   The order payment method login.
		*/
		public $LoginMethod;

		/**
		*   The order status (0 for canceled or not completed, 1 for completed).
		*/
		public $Status;

		/**
		*   Constructor. Initializes a new instance of DataPushOrder.
		*   @param $id The order id at your system.
		*   @param $date The order date.
		*   @param $name The order name or products.
		*   @param $value The order total value.
		*   @param $status The order status (0 for canceled or not completed, 1 for completed).
		*/
		function __construct($id, $date, $name, $value, $status)
		{
			$this->Id = $id;
			$this->Date = $date;
			$this->Name = $name;
			$this->Value = $value;
			$this->Status = $status;
		}
	}
}
?>