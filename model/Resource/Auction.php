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
}
