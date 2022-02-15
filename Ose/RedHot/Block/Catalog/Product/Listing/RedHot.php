<?php
/**
 * OSE Digital.
 *
 * @category    OSE
 * @package     RedHot Product
 * @author      Anuradha Fernando <anuradhafernando81@gmail.com>
 * @copyright   Copyright (c) 2022 OSE Digital. (https://www.ose.com.au/)
 */
namespace Ose\RedHot\Block\Catalog\Product\Listing;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory as RedHotCollection;

/**
 * Class RedHot for Product Listing
 */
class RedHot extends Template
{
    /**
     * @var RedHotCollection
     */
    protected $redHotCollection;

    /**
     * @param Template\Context $context
     * @param RedHotCollection $redHotCollection
     */
    public function __construct(
        Template\Context $context,
        RedHotCollection $redHotCollection
    )
    {
        $this->redHotCollection = $redHotCollection;
        parent::__construct(
            $context
        );
    }

    /**
     * Get product
     *
     * @return mixed
     */
    public function getProduct()
    {
        return $this->getParentBlock()->getProduct();
    }

    /**
     * Get getRedHotCountPerSku
     *
     * @return int|mixed|void
     */
    public function getRedHotCountPerSku()
    {
        $addToCartCount = 0;
        $product = $this->getProduct();
        $redHotCollection = $this->redHotCollection->create();

        if ($product->getRedHot() == 1) {
            $sku = $product->getSku();
            $redHotCollection->addFieldToSelect('add_to_cart_count')
                             ->addFieldToFilter('sku', $sku);

            if (count($redHotCollection->getData()) > 0) {
                foreach ($redHotCollection->getData() as $result) {
                    $addToCartCount = $result['add_to_cart_count'];
                }
            }

            return $addToCartCount;
        }
    }
}
