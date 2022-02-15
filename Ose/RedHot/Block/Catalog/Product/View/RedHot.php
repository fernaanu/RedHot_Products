<?php
/**
 * OSE Digital.
 *
 * @category    OSE
 * @package     RedHot Product
 * @author      Anuradha Fernando <anuradhafernando81@gmail.com>
 * @copyright   Copyright (c) 2022 OSE Digital. (https://www.ose.com.au/)
 */
namespace Ose\RedHot\Block\Catalog\Product\View;

/**
 * Class RedHot for Product View
 */
class RedHot extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory
     */
    protected $redHotCollection;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory $redHotCollection,
        \Magento\Framework\Registry $registry
    )
    {
        $this->registry = $registry;
        $this->redHotCollection = $redHotCollection;
        parent::__construct($context);
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Show Add to cart count
     *
     * @return mixed|void
     */
    public function showAddToCartCount()
    {
        $product = $this->getCurrentProduct();
        $redHotCollection = $this->redHotCollection->create();
        if ($product->getRedHot() == 1) {
            $sku = $product->getSku();
            $redHotCollection->addFieldToSelect('add_to_cart_count')
                ->addFieldToFilter('sku', $sku);

            $redHotData = $redHotCollection->getData();
            return $redHotData[0]['add_to_cart_count'];
        }
    }
}
