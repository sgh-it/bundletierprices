<?php
/**
 * Rewrite bundle price model to calculate tier prices correctly in bundle options
 *
 * @category   SGH
 * @package    SGH_BundleTierPrices
 * @subpackage Model
 * @author     Fabian Schmengler <fschmengler@sgh-it.eu>
 * @copyright  SGH informationstechnologie UGmbh 2014
 *
 */
class SGH_BundleTierPrices_Model_Bundle_Product_Price extends Mage_Bundle_Model_Product_Price
{
    /**
     * Calculate final price of selection
     * with take into account tier price
     *
     * @param  Mage_Catalog_Model_Product $bundleProduct
     * @param  Mage_Catalog_Model_Product $selectionProduct
     * @param  decimal                    $bundleQty
     * @param  decimal                    $selectionQty
     * @param  bool                       $multiplyQty
     * @param  bool                       $takeTierPrice
     * @return decimal
     */
    public function getSelectionFinalTotalPrice($bundleProduct, $selectionProduct, $bundleQty, $selectionQty,
            $multiplyQty = true, $takeTierPrice = true)
    {
        if (is_null($selectionQty)) {
            $selectionQty = $selectionProduct->getSelectionQty();
        }

        //BEGIN hack: force recalculation
        if ($takeTierPrice) {
            $selectionProduct->setFinalPrice(null);
        }
        // END hack

        if ($bundleProduct->getPriceType() == self::PRICE_TYPE_DYNAMIC) {
            $price = $selectionProduct->getFinalPrice($takeTierPrice ? $selectionQty * max(1, $bundleQty) : 1);
            //                                                                        ^^^^^^^^^^^^^^^^^^^ hack: take bundle qty into account if possible
        } else {
            if ($selectionProduct->getSelectionPriceType()) { // percent
                $product = clone $bundleProduct;
                $product->setFinalPrice($this->getPrice($product));
                Mage::dispatchEvent(
                'catalog_product_get_final_price',
                array('product' => $product, 'qty' => $bundleQty)
                );
                $price = $product->getData('final_price') * ($selectionProduct->getSelectionPriceValue() / 100);

            } else { // fixed
                $price = $selectionProduct->getSelectionPriceValue();
            }
        }

        if ($multiplyQty) {
            $price *= $selectionQty;
        }

        return min($price,
                $this->_applyGroupPrice($bundleProduct, $price),
                $this->_applyTierPrice($bundleProduct, $bundleQty, $price),
                $this->_applySpecialPrice($bundleProduct, $price)
        );
    }
}