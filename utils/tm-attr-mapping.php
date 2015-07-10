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
 */

/**
 * This utility adds mapping between Magento attribute and TradeMe
 * attribute when creates attribute option.
 *
 * Options are supplied in CSV file with following format:
 *
 *   "mageto option 1","trademe attribute:trademe option 1"
 *   "mageto option 2","trademe attribute:trademe option 2"
 *   ...
 *
 * Attribute and fake store can be specfied via utility parameters,
 * See tm-attr-mapping --help for more info
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */

if (!$action = get_action($argv))
  exit('Wrong parameters. Try ' . $argv[0] . ' --help' . PHP_EOL);

exit(call_action($action));

function main ($params) {
  //Change current directory to the directory of current script
  chdir(dirname(__FILE__));

  require 'app/Mage.php';

  if (!Mage::isInstalled())
    return 'Magento is not installed yet, please complete install wizard first.';

  //Only for urls
  //Don't remove this
  $_SERVER['SCRIPT_NAME'] = str_replace(
    basename(__FILE__),
    'index.php',
    $_SERVER['SCRIPT_NAME']
  );

  $_SERVER['SCRIPT_FILENAME'] = str_replace(
    basename(__FILE__),
    'index.php',
    $_SERVER['SCRIPT_FILENAME']
  );

  Mage::app('admin')->setUseSessionInUrl(false);
  Mage::setIsDeveloperMode(true);

  ini_set('display_errors', 1);

  umask(0);

  $optManager = Mage::getModel('sync/option');

  $io = new Varien_Io_File();

  $io->open(array('path' => getcwd()));
  $io->streamOpen($params['file'], 'r');

  $storeId = Mage::app()
    ->getStore($params['fake_store'])
    ->getId();

  while (false !== ($line = $io->streamReadCsv())) try {
    if (empty($line) || count($line) < 2)
      continue;

    $defVal = csv_str($line[0]);
    $tmVal = csv_str($line[1]);

    if (!$defVal)
      continue;

    $optManager->add(
      $params['attribute'],
      [
        Mage_Core_Model_App::ADMIN_STORE_ID => $defVal,
        $storeId => $tmVal
      ]
    );
  } catch (Exception $e) {
    Mage::printException($e);
  }
}

function help ($params) {
  global $argv;

  echo <<<"EOT"
Usage: {$argv[0]} [OPTION]... [FILE]
  -a, --attribute   attribute code
  -s, --fake-store  fake store code
      --help        display this help and exit

EOT;
}

function csv_str ($data) {
  return trim(mb_convert_encoding($data, 'UTF-8', 'CP1252'));
}

function get_action ($argv) {
  $_opts = getopt('ha:s:', ['help', 'attribute:', 'fake-store:']);

  if (isset($_opts['h']) || isset($_opts['help']))
    return ['help' => []];

  if (count($argv) != count($_opts) * 2 + 2)
    return;

 $options = [];

  array_walk($_opts, function ($value, $opt) use (&$options) {
    switch ($opt) {
      case 'a':
      case 'attribute': $options['attribute'] = $value; break;
      case 's':
      case 'fake-store': $options['fake_store'] = $value; break;
    }
  });

  if (count($options) != 2)
    return;

  $options['file'] = end($argv);

  return ['main' => $options];
}

function call_action ($action) {
  reset($action);

  $func = key($action);
  $params = current($action);

  if (function_exists($func)) try {
    return $func($params);
  } catch (Exception $e) {
    Mage::printException($e);
    return $e->getMessage();
  }
}