<?php
/**
 * OSE Digital.
 *
 * @category    OSE
 * @package     RedHot Product
 * @author      Anuradha Fernando <anuradhafernando81@gmail.com>
 * @copyright   Copyright (c) 2022 OSE Digital. (https://www.ose.com.au/)
 */
namespace Ose\RedHot\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Flatrate
 */
class Flatrate extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $rateMethodFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory
     */
    protected $redHotCollection;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    const SHIPPING_CODE = 'flatrate';
    const SHIPPING_TITLE = 'Flat Rate';
    const REDHOT_FLATRATE = '10.00';

    const XPATH_SHIPPING_TITLE = 'carriers/flatrate/title';
    const XPATH_SHIPPING_METHOD = 'carriers/flatrate/name';
    const XPATH_SHIPPING_PRICE = 'carriers/flatrate/price';

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory $redHotCollection
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Ose\RedHot\Model\ResourceModel\RedHot\CollectionFactory $redHotCollection,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->rateResultFactory = $rateResultFactory;
        $this->customerSession = $customerSession;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->checkoutSession = $checkoutSession;
        $this->redHotCollection = $redHotCollection;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @return string[]
     */
    public function getAllowedMethods()
    {
        return [self::SHIPPING_CODE => "flatrate"];
    }

    /**
     * Collect rates
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function collectRates(RateRequest $request)
    {
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $flatRateShippingTitle = $this->getConfigValue(self::XPATH_SHIPPING_TITLE);
        $flatRateShippingMethod = $this->getConfigValue(self::XPATH_SHIPPING_METHOD);
        $flatRateShippingPrice = $this->getConfigValue(self::XPATH_SHIPPING_PRICE);

        $method->setCarrier(self::SHIPPING_CODE);
        //Get the title from the system configuration
        $method->setCarrierTitle($flatRateShippingTitle);

        //Get the methos from the system configuration
        $method->setMethod($flatRateShippingMethod);

        //Get the title from the system configuration
        $method->setMethodTitle($flatRateShippingMethod);

        //Filter price for Red Hot products
        $isAllowedForRedHot = $this->allowFlatrateForRedhotProducts();
        if ($isAllowedForRedHot) {
            $amount = self::REDHOT_FLATRATE;
        } else {
            $amount = $flatRateShippingPrice;
        }

        $method->setPrice($amount);
        $method->setCost($amount);

        $result->append($method);

        return $result;
    }

    /**
     * Allow custom flat rate for RedHor products
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function allowFlatrateForRedhotProducts()
    {
        $items = $this->checkoutSession->getQuote()->getAllVisibleItems();
        $redHotCollection = $this->redHotCollection->create();

        $skus = [];
        $showFlatRate = false;
        foreach($items as $item)
        {
            $skus[] = $item->getSku();
        }

        $redHotCollection->addFieldToSelect('*');
        $redHotCollection->getSelect()->orWhere('sku IN (?)', $skus);
        $redHotCount = count($redHotCollection->getData());

        if ($redHotCount > 0) {
            $showFlatRate = true;
        }

        return (bool)$showFlatRate;
    }

    /**
     * Get system config values
     */
    protected function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }
}
