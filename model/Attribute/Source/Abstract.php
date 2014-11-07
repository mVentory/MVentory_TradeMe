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
 * Entity/Attribute/Model - attribute selection source abstract
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
abstract class MVentory_TradeMe_Model_Attribute_Source_Abstract
  extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{

  /**
   * Array of prepared options in value => label format
   * @var array
   */
  protected $_opts = null;

  /**
   * Generate array of options in following format: array('value' => 'label')
   *
   * @return array
   */
  abstract protected function _getOptions ();

  /**
   * Retrieve All options
   *
   * @return array
   */
  public function getAllOptions () {
    if ($this->_options == null) {
      $helper = Mage::helper('trademe');

      $options = $this->_initAllOptionsArray();

      foreach ($this->_getOptions() as $value => $label)
        $options[] = array(
          'label' => $helper->__($label),
          'value' => $value
        );

      $this->_options = $options;
    }

    return $this->_options;
  }

  /**
   * Retrieve Option value text
   *
   * @param string $value
   * @return mixed
   */
  public function getOptionText ($value) {
    return (($options = $this->_getOptions()) && isset($options[$value]))
             ? Mage::helper('trademe')->__($options[$value])
             : false;
  }

  /**
   * Retrieve all options as value => label array
   *
   * @return array
   */
  public function toOptionArray () {
    if ($this->_opts == null) {
      $helper = Mage::helper('trademe');

      $opts = $this->_getOptions();

      foreach ($opts as $value => $label)
        $opts[$value] = $helper->__($label);

      $this->_opts = $opts;
    }

    return $this->_opts;
  }

  /**
   * Retrieve original array of options
   *
   * @return array
   */
  public function toArray () {
    return $this->_getOptions();
  }

  /**
   * Initiates options array for getAllOptions() method
   *
   * @return array
   */
  protected function _initAllOptionsArray () {
    return array();
  }
}
