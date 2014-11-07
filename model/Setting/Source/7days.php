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
 * Source model for duration field
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Setting_Source_7days
{

  /**
   * Options getter
   *
   * @return array
   */
  public function toOptionArray () {
    $helper = Mage::helper('trademe');

    return array(
      //Temporarily disabled because TradeMe doesn't support such predefined
      //duration
      //array('label' => $helper->__('1 day'), 'value' => 1),

      array('label' => $helper->__('2 days'), 'value' => 2),
      array('label' => $helper->__('3 days'), 'value' => 3),
      array('label' => $helper->__('4 days'), 'value' => 4),
      array('label' => $helper->__('5 days'), 'value' => 5),
      array('label' => $helper->__('6 days'), 'value' => 6),
      array('label' => $helper->__('7 days'), 'value' => 7)
    );
  }
}

?>
