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
 * Source model for List full price field
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Setting_Source_Listfullprice
{

  /**
   * Options getter
   *
   * @return array
   */
  public function toOptionArray () {
    $helper = Mage::helper('trademe');

    return array(
      array('label' => $helper->__('Always'), 'value' => 0),
      array('label' => $helper->__('If stock allows'), 'value' => 1),
      array('label' => $helper->__('Never'), 'value' => 2),
    );
  }
}

?>
