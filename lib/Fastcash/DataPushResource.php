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
	*   DataPushResource represents an user resource to be sent at DataPush.
	*   @see DynamicTransaction::CreateDataPush
	*   @see DataPush::CreateResource
	*/
	class DataPushResource
	{
		/**
		*   The resource name.
		*/
		public $Name;

		/**
		*   The resource date.
		*/
		public $Date;

		/**
		*   The resource description.
		*/
		public $Description;

		/**
		*   The resource mime-type.
		*/
		public $MimeType;

		/**
		*   The resource Base64 encoded data.
		*/
		public $Data;

		/**
		*   Constructor. Initializes a new instance of DataPushResource.
		*   @param $name The resource name.
		*   @param $date The resource date.
		*   @param $desc The resource description.
		*   @param $mime The resource mime-type.
		*/
		function __construct($name, $date, $desc, $mime)
		{
			$this->Name = $name;
			$this->Date = $date;
			$this->Description = $desc;
			$this->MimeType = $mime;
		}

		/**
		* Sets the specified base64 data as the resource data of this instance.
		* @param $data The Base64 data of the resource. 
		*/
		public function SetBase64Data($data)
		{
			$this->Data = $data;
		}
	}
}
?>