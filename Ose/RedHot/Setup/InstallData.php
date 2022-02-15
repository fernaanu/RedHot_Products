<?php
/**
 * OSE Digital.
 *
 * @category    OSE
 * @package     RedHot Product
 * @author      Anuradha Fernando <anuradhafernando81@gmail.com>
 * @copyright   Copyright (c) 2022 OSE Digital. (https://www.ose.com.au/)
 */
namespace Ose\RedHot\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use \Psr\Log\LoggerInterface;

/**
 * Class InstallData
 * @package Visy\ProductPermission\Setup
 */
class InstallData implements InstallDataInterface
{
    const ATTRIBUTE_CODE = 'red_hot';

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var CollectionFactory
     */
    private $productCollection;

    /**
     * @var ProductAction
     */
    private $productAction;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param CollectionFactory $collection
     * @param ProductAction $action
     * @param StoreManagerInterface $storeManager
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        CollectionFactory $collection,
        ProductAction $action,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productCollection = $collection;
        $this->productAction = $action;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'group' => 'General',
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Red Hot Product?',
                'input' => 'boolean',
                'class' => '',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '1',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => '',
                'default_attribute_set' => 4
            ]
        );

        $this->setRedhotData();

        $setup->endSetup();
    }

    /**
     * Save attribute for product assigned to attribute set Default
     *
     * @return void
     */
    protected function setRedhotData()
    {
        try {
            $collection = $this->productCollection->create();
            $collection->addFieldToFilter('attribute_set_id', 4);
            $storeId = $this->storeManager->getStore()->getId();
            $ids = [];
            $i = 0;
            foreach ($collection as $item) {
                $ids[$i] = $item->getEntityId();
                $i++;
            }
            $this->productAction->updateAttributes($ids, array(self::ATTRIBUTE_CODE => 1), $storeId);

        } catch (\Exception $e) {
            $this->logger->info(self::ATTRIBUTE_CODE . " assigned failed. | " . $e->getMessage());
        }
    }
}
