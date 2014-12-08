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
 * Grid of the TradeMe options for CSV generation
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Block_Options
  extends Mage_Adminhtml_Block_Widget_Grid {

  const TYPE_TEXT = 0;
  const TYPE_INT = 1;
  const TYPE_BOOL = 2;
  const TYPE_PRICE = 3;

  protected $_helper = null;
  protected $_options = null;

  //Cache some data
  private $_addfeesValues = null;

  /**
   * Define grid properties
   *
   * @return void
   */
  public function __construct () {
    parent::__construct();

    $this->setId('optionsGrid');

    $this->_exportPageSize = 10000;

    $this->_helper = Mage::helper('trademe');

    $this->_options = array(
      'account_name' => array(
        'label' => 'Account',
        'type' => self::TYPE_TEXT
      ),
      'shipping_type' => array(
        'label' => 'Shipping type',
        'type' => self::TYPE_TEXT
      ),
      'minimal_price' => array(
        'label' => 'Minimal price',
        'type' => self::TYPE_PRICE
      ),
      'free_shipping_cost' => array(
        'label' => 'Free shipping cost',
        'type' => self::TYPE_PRICE
      ),
      'allow_buy_now' => array(
        'label' => 'Allow Buy Now',
        'type' => self::TYPE_BOOL
      ),
      'avoid_withdrawal' => array(
        'label' => 'Avoid withdrawal',
        'type' => self::TYPE_BOOL
      ),
      'add_fees' => array(
        'label' => 'Add fees',
        'type' => self::TYPE_TEXT,
        'prepare' => '_prepareAddfeesValue'
      ),
      'allow_pickup' => array(
        'label' => 'Allow pickup',
        'type' => self::TYPE_BOOL
      ),
      'category_image' => array(
        'label' => 'Add category image',
        'type' => self::TYPE_BOOL
      ),
      'buyer' => array(
        'label' => 'Buyer ID',
        'type' => self::TYPE_INT
      ),
      'duration' => array(
        'label' => 'Listing duration',
        'type' => self::TYPE_INT
      ),
      'shipping_options' => array(
        'label' => 'Shipping options',
        'type' => self::TYPE_TEXT,
        'prepare' => '_exportShippingOptions'
      ),
      'footer' => array(
        'label' => 'Footer description',
        'type' => self::TYPE_TEXT
      )
    );
  }

  /**
   * Prepare options collection
   *
   * @return MVentory_TradeMe_Block_Options
   */
  protected function _prepareCollection () {
    $collection = new Varien_Data_Collection();

    $accounts = Mage::helper('trademe')->getAccounts(
      $this->getWebsiteId(),
      false
    );

    $_shippingTypes = $this->_getShippingTypes();

    if (count($accounts) && count($_shippingTypes))
      foreach ($accounts as $account) {
        $hasShippingTypes = isset($account['shipping_types'])
                            && count($account['shipping_types']);

        $shippingTypes = $hasShippingTypes
                           ? $account['shipping_types']
                             : $this->_fillShippingTypes($_shippingTypes);

        foreach ($shippingTypes as $id => $options) {
          $options['allow_buy_now'] = (int) $options['allow_buy_now'];

          $row = $options + array(
            'account_name' => $account['name'],
            'shipping_type' => $_shippingTypes[$id]
          );

          foreach ($row as $optionId => $optionValue) {
            if (!isset($this->_options[$optionId]))
              continue;

            $option = $this->_options[$optionId];

            if (isset($option['prepare']))
              $optionValue = call_user_func(
                array($this, $option['prepare']),
                $optionValue
              );

            switch ($option['type']) {
              case self::TYPE_INT:
              case self::TYPE_BOOL:
                $optionValue = (int) $optionValue;
                break;
              case self::TYPE_PRICE:
                $optionValue = number_format((float) $optionValue, 2);
            }

            $row[$optionId] = $optionValue;
          }

          $collection->addItem(new Varien_Object($row));
        }
      }

    $this->setCollection($collection);

    return parent::_prepareCollection();
  }

  /**
   * Return array with empty options for all shipping types
   *
   * @param array $shippingTypes
   * @return array
   */
  protected function _fillShippingTypes ($shippingTypes) {
    $emptyOptions = array_fill_keys(array_keys($this->_options), '');

    unset($emptyOptions['account_name'], $emptyOptions['shipping_type']);

    return array_fill_keys(array_keys($shippingTypes), $emptyOptions);
  }

  /**
   * Return all shipping options
   *
   * @return array
   */
  protected function _getShippingTypes () {
    $types
      = Mage::getModel('mventory/system_config_source_allowedshippingtypes')
          ->toArray();

    //There's 2 special values:
    //  - * - means any shipping type in product
    //  - <empty> - means no shipping type is set in product
    $types['*'] = '*';
    $types[''] = '';

    return $types;
  }

  /**
   * Return all values for Add fees option
   *
   * @return array
   */
  protected function _getAddfeesValues () {
    if ($this->_addfeesValues !== null)
      return $this->_addfeesValues;

    $this->_addfeesValues = Mage::getModel('trademe/attribute_source_addfees')
      ->toArray();

    return $this->_addfeesValues;
  }

  /**
   * Prepare table columns
   *
   * @return MVentory_TradeMe_Block_Options
   */
  protected function _prepareColumns () {
    foreach ($this->_options as $id => $option)
      $this->addColumn($id, array(
        'header' => $this->_helper->__($option['label']),
        'index' => $id
      ));

    return parent::_prepareColumns();
  }

  /**
   * Convert list of shipping options to string
   *
   * String format:
   *
   *   <price>,<method>\r\n
   *   ...
   *   <price>,<method>
   *
   * @param string $options Shipping options
   * @return string
   */
  protected function _exportShippingOptions ($options) {
    if (!is_array($options))
      return $options;

    $_options = '';

    foreach ($options as $option)
      $_options .= "\r\n" . $option['price'] . ',' . $option['method'];

    return substr($_options, 2);
  }

  /**
   * Prepare value of Add fees option for export
   *
   * @param $value int Value to prepare
   * @return string
   */
  protected function _prepareAddfeesValue ($value) {
    $options = $this->_getAddfeesValues();

    return isset($options[$value])
             ? $options[$value]
               : $options[MVentory_TradeMe_Model_Config::FEES_NO];
  }
}
