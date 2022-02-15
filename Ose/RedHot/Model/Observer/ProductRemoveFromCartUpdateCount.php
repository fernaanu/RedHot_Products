<?php
/**
 * OSE Digital.
 *
 * @category    OSE
 * @package     RedHot Product
 * @author      Anuradha Fernando <anuradhafernando81@gmail.com>
 * @copyright   Copyright (c) 2022 OSE Digital. (https://www.ose.com.au/)
 */
namespace Ose\RedHot\Model\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class ProductRemoveFromCartUpdateCount
 */
class ProductRemoveFromCartUpdateCount implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Checkout\Model\CartFactory
     */
    protected $cart;

    /**
     * @var \Ose\RedHot\Model\RedHotFactory
     */
    private $redHotFactory;

    /**
     * @var \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory
     */
    private $redHotCollection;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Checkout\Model\CartFactory $cart
     * @param \Ose\RedHot\Model\RedHotFactory $redHotFactory
     * @param \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory $redHotCollection
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Checkout\Model\CartFactory $cart,
        \Ose\RedHot\Model\RedHotFactory $redHotFactory,
        \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory $redHotCollection
    ) {
        $this->cart = $cart;
        $this->request = $request;
        $this->redHotFactory = $redHotFactory;
        $this->redHotCollection = $redHotCollection;
    }

    /**
     * @param EventObserver $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //Below event if for removing RedHot data from ose_redhot_redhot table.
        //Due to the lack of time, I didn't complete that function.
    }
}
