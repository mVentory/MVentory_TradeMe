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
 * Product collection model
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */

class MVentory_TradeMe_Model_Resource_Product_Collection
  extends Mage_Catalog_Model_Resource_Product_Collection
{
  /**
   * Retrive all IDs for collection
   *
   * @param int|null $limit
   *   The number of rows to return
   *
   * @param int|null $offset
   *   Start returning after this many rows
   *
   * @return array
   *   List of all IDs for collection
   */
  public function getAllIds ($limit = null, $offset = null) {
    $select = $this->getSelect();

    $this->_collectionQuery = $select->assemble();

    $select = $this
      ->_buildClearSelect(clone $select)
      ->columns('e.' . $this->getEntity()->getIdFieldName())
      ->limit($limit, $offset)
      ->resetJoinLeft();

    $this->_allIdsQuery = $select->assemble();

    return $this
      ->getConnection()
      ->fetchCol($select, $this->_bindParams);
  }

  /**
   * Return assembled queries
   *
   * @return array
   *   List of assembled queries
   */
  public function getAssembledQueries () {
    return array(
      'collection' => $this->_collectionQuery,
      'ids' => $this->_allIdsQuery
    );
  }
}
