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
 * Source model for Yes/No/Default field
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Model_Attribute_Source_Boolean
  extends MVentory_TradeMe_Model_Attribute_Source_WithDefault
{
  /**
   * "Yes" value, same as in Mage_Eav_Model_Entity_Attribute_Source_Boolean
   * Redeclared to make the extensions compatible with Magento < 1.8
   *
   * @see Mage_Eav_Model_Entity_Attribute_Source_Boolean::VALUE_YES
   */
  const VALUE_YES = 1;

  /**
   * "No" value, same as in Mage_Eav_Model_Entity_Attribute_Source_Boolean
   * Redeclared to make the extensions compatible with Magento < 1.8
   *
   * @see Mage_Eav_Model_Entity_Attribute_Source_Boolean::VALUE_NO
   */
  const VALUE_NO = 0;

  /**
   * Generate array of options
   *
   * @return array
   */
  protected function _getOptions () {
    return array(
      self::VALUE_YES => 'Yes',
      self::VALUE_NO => 'No',
    );
  }
}
