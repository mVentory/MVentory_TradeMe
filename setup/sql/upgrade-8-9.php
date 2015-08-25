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

$attrs = [
  'tm_clearance' => [
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'input' => 'select',
    'label' => 'Clearance',
    'source' => 'eav/entity_attribute_source_boolean',
    'required' => false,
    'default' => 0,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'is_configurable' => false
  ]
];

$this->startSetup();

$this->addAttributes($attrs);

$this->endSetup();
