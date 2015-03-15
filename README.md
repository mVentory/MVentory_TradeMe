#MVentory_TradeMe

[TradeMe](http://trademe.co.nz) is the leading New Zealand online auction site. This extension integrates Magento directly into TradeMe.

##Features

* List a Magento product via an [Android app](https://play.google.com/store/apps/details?id=com.mageventory) ([source code repository](https://github.com/mVentory/app))
* List a product with one click in Magento Admin
* Configure automated rule-based listing of Magento products on TradeMe
* Automatic synching of TradeMe sales to update Magento inventory
* Automatically withdraw an auction if a product goes out of stock while being listed on TradeMe
* Rule based mapping of TradeMe categories depending on product attributes

##Stability

This extension has been used by our customers for more than a year, but we only released it to the public in July 2014. Please, treat it as a beta version and feel free to report any problems to info@mventory.com.

##Dependencies

Note, this extension requires installation of our [Magento Mobile API extension](https://github.com/mVentory/MVentory_API), but you do not have to use the [Android app](https://play.google.com/store/apps/details?id=com.mageventory) for it. We are working on removing this cross dependency.

##License

This source file is subject to a Commercial Software License.

* No sharing - This file cannot be shared, published or distributed outside of the licensed organisation.

* No Derivatives - You can make changes to this file for your own use, but you cannot share or redistribute the changes.

The full text of the license was supplied to your organisation as part of the licensing agreement with mVentory.


##About the extension

This extension was developed to support our combined PoS, Inventory and Website control app for Android to bring Magento closer to the realities of a retail business. We took several unconventional approaches to make the product listing process as quick and simple to the end user as possible. On the other hand we moved most of the essential admin tasks into an Android app, including listing on TradeMe.

Watch our demo videos on http://mventory.com/demo.

##Help and support

The extension is poorly documented. We are working on it.

Please, contact us on info@mventory.com if you need help installing and configuring it.

##Technical details

#### Listing descriptions

Listing description is built from a template specified in ... column of the config file.

The code starts at the top of the template, extracts the data one element at a time and there is room left inserts the data into the listing. HTML is converted into plain text, where possible. HTML tables are ignored, white space is excessive white space is removed where possible.

Tags: ...
