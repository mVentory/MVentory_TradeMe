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
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */

/**
 * TradeMe attribute helper
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Helper_Attribute extends MVentory_TradeMe_Helper_Data
{
  /**
   * List of allowed magento attributes
   * @var array
   */
  protected $_allowedTypes = [
    'text' => true,
    'textarea' => true,
    'select' => true,
    'multiselect' => true
  ];

  /**
   * Return list of TM attributes with their values from the supplied product.
   * Format of return data:
   *
   *   [
   *     //Shows if error happened during preparing list of aTM attributes with
   *     //values.
   *     'error' => false,
   *
   *     //Name of required TM attribute which value doesn't exists
   *     //in the supplied product.
   *     //OPTIONAL field, it's set when error is true
   *     'required' => 'CPUSpeed',
   *
   *     //Pairs of TM atribute name and its value from the supplied product
   *     'attributes' => [
   *       'Memory' => '2',
   *
   *       ...
   *     ]
   *   ]
   * @param Mage_Catalog_Model_Product $product
   *   Product model
   *
   * @param array $attrs
   *   List of TM attributes for product's TM category
   *
   * @param Mage_Core_Model_Store $store
   *   Store for TM attributes matching
   *
   * @return array
   *   List of TM attributes with their values or error
   */
  public function fillAttributes ($product, $attrs, $store) {
    $storeId = $store->getId();

    //Prepare list of TM attributes and use attribute name in lower case as
    //array's key for faster and case-independent search
    foreach ($attrs as $attr)
      $_attrs[strtolower($attr['Name'])] = $attr;

    unset($attrs);

    //Iterater over all product's attributes to find which should be mapped to
    //TM attributes and collect them
    foreach ($product->getAttributes() as $pAttr) {

      /**
       * Check if magento has allowed type
       *
       * @see MVentory_TradeMe_Helper_Attribute::_allowedTypes
       *   For list if allowed attribute types
       */
      $input = $pAttr->getFrontendInput();
      if (!isset($this->_allowedTypes[$input]))
        continue;

      //Get TM attributes name-value pairs. Got to next Magento attribute if
      //current one doesn't have matched TM attributes
      $data = $this->_getTmAttrData($product, $pAttr, $storeId);
      if (!$data)
        continue;

      foreach ($data as $pair) {
        //Convert name of TM attribute lo lower case to make case-insesitive
        //search in the list of TM attirbutes
        $name = strtolower($pair['name']);
        if (!isset($_attrs[$name]))
          continue;

        $attr = $_attrs[$name];
        $value = $pair['value'];

        //Return error if TM attribute is required and if doesn't have value in
        //the supplied product
        if (!$value && $attr['IsRequiredForSell'])
          return array(
            'error' => true,
            'required' => $attr['DisplayName']
          );

        $result[$attr['Name']] = $value;
      }
    }

    return array(
      'error' => false,
      'attributes' => isset($result) ? $result : null
    );
  }

  /**
   * Return TM attribute name and its value for supplied product and attribute
   *
   * @param Mage_Catalog_Model_Product $product
   *   Product model
   *
   * @param Mage_Catalog_Model_Resource_Eav_Attribute $attr
   *   Attribute model
   *
   * @param int $storeId
   *   Store ID for TM attributes matching
   *
   * @return array|null
   *   Pair of TM attribute and its value or null if name and/or value can't be
   *   obtained
   */
  protected function _getTmAttrData ($product, $attr, $storeId) {
    //Try to get TM attributes' name and value from Magento's attribute label
    //used in store for TM attributes mapping.
    if ($data = $this->_getDataFromLabel($product, $attr, $storeId))
      return $data;

    //Otherwise check if Magento attribute is dropbdown or multiselect
    //attribute
    $input = $attr->getFrontendInput();

    if (!($input == 'select' || $input == 'multiselect'))
      return;

    //and return TM attributes' name and value from Magento attribute's option
    //label
    return $this->_getDataFromValue($product, $attr, $storeId);
  }

  /**
   * Return name and value for TM attributes from supplied Magento's attribute
   * label used in store for TM attributes mapping
   *
   * @param Mage_Catalog_Model_Product $product
   *   Product model
   *
   * @param Mage_Catalog_Model_Resource_Eav_Attribute $attr
   *   Attribute model
   *
   * @param int $storeId
   *   Store ID for TM attributes matching
   *
   * @return array|null
   *   List of name and value pairs for TM attributes
   */
  protected function _getDataFromLabel ($product, $attr, $storeId) {

    //Get attribute labels for all stores
    $labels = $attr->getStoreLabels();
    if (!isset($labels[$storeId]))
      return;

    //Ignore empty label
    $names = trim($labels[$storeId]);
    if (!$names)
      return;

    //Split attribute label by comma to get list of all TM attributes names
    $names = explode(',', trim($labels[$storeId]));
    if (!$names)
      return;

    //Get value of Magento attribute in supplied product.
    //Return empty string for attributes without values in the products because
    //Magento will return 'No' for such attributes
    $value = $product[$attr->getAttributeCode()]
               ? $attr->getFrontend()->getValue($product)
               : '';

    //Ignore TM attributes with empty value. TradeMe will use default one
    if ($value === '')
      return [];

    $data = [];

    //Go through list of TM atrributes names and assign Magento attribute value
    //to every TM attribute
    array_walk(
      $names,
      function ($name) use (&$data, $value) {

        //Ignore empty names
        $name = trim($name);
        if (!$name)
          return;

        $data[] = [
          'name' => $name,
          'value' => $value
        ];
      }
    );

    return $data;
  }

  /**
   * Return name and value for TM attributes from supplied Magento's attribute
   * option label used in store for TM attributes mapping
   *
   * @param Mage_Catalog_Model_Product $product
   *   Product model
   *
   * @param Mage_Catalog_Model_Resource_Eav_Attribute $attr
   *   Attribute model
   *
   * @param int $storeId
   *   Store ID for TM attributes matching
   *
   * @return array|null
   *   List of name-value pairs of TM attribute
   */
  protected function _getDataFromValue ($product, $attr, $storeId) {
    $frontend = $attr->getFrontend();

    //Remember value of the supplied attribute in current store
    $defaultValue = $frontend->getValue($product);
    //Remeber current store ID to restore it later
    $_storeId = $attr->getStoreId();

    //Change attribute's store ID to store used for TM attributes matching
    //and get value for the attribute, restore original store ID
    $attr->setStoreId($storeId);
    $value = $frontend->getValue($product);
    $attr->setStoreId($_storeId);

    //Similar values mean we probably don't have data for TM attributes mathing,
    //return nothing
    if ($defaultValue == $value)
      return;

    //Ignore empty value
    $value = trim($value);
    if (!$value)
      return;

    //Split name-value pairs by comma. Comma is used to specify multiple TradeMe
    //attributes for one to many mapping
    $pairs = explode(',', $value);
    if (!$pairs)
      return;

    $data = [];

    //Go through list of TM atrributes's name-value pairs separated by column
    //and convert them to the list of name-value pair
    array_walk(
      $pairs,
      function ($pair) use (&$data) {

        //Ignore empty pairs
        $pair = trim($pair);
        if (!$pair)
          return;

        //Extract TM attribute name and value from Magento's attribute option
        //label. We use column as seaparator for them
        $parts = explode(':', $pair, 2);

        //Ignore pairs which don't follow format or have no TM attribute
        //name before the separator
        if (!(count($parts) == 2 && $parts[0]))
          return;

        //Ignore TM attributes with empty value. TradeMe will use default one
        $value = ltrim($parts[1]);
        if ($value === '')
          return;

        $data[] = [
          'name' => rtrim($parts[0]),
          'value' => $value
        ];
      }
    );

    return $data;
  }
};