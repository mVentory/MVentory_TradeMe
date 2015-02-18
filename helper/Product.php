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
 * @copyright Copyright (c) 2014 mVentory Ltd. (http://mventory.com)
 * @license Commercial
 */

/**
 * Product helper
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Helper_Product extends MVentory_TradeMe_Helper_Data
{
  /**
   * Return name variants for the supplied product
   *
   * @param Mage_Catalog_Model_Product $product
   *   Product model
   *
   * @param Mage_Core_Model_Store $store
   *   Store model
   *
   * @return array
   *   List of name variants
   */
  public function getNameVariants ($product, $store) {
    $code = trim(
      $store->getConfig(MVentory_TradeMe_Model_Config::_NAME_VARIANTS_ATTR)
    );

    if (!$code)
      return array();

    if (!$_names = trim($product[strtolower($code)]))
      return array();

    $_names = explode("\n", str_replace("\r\n", "\n", $_names));

    $names = array();

    foreach ($_names as $_name)
      if ($_name = trim($_name))
        $names[] = $_name;

    return $names;
  }
}