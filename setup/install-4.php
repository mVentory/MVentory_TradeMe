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

$attrs = array(
  'tm_listing_id' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'hidden',
    'label' => 'Previous listing ID',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'visible' => false,
    'is_configurable' => false
  ),

  'tm_current_listing_id' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'hidden',
    'label' => 'Listing ID',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'used_in_product_listing' => true,
    'is_configurable' => false
  ),

  'tm_account_id' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'input' => 'select',
    'label' => 'Previous account ID',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'visible' => false,
    'is_configurable' => false
  ),

  'tm_current_account_id' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'input' => 'hidden',
    'label' => 'Account ID',
    'source' => 'trademe/attribute_source_accounts',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'is_configurable' => false
  ),

  'tm_relist' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Allow to list',
    'source' => 'eav/entity_attribute_source_boolean',
    'required' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'is_configurable' => false
  ),

  'tm_avoid_withdrawal' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Avoid withdrawal',
    'source' => 'trademe/attribute_source_boolean',
    'required' => false,
    'default' => -1,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'is_configurable' => false
  ),

  'tm_shipping_type' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Use free shipping',
    'source' => 'trademe/attribute_source_freeshipping',
    'required' => false,
    'default' => -1,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'visible' => true,
    'is_configurable' => false
  ),

  'tm_allow_buy_now' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Allow Buy Now',
    'source' => 'trademe/attribute_source_boolean',
    'required' => false,
    'default' => -1,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'visible' => true,
    'is_configurable' => false
  ),

  'tm_add_fees' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Add Fees',
    'source' => 'trademe/attribute_source_addfees',
    'required' => false,
    'default' => -1,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'visible' => true,
    'is_configurable' => false
  ),

  'tm_pickup' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Pickup',
    'source' => 'trademe/attribute_source_pickup',
    'required' => false,
    'default' => -1,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'visible' => true,
    'is_configurable' => false
  ),

  'tm_condition' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Condition',
    'source' => 'eav/entity_attribute_source_table',
    'required' => false,
    'user_defined' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'filterable' => true,
    'visible_on_front' => true,
    'is_html_allowed_on_front' => true,

    'option' => array('values' => array('New', 'Near New', 'Used'))
  )
);

$this->startSetup();

$this->addAttributes($attrs);

$this->createTable('matching_rules');
$this->createTable('auction');

$this->endSetup();
