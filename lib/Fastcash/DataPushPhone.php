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
	*   DataPushPhone represents an user phone to be sent at DataPush.
	*   @see DynamicTransaction::CreateDataPush
	*   @see DataPush::CreatePhone
	*/
	class DataPushPhone
	{
		/**
		*   The phone type for Mobile.
		*/
		const Mobile = "Mobile";

		/**
		*   The phone type for Work.
		*/
		const Work = "Business";

		/**
		*   The phone type for Landline.
		*/
		const Landline = "Landline";

		/**
		*   The phone type for Messages.
		*/
		const Messages = "Messages";

		/**
		*   The phone type for Others or unknown.
		*/
		const Others = "Others";

		/**
		*   The phone type.
		*/
		public $Type;

		/**
		*   The phone Number.
		*/
		public $Number;

		/**
		*   Constructor. Initializes a new instance of DataPushPhone.
		*   @param $type The phone type.
		*   @param $number The phone Number.
		*/
		function __construct($type, $number)
		{
			if ($type != self::Mobile && $type != self::Work && $type != self::Landline && $type != self::Messages && $type != self::Others)
			{
				$this->Type = self::Others;
			}
			else
			{
				$this->Type = $type;
			}

			$this->Number = $number;
		}
	}
}
?>