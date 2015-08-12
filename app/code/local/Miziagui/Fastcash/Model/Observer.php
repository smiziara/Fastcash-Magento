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
    
    public function fastcashDiscount($observer) { 
        $shoppingCartPriceRule = Mage::getModel('salesrule/rule')->getCollection();
        if (Mage::getStoreConfig('fastcash/fastcash_payment/active') && Mage::getStoreConfig('fastcash/fastcash_payment/active') > 0) {
            $flag = false;
            $discount = Mage::getStoreConfig('fastcash/fastcash_payment/discount');
            $labels[0] = ''; //Texto Desconto
            
            foreach ($shoppingCartPriceRule as $rule) {
                if ($rule->getData('name') == 'Desconto Fastcash') {
                    $flag = true;

                    $shoppingCartPriceRule = Mage::getModel('salesrule/rule');
                    $shoppingCartPriceRule
                            ->setRuleId($rule->getId())
                            ->setName($rule->getData('name'))
                            ->setDescription('')
                            ->setIsActive(1)
                            ->setWebsiteIds(array(1))
                            ->setCustomerGroupIds(array(0, 1, 2, 3))
                            ->setFromDate('')
                            ->setToDate('')
                            ->setSortOrder('')
                            ->setSimpleAction(Mage_SalesRule_Model_Rule::BY_PERCENT_ACTION)
                            ->setDiscountAmount($discount)
                            ->setStopRulesProcessing(0)
                            ->setStoreLabels($labels);

                    $conditions = array(
                        "1" => array(
                            "type" => "salesrule/rule_condition_combine",
                            "aggregator" => "all",
                            "value" => "1",
                            "new_child" => null
                        ),
                        "1--1" => array(
                            "type" => "salesrule/rule_condition_address",
                            "attribute" => "payment_method",
                            "operator" => "==",
                            "value" => "fastcash_payment"
                        )
                    );

                    try {
                        $shoppingCartPriceRule->setData("conditions", $conditions);
                        $shoppingCartPriceRule->loadPost($shoppingCartPriceRule->getData());
                        $shoppingCartPriceRule->save();
                    } catch (Exception $e) {
                        Mage::log($e->getMessage(), null, 'erro_fastcash_desconto.log');
                        Mage::getSingleton('core/session')->addError(Mage::helper('catalog')->__($e->getMessage()));
                        return;
                    }
                }
            }
            if (!$flag) {
                $name = "Desconto Fastcash";

                $shoppingCartPriceRule = Mage::getModel('salesrule/rule');
                $shoppingCartPriceRule
                        ->setName($name)
                        ->setDescription('')
                        ->setIsActive(1)
                        ->setWebsiteIds(array(1))
                        ->setCustomerGroupIds(array(0, 1, 2, 3))
                        ->setFromDate('')
                        ->setToDate('')
                        ->setSortOrder('')
                        ->setSimpleAction(Mage_SalesRule_Model_Rule::BY_PERCENT_ACTION)
                        ->setDiscountAmount($discount)
                        ->setStopRulesProcessing(0)
                        ->setStoreLabels($labels);

                $conditions = array(
                    "1" => array(
                        "type" => "salesrule/rule_condition_combine",
                        "aggregator" => "all",
                        "value" => "1",
                        "new_child" => null
                    ),
                    "1--1" => array(
                        "type" => "salesrule/rule_condition_address",
                        "attribute" => "payment_method",
                        "operator" => "==",
                        "value" => "fastcash_payment"
                    )
                );

                try {
                    $shoppingCartPriceRule->setData("conditions", $conditions);
                    $shoppingCartPriceRule->loadPost($shoppingCartPriceRule->getData());
                    $shoppingCartPriceRule->save();
                } catch (Exception $e) {
                    Mage::log($e->getMessage(), null, 'erro_fastcash_desconto.log');
                    Mage::getSingleton('core/session')->addError(Mage::helper('catalog')->__($e->getMessage()));
                    return;
                }
            }
        } else {
            foreach ($shoppingCartPriceRule as $rule) {
                if ($rule->getData('name') == 'Desconto Fastcash') {
                    Mage::getModel('salesrule/rule')->load($rule->getId())->delete();
                }
            }
        }
    }    

}
