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
    *   BaseSender is an abstraction class for the Sender components.
    *   @example SampleCheckout/index.php
    */
    abstract class BaseSender extends BaseComponent
    {
        /**
        *   Constructor. Calls BaseComponent constructor.
        */
        function __construct() {
            parent::__construct();
        }

        /**
        *   Main function for the sender components. Sends the call to Fastcash.
        */
        abstract public function Send();
    }
}