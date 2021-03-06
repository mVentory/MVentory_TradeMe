<?php

/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a Commercial Software License.
 * No sharing - This file cannot be shared, published or
 * distributed outside of the licensed organisation.
 * No Derivatives - You can make changes to this file for your own use,
 * but you cannot share or redistribute the changes.
 * This Copyright Notice must be retained in its entirety.
 * The full text of the license was supplied to your organisation as
 * part of the licensing agreement with mVentory.
 *
 * @package MVentory/TradeMe
 * @copyright Copyright (c) 2014-2015 mVentory Ltd. (http://mventory.com)
 * @license Commercial
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */

/**
 * Auction collection
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */

class MVentory_TradeMe_Model_Resource_Auction_Collection
  extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
  /**
   * Initialise collection model
   */
  protected function _construct () {
    $this->_init('trademe/auction');
  }

  /**
   * Delete all auctions in the collection
   *
   * @return MVentory_TradeMe_Model_Resource_Auction_Collection
   *   Instance of this class
   */
  public function delete () {
    foreach ($this->getItems() as $item)
      $item->delete();

    return $this;
  }
}
