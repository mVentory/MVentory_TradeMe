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
  'tm_relist',
  'tm_avoid_withdrawal',
  'tm_shipping_type',
  'tm_allow_buy_now',
  'tm_add_fees',
  'tm_pickup',
  'tm_condition',
  'tm_fixedend_limit'
);

$entityTypeId = $this->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$setIds = $this->getAllAttributeSetIds($entityTypeId);

$this->startSetup();

foreach ($setIds as $setId) {
  $this->addAttributeGroup($entityTypeId, $setId, 'TM');
  $groupId = $this->getAttributeGroupId($entityTypeId, $setId, 'TM');

  foreach ($attrs as $attr)
    $this->addAttributeToGroup($entityTypeId, $setId, $groupId, $attr);
}

$this->endSetup();
