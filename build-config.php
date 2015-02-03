<?php

$version = '1.1.2';

$notes = <<<EOT
* Fix calculation of listing price to include various price rules
* Small fixes in interface
* Add support for $1 auctions
* Download product image by its frontend URL if it doesn&apos;t exist on server before listing product (allows to list products on Magento instances where product images are served from some CDN service)
* Add support for title variants for auction
* Add Maximum weight column into TradeMe config file to allow different settings per product&apos;s weight
* Send product SKU on listing/updating TradeMe auction
* Move all extension&apos;s attributes into separate attribute group (TM tab)
* Allow to select attribute for shipping types
* Small bug fixes and improvements
EOT;

$description = <<<EOT
TradeMe (http://trademe.co.nz) is the leading New Zealand online auction site. This extension integrates Magento directly into TradeMe.

Features

* List a Magento product via an Android app (https://play.google.com/store/apps/details?id=com.mageventory)
* List a product with one click in Magento Admin
* Configure automated rule-based listing of Magento products on TradeMe
* Automatic synching of TradeMe sales to update Magento inventory
* Automatically withdraw an auction if a product goes out of stock while being listed on TradeMe
* Rule based mapping of TradeMe categories depending on product attributes
* Support for multiple TradeMe accounts, e.g. one for Wellington, one for Auckland
* Rule-based shipping options
* Listing products at a set interval for even spread across pages

Stability

This extension has been used by our customers for more than a year, but we only released it to the public in July 2014. Please, treat it as a beta version and feel free to report any problems to info@mventory.com

Dependencies

Note, this extension requires installation of our Mobile API extension for Magento (http://www.magentocommerce.com/magento-connect/mobile-pos-inventory-barcode-scanning-photos-and-more-on-android.html), but you do not have to use the Android app (https://play.google.com/store/apps/details?id=com.mageventory) that comes with it.

License

This extension is subject to Creative Commons License BY-NC-ND (http://creativecommons.org/licenses/by-nc-nd/4.0/)

* NonCommercial — You may use it for commercial purposes after requesting a free waiver.
* NoDerivatives — If you remix, transform, or build upon the material, you may not distribute the modified material. Please, contribute your changes back to the project on Github (https://github.com/mVentory/MVentory_TradeMe)

About the extension

This extension was developed to support our combined PoS, Inventory and Website control app for Android to bring Magento closer to the realities of a retail / warehousing business. We took several unconventional approaches to make the product listing process as quick and simple to the end user as possible. On the other hand we moved most of the essential admin tasks into an Android app, including listing on TradeMe.

Watch our demo videos on http://mventory.com/demo

Help and support

Please, contact us on info@mventory.com if you need help installing and configuring it
EOT;

$summary = <<<EOT
List products on TradeMe from Magento Admin, Android app or using a cron. Sync stock levels and much more.
EOT;

return array(

//The base_dir and archive_file path are combined to point to your tar archive
//The basic idea is a seperate process builds the tar file, then this finds it
//'base_dir'               => '/home/bitnami/build',
//'archive_files'          => 'tm.tar',

//The Magento Connect extension name.  Must be unique on Magento Connect
//Has no relation to your code module name.  Will be the Connect extension name
'extension_name'         => 'MVentory_TradeMe',

//Your extension version.  By default, if you're creating an extension from a 
//single Magento module, the tar-to-connect script will look to make sure this
//matches the module version.  You can skip this check by setting the 
//skip_version_compare value to true
'extension_version'      => $version,
'skip_version_compare'   => true,

//You can also have the package script use the version in the module you 
//are packaging with. 
'auto_detect_version'   => false,

//Where on your local system you'd like to build the files to
//'path_output'            => '/home/bitnami/build/packages/MVentory_TradeMe',

//Magento Connect license value. 
'stability'              => 'stable',

//Magento Connect license value 
'license'                => 'CC BY-NC-ND 4.0',

//Magento Connect license URI 
'license_uri'            => 'http://creativecommons.org/licenses/by-nc-nd/4.0/',

//Magento Connect channel value.  This should almost always (always?) be community
'channel'                => 'community',

//Magento Connect information fields.
'summary'                => $summary,
'description'            => $description,
'notes'                  => $notes,

//Magento Connect author information. If author_email is foo@example.com, script will
//prompt you for the correct name.  Should match your http://www.magentocommerce.com/
//login email address
'author_name'            => 'Anatoly A. Kazantsev',
'author_user'            => 'anatoly',
'author_email'           => 'anatoly@mventory.com',
/*
// Optional: adds additional author nodes to package.xml
'additional_authors'     => array(
  array(
    'author_name'        => 'Mike West',
    'author_user'        => 'micwest',
    'author_email'       => 'foo2@example.com',
  ),
  array(
    'author_name'        => 'Reggie Gabriel',
    'author_user'        => 'rgabriel',
    'author_email'       => 'foo3@example.com',
  ),
),
*/
//PHP min/max fields for Connect.  I don't know if anyone uses these, but you should
//probably check that they're accurate
'php_min'                => '5.3.0',
'php_max'                => '6.0.0',

//PHP extension dependencies. An array containing one or more of either:
//  - a single string (the name of the extension dependency); use this if the
//    extension version does not matter
//  - an associative array with 'name', 'min', and 'max' keys which correspond
//    to the extension's name and min/max required versions
//Example:
//    array('json', array('name' => 'mongo', 'min' => '1.3.0', 'max' => '1.4.0'))
'extensions'             => array(),
'packages'               => array(array('name' => 'MVentory_API', 'channel' => 'community'))
);
