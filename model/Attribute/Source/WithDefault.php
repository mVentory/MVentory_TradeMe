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
 * Entity/Attribute/Model - attribute selection source abstract with default
 * value
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
abstract class MVentory_TradeMe_Model_Attribute_Source_WithDefault
  extends MVentory_TradeMe_Model_Attribute_Source_Abstract
{
  /**
   * Value of Default option
   * @var integer
   */
  protected $_defaultValue = -1;

  /**
   * Label of Default option
   * @var string
   */
  protected $_defaultLabel = 'Default';

  /**
   * Initiates options array for getAllOptions() method with Default options
   * on first position
   *
   * @return array
   */
  protected function _initAllOptionsArray () {
    return array(
      array(
        'value' => $this->_defaultValue,
        'label' => Mage::helper('trademe')->__($this->_defaultLabel)
      )
    );
  }
}
