<?php
/**
 * OSE Digital.
 *
 * @category    OSE
 * @package     RedHot Product
 * @author      Anuradha Fernando <anuradhafernando81@gmail.com>
 * @copyright   Copyright (c) 2022 OSE Digital. (https://www.ose.com.au/)
 */
namespace Ose\RedHot\Model\ResourceModel\RedHot;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Visy\ProductPermission\Model\ResourceModel\Permission
 */
class Collection extends AbstractCollection {

    /**
     * Initialize the collection
     */
    protected function _construct() {
        $this->_init('Ose\RedHot\Model\RedHot', 'Ose\RedHot\Model\ResourceModel\RedHot');
    }
}
