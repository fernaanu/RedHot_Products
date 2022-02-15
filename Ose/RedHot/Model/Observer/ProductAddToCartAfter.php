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
use Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory as RedHotCollection;
use Ose\RedHot\Model\RedHotFactory;
use Ose\RedHot\Model\RedHot as RedHotModel;
use Ose\RedHot\Model\ResourceModel\RedHot as RedHotResource;

/**
 * Class ProductAddToCartAfter
 */
class ProductAddToCartAfter implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    /**
     * @var RedHotCollection
     */
    protected $redHotCollection;

    /**
     * @var RedHotFactory
     */
    protected $redHotFactory;

    /**
     * @var RedHotResource
     */
    protected $redHotResource;

    /**
     * @var RedHotModel
     */
    protected $redHotModel;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param RedHotCollection $redHotCollection
     * @param RedHotFactory $redHotFactory
     * @param RedHotResource $redHotResource
     * @param RedHotModel $redHotModel
     */
    public function __construct(
        \Psr\Log\LoggerInterface                  $logger,
        \Magento\Framework\App\ResourceConnection $resource,
        RedHotCollection                          $redHotCollection,
        RedHotFactory                             $redHotFactory,
        RedHotResource                            $redHotResource,
        RedHotModel                               $redHotModel
    )
    {
        $this->logger = $logger;
        $this->resource = $resource;
        $this->redHotCollection = $redHotCollection;
        $this->redHotFactory = $redHotFactory;
        $this->redHotResource = $redHotResource;
        $this->redHotModel = $redHotModel;
    }

    /**
     * @param EventObserver $observer
     * @return void
     * @throws \Exception
     */
    public function execute(EventObserver $observer)
    {
        $product = $observer->getEvent()->getData('product');
        $sku = $product->getSku();
        $redHotCollection = $this->redHotCollection->create();

        $redHotCollection->addFieldToSelect('add_to_cart_count')
                         ->addFieldToFilter('sku', $sku);
        $model = $this->redHotFactory->create();

        if ($product->getRedHot() == 1) {
            if (count($redHotCollection->getData()) > 0) {
                foreach ($redHotCollection->getData() as $result) {
                    $addToCartCount = $result['add_to_cart_count'] + 1;

                    $model->load($product->getSku());
                    $model->setAddToCartCount($addToCartCount);
                    $model->save();
                }
            } else {
                $data = ['sku' => $sku, 'add_to_cart_count' => 1];
                $this->logger->info("bbbbbbbbbb" . $data['sku']);
                $model->setData($data);
                $model->save();
            }
        }
    }
}
