<?phprequire_once Mage::getBaseDir('lib')."/Fastcash/Fastcash.php";class Miziagui_Fastcash_Model_Source_Methodstrans {    public function toOptionArray() {        $methods = Fastcash\PaymentMethodsOptions::$Transference;        $returnArray = array();        foreach($methods as $key => $item){            $returnArray[] = array('value' => $item, 'label' => $key);        }        return $returnArray;    }}