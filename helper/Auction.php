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
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */

/**
 * Auction helper
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Helper_Auction extends MVentory_TradeMe_Helper_Data
{
  public function getEndTimePeriod ($store) {
    $endTime = $store->getConfig(MVentory_TradeMe_Model_Config::_1AUC_ENDTIME);

    if (!$endTime)
      return;

    $endTime = DateTime::createFromFormat(
      'H:i',
      $endTime,
      new DateTimeZone(
        $store->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE)
      )
    );

    $int = new DateInterval('PT30M');
    $start = clone $endTime;

    return array($start->sub($int), $endTime->add($int));
  }

  public function isAuctionDay ($store) {
    $endDays = $store->getConfig(MVentory_TradeMe_Model_Config::_1AUC_ENDDAYS);

    if ($endDays === '')
      return false;

    $duration = $store->getConfig(
      MVentory_TradeMe_Model_Config::_1AUC_DURATION
    );

    if ($duration === '')
      return false;

    $endDays = explode(',', $endDays);
    $duration = (int) $duration;

    $now = new DateTime(
      'now',
      new DateTimeZone(
        $store->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE)
      )
    );

    $dow = (int) $now->format('w');

    foreach ($endDays as $endDay) {
      if (($day = $endDay - $duration) < 0)
        $day = 7 + $day;

      if ($dow == $day)
        return true;
    }

    return false;
  }

  public function isInAllowedPeriod ($store) {
    if (!$period = $this->getEndTimePeriod($store))
      return false;

    list($start, $end) = $period;

    $now = new DateTime(
      'now',
      new DateTimeZone(
        $store->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE)
      )
    );

    return ($start < $now) && ($now < $end) && $this->isAuctionDay($store);
  }

  public function getProductsListedToday ($store, $type) {
    $defTz = new DateTimeZone(date_default_timezone_get());
    $tz = new DateTimeZone(
      $store->getConfig(Mage_Core_Model_Locale::XML_PATH_DEFAULT_TIMEZONE)
    );

    return Mage::getResourceSingleton('trademe/auction')->getListedProducts(
      $type,
      DateTime::createFromFormat('H', '00', $tz)
        ->setTimezone($defTz)
        ->format(Varien_Date::DATETIME_PHP_FORMAT),
      DateTime::createFromFormat('H:i:s', '23:59:59', $tz)
        ->setTimezone($defTz)
        ->format(Varien_Date::DATETIME_PHP_FORMAT)
    );
  }

  /**
   * Return title of auction choosen randomly from the set of name variants and
   * product's name
   *
   * @param Mage_Catalog_Model_Product $product Product
   * @param Mage_Core_Model_Store $store Store
   * @return string Auctin title
   */
  public function getTitle ($product, $store) {
    $title = $product->getName();

    $code = trim(
      $store->getConfig(MVentory_TradeMe_Model_Config::_NAME_VARIANTS_ATTR)
    );

    if (!$code)
      return $title;

    if (!$_titles = trim($product[strtolower($code)]))
      return $title;

    $_titles = explode("\n", str_replace("\r\n", "\n", $_titles));

    foreach ($_titles as $_title)
      if ($_title = trim($_title))
        $titles[] = $_title;

    if (!isset($titles))
      return $title;

    $titles[] = $title;

    return $titles[array_rand($titles)];
  }
}
