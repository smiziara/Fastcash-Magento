<?php

class Miziagui_Fastcash_Model_Observer
{

    public function addLinkToAccountNavigation($observer)
    {
        $navigationBlock = $observer->getLayout()->getBlock('customer_account_navigation');
        if (!is_object($navigationBlock)) {
            return $this;
        }
        $navigationBlock->addLink('trocas', 'fastcash/confirm/orders/', 'Fastcash', '', 41);
        return $this;
    }    

}
