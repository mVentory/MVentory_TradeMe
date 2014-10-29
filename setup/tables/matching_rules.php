<?php

$connection = $this->getConnection();
$table = $connection->newTable($tableName);

$table->addColumn(
  'id',
  Varien_Db_Ddl_Table::TYPE_INTEGER,
  null,
  array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary' => true,
  ),
  'Primary key'
);

$table->addColumn(
  'attribute_set_id',
  Varien_Db_Ddl_Table::TYPE_SMALLINT,
  null,
  array(
    'unsigned' => true,
    'nullable' => false,
    'default' => '0'
  ),
  'Attribute Set ID'
);

$table->addColumn(
  'rules',
  Varien_Db_Ddl_Table::TYPE_TEXT,
  null,
  array(
    'nullable' => true,
  ),
  'Matching rule data in JSON format'
);

$table->addIndex(
  $this->getIdxName($tableName, array('attribute_set_id')),
  array('attribute_set_id')
);

$table->addForeignKey(
  $this->getFkName(
    $tableName,
    'attribute_set_id',
    'eav/attribute_set',
    'attribute_set_id'
  ),
  'attribute_set_id',
  $this->getTable('eav/attribute_set'),
  'attribute_set_id',
  Varien_Db_Ddl_Table::ACTION_CASCADE,
  Varien_Db_Ddl_Table::ACTION_CASCADE
);

$table->setComment('TradeMe matching rules');

$connection->createTable($table);
