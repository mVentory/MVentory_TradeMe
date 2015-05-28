<?php

//Copy the script into Magento's root directory and run it with php or
//access it via browser (e.g. http://yoursite.com/uninstall.php)

error_reporting(E_ALL | E_STRICT);

define('LF', PHP_EOL);

ini_set('display_errors', 1);

require 'app/Mage.php';

Mage::setIsDeveloperMode(true);

if (!Mage::isInstalled()) {
  echo "Application is not installed yet, please complete install wizard first.";
  exit;
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_NAME']);
$_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', $_SERVER['SCRIPT_FILENAME']);

Mage::app('admin')->setUseSessionInUrl(false);

umask(0);

try {

stage1();
stage2();

} catch (Exception $e) {
  Mage::printException($e);
}

function stage1 () {
  $setup = Mage::getResourceModel('trademe/setup', 'core_setup');

  $setup
    ->removeAttributes(_getAttrs(), true)
    ->removeAttributeGroups(_getAttrGroups());

  foreach (_getTables() as $table)
    $setup->run('DROP TABLE ' . $table);

  //Remove setings
  $setup->run(sprintf(
    'DELETE FROM %s WHERE path like "trademe/%%"',
    $setup->getTable('core/config_data')
  ));
}

function stage2 () {
  $setup = Mage::getResourceModel('core/setup', 'core_setup');

  //Remove record about extension from resource table
  $setup->run(sprintf(
    'DELETE FROM %s WHERE code = "trademe_setup"',
    $setup->getTable('core/resource')
  ));
}

function _getAttrs () {
  return array(
    'tm_relist',
    'tm_avoid_withdrawal',
    'tm_shipping_type',
    'tm_allow_buy_now',
    'tm_add_fees',
    'tm_pickup',
    'tm_condition',
    'tm_fixedend_limit',
    'tm_listing_date'
  );
}

function _getAttrGroups () {
  return array('TM');
}

function _getTables () {
  return array(
    'trademe_matching_rules',
    'trademe_auction',
  );
}
