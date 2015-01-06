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

$conn = $this->getConnection();
$res = Mage::getSingleton('core/resource');

$table = $res->getTableName('core/config_data');

$select = $conn
  ->select()
  ->from($table, array('config_id', 'value'))
  ->where($conn->prepareSqlCondition(
      $conn->quoteIdentifier(array($table, 'path')),
      array('like' => 'trademe/account%/shipping_types')
    ));

$data = $conn->fetchPairs($select);

foreach ($data as $configId => &$value) {
  if (($shippingTypes = unserialize($value)) === false) {
    unset($data[$configId]);

    continue;
  }

  foreach ($shippingTypes as $id => $settings) {
    $settings['shipping_type'] = $id;
    $settings['weight'] = '';

    $_shippingTypes[] = $settings;
  }

  $value = serialize($_shippingTypes);
}

unset($value);

if ($data) {
  $configIdent = $conn->quoteIdentifier(array($table, 'config_id'));

  $this->startSetup();

  foreach ($data as $configId => $value)
    $conn->update(
      $table,
      array('value' => $value),
      $conn->prepareSqlCondition(
        $configIdent,
        $configId
      )
    );

  $this->endSetup();
}
