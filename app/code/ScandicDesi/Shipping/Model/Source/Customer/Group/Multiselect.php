<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ScandicDesi\Shipping\Model\Source\Customer\Group;

use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\Convert\DataObject;
use Magento\Framework\Option\ArrayInterface;

class Multiselect implements ArrayInterface
{
    /**
     * Customer groups options array
     *
     * @var null|array
     */
    protected $_options;

    /**
     * @var GroupManagementInterface
     */
    protected $_groupManagement;

    /**
     * @var DataObject
     */
    protected $_converter;

    /**
     * @param GroupManagementInterface $groupManagement
     * @param DataObject $converter
     */
    public function __construct(
        GroupManagementInterface $groupManagement,
        DataObject $converter
    ) {
        $this->_groupManagement = $groupManagement;
        $this->_converter = $converter;
    }

    /**
     * Retrieve customer groups as array
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->_options) {
            $groups = $this->_groupManagement->getLoggedInGroups();
            $groups[] = $this->_groupManagement->getNotLoggedInGroup();
            $this->_options = $this->_converter->toOptionArray($groups, 'id', 'code');
        }
        return $this->_options;
    }
}
