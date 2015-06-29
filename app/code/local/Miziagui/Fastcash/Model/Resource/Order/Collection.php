<?php

class Miziagui_Fastcash_Model_Resource_Order_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct() {
        $this->_init('fastcash/order');
    }
    
    /**
     * Filter by status
     *
     * @param string $status
     * @return Indexa_Braspag_Model_Mysql4_Orders_Collection
     */
    public function addStatusFilter($status) {
        $this->addFieldToFilter('main_table.status', $status);
        return $this;
    }
    
    /**
     * Filter by status
     *
     * @param string $status
     * @return Indexa_Braspag_Model_Mysql4_Orders_Collection
     */
    public function addStatusFilterCustom($status1,$status2) {
        $this->addFieldToFilter('main_table.status', array(array('in'=>array($status1,$status2))));
        return $this;
    }    
    
    /**
     * Filter by type
     *
     * @param string $type
     * @return Indexa_Braspag_Model_Mysql4_Orders_Collection
     */
    public function addTypeFilter($type) {
        $this->addFieldToFilter('main_table.type', $type);
        return $this;
    }

    /**
     * Filter by abandoned status
     *
     * @param integer $abandoned
     * @return Indexa_Braspag_Model_Mysql4_Orders_Collection
     */
    public function addAbandonedFilter($abandoned) {
        $this->addFieldToFilter('main_table.abandoned', $abandoned);
        return $this;
    }

    
    /**
     * Filter Time  
     *
     * @param integer $time
     * @return Indexa_Mc_Model_Resource_Payment_Collection
     */
    public function addExpireFilter( $time ) {
       
        $this->addFieldToFilter('main_table.created_at',  array('to'=> date("Y-m-d H:i:s", $time )) );
        return $this;
    }
}

