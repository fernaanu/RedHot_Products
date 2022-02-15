<?php
/**
 * OSE Digital.
 *
 * @category    OSE
 * @package     RedHot Product
 * @author      Anuradha Fernando <anuradhafernando81@gmail.com>
 * @copyright   Copyright (c) 2022 OSE Digital. (https://www.ose.com.au/)
 */
namespace Ose\RedHot\Model;

/**
 * Class RedHot
 * @package Ose\RedHot\Model
 */
class RedHot extends \Magento\Framework\Model\AbstractModel {
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct() {
        $this->_init('Ose\RedHot\Model\ResourceModel\RedHot');
    }
}
