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
 * @copyright Copyright (c) 2015 mVentory Ltd. (http://mventory.com)
 * @license http://creativecommons.org/licenses/by-nc-nd/4.0/
 */

/**
 * Button for unlinking all sandbox auctions
 *
 * @package MVentory/TradeMe
 * @author Anatoly A. Kazantsev <anatoly@mventory.com>
 */
class MVentory_TradeMe_Block_Setting_Unlink
  extends Mage_Adminhtml_Block_System_Config_Form_Field
{
  /**
   * Return HTML for "Unlink all sandbox auctions" field
   *
   * @param Varien_Data_Form_Element_Abstract $elem
   *   Form element
   *
   * @return string
   *   Generated HTML for the form field
   */
  protected function _getElementHtml (Varien_Data_Form_Element_Abstract $elem) {
    Mage::getSingleton('adminhtml/session')->setData(
      'last_url',
      Mage::helper('core/url')->getCurrentUrl()
    );

    $url = $this->getUrl('trademe/listing/unlink', array('accounts' => 'all'));

    $button = $this
      ->getLayout()
      ->createBlock('adminhtml/widget_button')
      ->setData(array(
          'label' => $this->__('Unlink'),
          'onclick' => 'setLocation(\'' . $url . '\')'
        ));

    $wrapper = new Zend_Form_Decorator_HtmlTag();
    $wrapper
      ->setTag('div')
      ->setOption('class', 'trademe-setting-buttons-wrapper');

    return $wrapper->render($button->toHtml());
  }
}
