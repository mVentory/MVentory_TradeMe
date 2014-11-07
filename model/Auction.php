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
 * Auction model
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Auction extends Mage_Core_Model_Abstract
{

  protected $_product = null;

  /**
   * Initialise resources
   */
  protected function _construct () {
    $this->_init('trademe/auction');
  }

  /**
   * Load auction data by product
   *
   * @param mixed $product
   * @return MVentory_TradeMe_Model_Auction
   */
  public function loadByProduct ($product) {
    $this
      ->_getResource()
      ->loadByProductId(
          $this,
          $product instanceof Mage_Catalog_Model_Product
            ? $product->getId()
            : $product
        );

    return $this->setOrigData();
  }

  public function assignProduct ($product) {
    $this->_product = $product;

    return $this;
  }

  /**
   * Return URL to auction
   *
   * @param Mage_Core_Model_Website $website Website
   * @return string Auction URL
   */
  public function getUrl ($website = null) {
    if (!$website)
      $website = $this->_getWebsite();

    return
      ($id = $this->_data['listing_id'])
        ? 'http://www.'
          . (Mage::helper('trademe')->isSandboxMode($website)
               ? 'tmsandbox'
               : 'trademe'
            )
          . '.co.nz/Browse/Listing.aspx?id='
          . $id
        : '';
  }

  public function withdraw () {
    //!!!TODO: implement withdrawal by listing ID only
    if (!$this['product_id'])
      return $this;

    //Withdrawal for auctions with fixed end date ($1 auctions) are temporarily
    //disabled
    if ($this['type'] == MVentory_TradeMe_Model_Config::AUCTION_FIXED_END_DATE)
      return $this;

    $product = Mage::getModel('catalog/product')->load($this['product_id']);

    if (!$product->getId())
      throw new Mage_Core_Exception('Can\'t load product');

    $this->assignProduct($product);

    $connector = new MVentory_TradeMe_Model_Api();

    $result = $connector
      ->setWebsiteId($this->_getWebsite())
      ->remove($this);

    if ($result !== true)
      throw new Mage_Core_Exception($result);

    return $this;
  }

  protected function _getWebsite () {
    return $this->_product
             ? Mage::helper('mventory/product')->getWebsite($this->_product)
             : Mage::app()->getWebsite();
  }
}
