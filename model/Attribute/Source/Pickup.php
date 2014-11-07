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
 */

/**
 * Source model for pickup field
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Attribute_Source_Pickup
  extends MVentory_TradeMe_Model_Attribute_Source_WithDefault
{
  /**
   * Generate array of options
   *
   * @return array
   */
  protected function _getOptions () {
    return array(
      MVentory_TradeMe_Model_Config::PICKUP_ALLOW => 'Buyer can pickup',
      MVentory_TradeMe_Model_Config::PICKUP_FORBID => 'No pickups',
    );
  }
}

?>
