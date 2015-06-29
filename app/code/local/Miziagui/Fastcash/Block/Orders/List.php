<?php

class Miziagui_Fastcash_Block_Orders_List extends Mage_Core_Block_Template
{

    public function getOrders($size = NULL)
    {
        $fastcashOrders = Mage::getModel('fastcash/order')->getCollection();
        $fastcashOrders->addFieldToFilter('main_table.status', Miziagui_Fastcash_Model_Order::STATUS_CREATED)
                ->addFieldToFilter('main_table.customer_id', Mage::getSingleton('customer/session')->getCustomer()->getId());
        $fastcashOrders->getSelect()->reset('query');
        $fastcashOrders->getSelect()
            ->joinInner(array('s' => 'sales_flat_order'),
                'main_table.order_id = s.entity_id', array('increment_id' => 's.increment_id',
                                                           'created_at' => 's.created_at',
                                                           'customer_firstname' => 's.customer_firstname',
                                                           'customer_lastname' => 's.customer_lastname',
                                                           'base_grand_total' => 's.base_grand_total'));
        $fastcashOrders->setOrder('id','DESC');
        $fastcashOrders = $fastcashOrders->load();
        
        if ($size) {
            $fastcashOrders->setPageSize($size);
        }
        //echo $fastcashOrders->getSelect();
        return $fastcashOrders;
    }

}
