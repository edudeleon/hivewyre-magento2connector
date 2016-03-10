<?php
/**
 * Copyright © 2016 Hivewyre.com
 * @autor eduedeleon
 * */
namespace Hivewyre\Magentoconnector\Block;

/**
 * HivewyreConnector Page Block
 * Tracking code block
 */
class Tracking extends \Magento\Framework\View\Element\Template
{
    /**
     * Hivewyre Connector data
     *
     * @var \Hivewyre\Magentoconnector\Helper\Data
     */
    protected $_HivewyreConnectorData = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Hivewyre\Magentoconnector\Helper\Data $HivewyreConnectorData
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Hivewyre\Magentoconnector\Helper\Data $HivewyreConnectorData,
        array $data = []
    ) {
        $this->_HivewyreConnectorData = $HivewyreConnectorData;
        parent::__construct($context, $data);
    }

    /**
     * Render block html if Hivewyre Connector in enabled
     *
     * @return string
     */
    protected function _toHtml()
    {
        return $this->_HivewyreConnectorData->isModuleActive() ? parent::_toHtml() : '';
    }

    /**
     * @return \Magento\HivewyreConnector\Helper\Data
     */
    public function getHelper()
    {
        return $this->_HivewyreConnectorData;
    }

    /**
     * Get tracking script URL
     * Used in the frontend
     * @return [type]
     * @author edudeleon
     * @date   2016-03-09
     */
    public function getTrackingScriptUrl(){
        $url = \Hivewyre\Magentoconnector\Model\Config::HIVEWYRE_TRACKING_URL."/tagcontainer.js?id=".$this->_HivewyreConnectorData->getShopId()."&type=1";

        return $url;
    }
}