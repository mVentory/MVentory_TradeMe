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
 * Source model for authorization type
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Setting_Source_Authtype
{

  /**
   * Options getter
   *
   * @return array
   */
  public function toOptionArray () {
    $helper = Mage::helper('trademe');

    return [
      [
        'label' => $helper->__('Using consumer keys'),
        'value' => MVentory_TradeMe_Model_Config::AUTH_CONSUMER_KEYS
      ],
      [
        'label' => $helper->__('Using pre-generated keys'),
        'value' => MVentory_TradeMe_Model_Config::AUTH_PREGEN_KEYS
      ]
    ];
  }
}

?>
