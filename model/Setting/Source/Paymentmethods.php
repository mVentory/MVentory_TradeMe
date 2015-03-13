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
 * @copyright Copyright (c) 2015 mVentory Ltd. (http://mventory.com)
 * @license http://creativecommons.org/licenses/by-nc-nd/4.0/
 */

/**
 * Source model for payment methods setting
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Setting_Source_Paymentmethods
{

  /**
   * Options getter
   *
   * @return array
   */
  public function toOptionArray () {
    $helper = Mage::helper('trademe');

    return array(
      array(
        'value' => MVentory_TradeMe_Model_Config::PAYMENT_BANK,
        'label' => $helper->__('NZ bank deposit')
      ),
      array(
        'value' => MVentory_TradeMe_Model_Config::PAYMENT_CC,
        'label' => $helper->__('Credit card')
      ),
      array(
        'value' => MVentory_TradeMe_Model_Config::PAYMENT_CASH,
        'label' => $helper->__('Cash')
      ),
      array(
        'value' => MVentory_TradeMe_Model_Config::PAYMENT_SAFE,
        'label' => $helper->__('Safe Trader')
      ),
    );
  }
}

?>
