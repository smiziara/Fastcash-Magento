<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();
/**
 * Tabela 'fastcash/order'
 */

$table = $installer->getConnection()
    ->newTable($installer->getTable('fastcash/order'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    	))
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ))   
    ->addColumn('transaction_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array())         
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ))
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
        'nullable'  => false,
    ))   
    ->addColumn('info', Varien_Db_Ddl_Table::TYPE_TEXT, null, array())         
    ->addColumn('field_count', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '1',
    ))        
    ->addColumn('f1_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64, array())        
    ->addColumn('f1_required', Varien_Db_Ddl_Table::TYPE_VARBINARY, null, array())
    ->addColumn('f1_data_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array())
    ->addColumn('f2_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64, array())        
    ->addColumn('f2_required', Varien_Db_Ddl_Table::TYPE_VARBINARY, null, array())
    ->addColumn('f2_data_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array())
    ->addColumn('f3_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64, array())        
    ->addColumn('f3_required', Varien_Db_Ddl_Table::TYPE_VARBINARY, null, array())
    ->addColumn('f3_data_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array())
    ->addColumn('f4_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 64, array())        
    ->addColumn('f4_required', Varien_Db_Ddl_Table::TYPE_VARBINARY, null, array())
    ->addColumn('f4_data_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array())
    ->addColumn('type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array())        
    ->addIndex($installer->getIdxName('fastcash/order', array('customer_id')),
        array('customer_id'))
	->addForeignKey($installer->getFkName('fastcash/order', 'customer_id', 'customer/entity', 'entity_id'),
    	'customer_id', $installer->getTable('customer/entity'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Miziagui Fastcash');
$installer->getConnection()->createTable($table);


$installer->endSetup();

