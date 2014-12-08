<?php

$this->startSetup();

$this->createTable('auction');

$products = Mage::getModel('catalog/product')
  ->getCollection()
  ->addAttributeToSelect('tm_current_account_id')
  ->addAttributeToFilter('type_id', 'simple')
  ->addAttributeToFilter('tm_current_listing_id', array('neq' => ''));

foreach ($products as $product)
  Mage::getModel('trademe/auction')
    ->setData(array(
        'product_id' => $product->getId(),
        'listing_id' => $product['tm_current_listing_id'],
        'account_id' => $product['tm_current_account_id']
      ))
    ->save();

$this->removeAttributes(
  array(
    'tm_listing_id',
    'tm_current_listing_id',
    'tm_account_id',
    'tm_current_account_id'
  ),
  true
);

$this->updateAttributes(
  array('tm_relist' => array('source' => 'trademe/attribute_source_relist'))
);


$this->endSetup();
