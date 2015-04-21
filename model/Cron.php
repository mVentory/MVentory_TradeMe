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
 * @copyright Copyright (c) 2015 mVentory Ltd. (http://mventory.com)
 * @license Commercial
 */

/**
 * Cron jobs
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Cron
{
  /**
   * Cron function to withdraw active listing when related products
   * become out of stock
   *
   * @param Mage_Cron_Model_Schedule $job
   *   Cron job model
   *
   * @return MVentory_TradeMe_Model_Cron
   *   Instance of this class
   */
  public function automatedWithdrawal ($job) {
    $helper = Mage::helper('trademe');
    $connector = new MVentory_TradeMe_Model_Api();
    $websites = Mage::app()->getWebsites();

    $withdrawnAuctions = array();

    foreach ($websites as $websiteId => $website) {
      if (!$accounts = $helper->getAccounts($website, false))
        continue;

      if (!$skus = $this->_getSkus(array($websiteId)))
        continue;

      $websiteCode = $website->getCode();

      $connector->setWebsiteId($website);

      foreach ($accounts as $accountId => $account) {
        $connector->setAccountId($accountId);

        try {
          $auctions = $connector->getAllSellingAuctions(10000);
        }
        catch (Exception $e) {
          Mage::logException($e);

          MVentory_TradeMe_Model_Log::debug(array(
            'website' => $websiteCode,
            'account' => $accountId,
            'error on getting selling auctions' => $e->getMessage(),
            'error' => true
          ));

          continue;
        }

        if (!$auctions)
          continue;

        foreach ($auctions['List'] as $auction) {
          if (!(isset($auction['SKU'])
                && ($sku = $auction['SKU'])
                && isset($skus[$sku])))
            continue;

          try {
            $result = $connector->remove(array(
              'account_id' => $accountId,
              'listing_id' => $auction['ListingId']
            ));
          }
          catch (Exception $e) {
            Mage::logException($e);
            $result = $e->getMessage();
          }

          if ($result === true)
            $withdrawnAuctions[] = $auction['ListingId'];

          MVentory_TradeMe_Model_Log::debug(array(
            'website' => $websiteCode,
            'account' => $accountId,
            'sku' => $sku,
            'auction' => $auction['ListingId'],
            'result' => $result,
            'error' => $result !== true
          ));
        }
      }
    }

    if ($withdrawnAuctions)
      $this->_unlinkAuctions($withdrawnAuctions);

    return $this;
  }

  /**
   * Get SKU fro all out of stock products which are allowed for TradeMe
   * and are assigned to the specified website ID
   *
   * @param array $website
   *   Website IDs to filter product
   *
   * @return array
   *   List of SKU of out of stock products
   */
  protected function _getSkus ($website) {
    return $collection = Mage::getResourceModel('trademe/product_collection')
      ->addAttributeToFilter('type_id', 'simple')

      //Load all allowed to list products (incl. for $1 dollar auctions)
      ->addAttributeToFilter('tm_relist', array('gt' => 0))
      ->addAttributeToFilter('image', array('nin' => array('no_selection', '')))
      ->addAttributeToFilter(
          'status',
          Mage_Catalog_Model_Product_Status::STATUS_ENABLED
        )
      ->addWebsiteFilter($website)
      ->joinField(
          'inventory_in_stock',
          'cataloginventory/stock_item',
          'is_in_stock',
          'product_id=entity_id',
          '{{table}}.is_in_stock = 0'
        )
      ->getAllSkus();
  }

  /**
   * Unlink auctions from related pruducts by supplied list of listing IDs
   *
   * @param array $auctions
   *   List of listing IDs to unlink
   *
   * @return MVentory_TradeMe_Model_Cron
   *   Instance of this class
   */
  protected function _unlinkAuctions ($auctions) {
    Mage::getResourceModel('trademe/auction_collection')
      ->addFieldToFilter('listing_id', array('in' => $auctions))
      ->delete();

    return $this;
  }
}