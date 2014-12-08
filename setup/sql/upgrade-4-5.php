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
  'tm_fixedend_limit' => array(
    //Fields from Mage_Eav_Model_Entity_Setup
    'type' => 'int',
    'label' => '$1 auctions limit',
    'frontend_class' => 'validate-digits',
    'required' => false,
    'user_defined' => true,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,

    //Fields from Mage_Catalog_Model_Resource_Setup
    'is_configurable' => false
  )
);

$this->startSetup();

$this->addAttributes($attrs);

$this->endSetup();
