<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

namespace Hivewyre\Magentoconnector\Model\Config\Source;

class Websites implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Get empty array of webistes
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array();
    }
}