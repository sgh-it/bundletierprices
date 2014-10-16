<?php
/**
 * Rewrite default price model to fix a Magento bug with tier prices in bundle product options
 *
 * @category   SGH
 * @package    SGH_BundleTierPrices
 * @subpackage Model
 * @author     Fabian Schmengler <fschmengler@sgh-it.eu>
 * @copyright  SGH informationstechnologie UGmbh 2014
 *
 */
class SGH_BundleTierPrices_Model_Product_Price extends Mage_Catalog_Model_Product_Type_Price
{
    /**
     * Always use numeric keys, otherwise JSON configuration gets screwed up
     * (expects array, would get object) and the configured price does not calculate correctly
     *
     * (non-PHPdoc)
     * @see Mage_Bundle_Model_Product_Price::getTierPrice($qty, $product)
     */
    public function getTierPrice($qty = null, $product)
    {
        $tierPrice = parent::getTierPrice($qty, $product);
        if (is_array($tierPrice)) {
            return array_values($tierPrice);
        }
        return $tierPrice;
    }
}