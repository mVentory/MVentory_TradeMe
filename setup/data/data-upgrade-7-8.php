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


$conn = $this->getConnection();
$res = Mage::getSingleton('core/resource');
$helper = Mage::helper('core');

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

  $_shippingTypes = [];

  foreach ($shippingTypes as $settings) {
    $shippingTypeId = $settings['shipping_type'];
    $weight = (int) $settings['weight'];

    unset(
      $settings['shipping_type'],
      $settings['weight'],
      $settings['minimal_price']
    );

    if ($weight > 0)
      $_shippingTypes[$shippingTypeId]['condition'] = 'weight';
    else
      $_shippingTypes[$shippingTypeId]['condition'] = '';

    $_shippingTypes[$shippingTypeId]['settings'][$weight] = $settings;
  }

  $value = $helper->jsonEncode($_shippingTypes);;
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

/**
 * Convert the way we store oauth token. Previously we store oauth token as
 * serialized array under 'access_token' setting per TM account.
 * Now we store keys from oauth token in separate settings per TM account.
 *
 * Following code loads all records for 'access_token' setting from DB,
 * unserializes and normalizes its value, then saves value of each oauth key
 * in separate setting with same parameters as in corresponding 'access_token'
 * setting.
 */

$conn = $this->getConnection();
$res = Mage::getSingleton('core/resource');
$table = $res->getTableName('core/config_data');

//Get all rows for access_token setting from DB
$select = $conn
  ->select()
  ->from($table)
  ->where($conn->prepareSqlCondition(
      $conn->quoteIdentifier(array($table, 'path')),
      array('like' => 'trademe/account%/access_token')
    ));

$rows = $conn->fetchAssoc($select);

if ($rows) {
  $keys = [
    Zend_Oauth_Token::TOKEN_PARAM_KEY => '',
    Zend_Oauth_Token::TOKEN_SECRET_PARAM_KEY => ''
  ];

  $this->startSetup();

  foreach ($rows as $configId => $setting) {
    if (($tokens = unserialize($setting['value'])) === false)
      continue;

    //Remove 'access_token' part from setting's path
    //Remained part is used to make paths for settings of oauth tokens
    $path = substr($setting['path'], 0, -12);

    //Get only values for oauth keys from unserialized array,
    //normalize list of keys with empty values
    $tokens = array_merge($keys, array_intersect_key($tokens, $keys));

    //Add values for settings of oauth tokens
    foreach ($tokens as $key => $value)
      $conn->insert(
        $table,
        [
          'scope' => $setting['scope'],
          'scope_id' => $setting['scope_id'],
          'path' => $path . $key,
          'value' => $value
        ]
      );
  }

  //Delete all access_token settings
  $conn->delete(
    $table,
    $conn->prepareSqlCondition(
      $conn->quoteIdentifier([$table, 'config_id']),
      ['in' => array_keys($rows)]
    )
  );

  $this->endSetup();
}
