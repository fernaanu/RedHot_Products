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

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrade Class. Used for create table
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $conn = $setup->getConnection();
        if (version_compare($context->getVersion(), '2.1.9') < 0) {
            $tableName = $setup->getTable('ose_redhot_redhot');
            if($conn->isTableExists($tableName) != true){
                $table = $conn->newTable($tableName)
                    ->addColumn(
                        'sku',
                        Table::TYPE_TEXT,
                        15,
                        ['identity'=>false,'unsigned'=>true,'nullable'=>false,'primary'=>true]
                    )
                    ->addColumn(
                        'add_to_cart_count',
                        Table::TYPE_INTEGER,
                        10,
                        ['nullable'=>false,'default'=>0]
                    )
                    ->setOption('charset','utf8');
                $conn->createTable($table);
            }
        }

        $setup->endSetup();
    }
}
