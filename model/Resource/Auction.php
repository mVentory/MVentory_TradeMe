<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License BY-NC-ND.
 * NonCommercial — You may not use the material for commercial purposes.
 * NoDerivatives — If you remix, transform, or build upon the material,
 * you may not distribute the modified material.
 * See the full license at http://creativecommons.org/licenses/by-nc-nd/4.0/
 *
 * See http://mventory.com/legal/licensing/ for other licensing options.
 *
 * @package MVentory/TradeMe
 * @copyright Copyright (c) 2014 mVentory Ltd. (http://mventory.com)
 * @license http://creativecommons.org/licenses/by-nc-nd/4.0/
 */

/**
 * Resource model for auction model
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */

class MVentory_TradeMe_Model_Resource_Auction
  extends Mage_Core_Model_Resource_Db_Abstract
{
  /**
   * Resource initialisation
   */
  protected function _construct() {
    $this->_init('trademe/auction', 'id');
  }

  /**
   * Loading stock item data by product
   *
   * @param Varien_Object $auction
   * @param int $productId
   * @return MVentory_TradeMe_Model_Resource_Auction
   */
  public function loadByProductId ($auction, $productId) {
    $adp = $this->_getReadAdapter();
    $table = $this->getMainTable();

    $type = $adp->quoteIdentifier(array($table, 'type'));

    $select = $this
      ->_getLoadSelect('product_id', $productId, $auction)
      ->where($type . ' = :type');

    $data = $adp->fetchRow(
      $select,
      array('type' => MVentory_TradeMe_Model_Config::AUCTION_NORMAL)
    );

    if ($data)
      $auction->setData($data);

    $this->_afterLoad($auction);

    return $this;
  }

  /**
   * Get number of auctions for products excluding specified in $exclude
   * parameter
   *
   * @param  array $exclude List if product IDs to exclude
   * @return array List of number of auctions per peoduct
   */
  public function getNumberPerProduct ($exclude = null) {
    $adp = $this->_getReadAdapter();
    $table = $this->getMainTable();
    $productId = $adp->quoteIdentifier(array($table, 'product_id'));

    $select = $adp
      ->select()
      ->from(
          $table,
          array(
            'product_id',
            'auctions_number' => 'COUNT(' . $productId . ')'
          )
        )
      ->group('product_id');

    if (is_array($exclude))
      if (count($exclude) > 1)
        $select->where('product_id NOT IN (?)', $exclude);
      elseif (count($exclude) == 1)
        $select->where('product_id != ?', reset($exclude));

    return $adp->fetchPairs($select);
  }

  /**
   * Get list of product listed in specified period of time and filtered by
   * auction type
   *
   * @param  int $type Type of auction
   * @param  string $fromData Filter by period of time (from date)
   * @param  strint $toDate Filter by period of time (to date)
   * @return array List of product IDs
   */
  public function getListedProducts ($type, $fromData, $toDate) {
    $adp = $this->_getReadAdapter();
    $table = $this->getMainTable();

    $select = $adp
      ->select()
      ->from($table, 'product_id')
      ->where('type = :type')
      ->where('listed_at > :from_date')
      ->where('listed_at < :to_date')
      ->group('product_id');

    return $adp->fetchCol(
      $select,
      array(
        'type' => $type,
        'from_date' => $fromData,
        'to_date' => $toDate
      )
    );
  }

  /**
   * Filter out products which have normal auction
   *
   * @param  Mage_Eav_Model_Entity_Collection_Abstract $collection Products
   * @return Mage_Eav_Model_Entity_Collection_Abstract Collection of products
   */
  public function filterNormalAuctions ($collection) {
    $adp = $this->_getReadAdapter();
    $table = $this->getMainTable();

    $entityId = $adp->quoteIdentifier(array('e', 'entity_id'));
    $productId = $adp->quoteIdentifier(array('auction', 'product_id'));
    $auctionType = $adp->quoteIdentifier(array('auction', 'type'));

    $conditions = array(
      $entityId . ' = ' . $productId,
      $adp->prepareSqlCondition(
        $auctionType,
        MVentory_TradeMe_Model_Config::AUCTION_NORMAL
      )
    );

    $and = ') ' . Zend_Db_Select::SQL_AND . ' (';

    $collection
      ->getSelect()
      ->joinLeft(
          //Table name
          array('auction' => $this->getMainTable()),

          //Conditions
          '(' . implode($and, $conditions) . ')' ,

          //Columns
          array(
            'auction_listing_id' => 'listing_id',
            'auction_account_id' => 'account_id',
            'auction_type' => 'type',
            'auction_listed_at' => 'listed_at'
          )
        )
      ->where($adp->prepareSqlCondition($auctionType, array('null' => true)))
      ->group('e.entity_id');

    return $collection;
  }

  /**
   * Filter out products which have any auction
   *
   * @param  Mage_Eav_Model_Entity_Collection_Abstract $collection Products
   * @return Mage_Eav_Model_Entity_Collection_Abstract Collection of products
   */
  public function filterAllAuctions ($collection) {
    $adp = $this->_getReadAdapter();
    $table = $this->getMainTable();

    $entityId = $adp->quoteIdentifier(array('e', 'entity_id'));
    $productId = $adp->quoteIdentifier(array('auction', 'product_id'));

    $collection
      ->getSelect()
      ->joinLeft(
          //Table name
          array('auction' => $this->getMainTable()),

          //Conditions
          $entityId . ' = ' . $productId,

          //Columns
          array(
            'auction_listing_id' => 'listing_id',
            'auction_account_id' => 'account_id',
            'auction_type' => 'type',
            'auction_listed_at' => 'listed_at'
          )
        )
      ->where($adp->prepareSqlCondition($productId, array('null' => true)))
      ->group('auction.product_id');

    return $collection;
  }

  /**
   * Perform actions before object save
   *
   * @param Varien_Object $object
   * @return Mage_Core_Model_Resource_Db_Abstract
   */
  protected function _beforeSave (Mage_Core_Model_Abstract $object) {
    if ($object->isObjectNew() && $object['listed_at'] === null)
      $object['listed_at'] = Varien_Date::now();

    return parent::_beforeSave($object);
  }
}
