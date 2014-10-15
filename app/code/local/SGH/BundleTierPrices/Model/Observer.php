<?php
/**
 * Observer to save the correct selection quantity in bundle options of quote item
 *
 * @category   SGH
 * @package    SGH_BundleTierPrices
 * @subpackage Model
 * @author     Fabian Schmengler <fschmengler@sgh-it.eu>
 * @copyright  SGH informationstechnologie UGmbh 2014
 *
 */
class SGH_BundleTierPrices_Model_Observer
{
	/**
	 * Update bundle_selection_attributes options with correct tier price
	 * 
	 * @see event checkout_cart_save_before
	 * @param Varien_Event_Observer $observer
	 */
	public function updateSelectionQtys(Varien_Event_Observer $observer)
	{
		/* @var $cart Mage_Checkout_Model_Cart */
		$cart = $observer->getCart();
		foreach ($cart->getItems() as $item) {
			/* @var $item Mage_Sales_Model_Quote_Item */
			if ($item->getParentItem() && $item->getParentItem()->getProduct()->getTypeId() === 'bundle') {
				$this->_updateSelectionQty($item);
			}
		}
	}
	/**
	 * Update bundle_selection_attributes option of given item with correct tier price
	 * 
	 * @param Mage_Sales_Model_Quote_Item $item
	 */
	public function _updateSelectionQty(Mage_Sales_Model_Quote_Item $item)
	{
		$attributes = unserialize($item->getOptionByCode('bundle_selection_attributes')->getValue());

		$bundleProduct = $item->getParentItem()->getProduct();
		$selectionProduct = $item->getProduct();
		$requestQty = $item->getParentItem()->getQty();
		$selectionQty = $item->getQty();

		$attributes['price'] = Mage::app()->getStore()->convertPrice($bundleProduct->getPriceModel()->getSelectionFinalTotalPrice($bundleProduct, $selectionProduct, $requestQty, $selectionQty));
		$item->getOptionByCode('bundle_selection_attributes')->setValue(serialize($attributes));
	}

}