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
 * @copyright Copyright (c) 2014-2015 mVentory Ltd. (http://mventory.com)
 * @license http://creativecommons.org/licenses/by-nc-nd/4.0/
 */

/**
 * TradeMe config model
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Config
{
  const SANDBOX = 'trademe/settings/sandbox';
  const CRON_INTERVAL = 'trademe/settings/cron';
  const MAPPING_STORE = 'trademe/settings/mapping_store';
  const ENABLE_LISTING = 'trademe/settings/enable_listing';
  const LIST_AS_NEW = 'trademe/settings/list_as_new';
  const _NAME_VARIANTS_ATTR = 'trademe/settings/name_variants_attr';
  const _SHIPPING_ATTR = 'trademe/settings/shipping_attr';
  const _PAYMENT_METHODS = 'trademe/settings/payment_methods';

  const _1AUC_ENDTIME = 'trademe/one_dollar/end_time';
  const _1AUC_ENDDAYS = 'trademe/one_dollar/end_days';
  const _1AUC_DURATION = 'trademe/one_dollar/duration';
  const _1AUC_FULL_PRICE = 'trademe/one_dollar/list_full_price';
  const _1AUC_LIMIT = 'trademe/one_dollar/limit';

  const TITLE_MAX_LENGTH = 50;
  const DESCRIPTION_MAX_LENGTH = 2048;

  //TradeMe shipping types
  //const SHIPPING_UNKNOWN = 0;
  //const SHIPPING_NONE = 0;
  const SHIPPING_UNDECIDED = 1;
  //const SHIPPING_PICKUP = 2;
  const SHIPPING_FREE = 3;
  const SHIPPING_CUSTOM = 4;

  //Pickup options
  //const PICKUP_NONE = 0; //None
  const PICKUP_ALLOW = 1;  //Buyer can pickup
  const PICKUP_DEMAND = 2; //Buyer must pickup
  const PICKUP_FORBID = 3; //No pickups

  //Add fees (TradeMe commission) options
  const FEES_NO = 0;      //No commission
  const FEES_ALWAYS = 1;  //Always add commission
  const FEES_SPECIAL = 2; //Only if the product has a current special price

  //Allow to list options
  const LIST_NO = 0;
  const LIST_YES = 1;
  const LIST_FIXEDEND = 2;

  const CACHE_TYPE = 'trademe';
  const CACHE_TAG = 'TRADEME';

  //Types of auctions
  //!!!TODO: move to MVentory_TradeMe_Model_Auction class
  const AUCTION_NORMAL = 0;
  const AUCTION_FIXED_END_DATE = 1;

  //Values for List full price setting
  const AUCTION_NORMAL_ALWAYS = 0;
  const AUCTION_NORMAL_STOCK = 1;
  const AUCTION_NORMAL_NEVER = 2;

  //Start and end hours for normal auctions
  const AUC_TIME_START = 7;
  const AUC_TIME_END = 23;

  //TradeMe currency
  const CURRENCY = 'NZD';

  //TradeMe time zone
  const TIMEZONE = 'Pacific/Auckland';
}
