<?php
/**
 * Copyright © 2016 Hivewyre.com
 * @autor eduedeleon
 * */
namespace Hivewyre\Magentoconnector\Block;

/**
 * HivewyreConnector Page Block
 * Roi render block
 */
class Conversion extends \Magento\Framework\View\Element\Template
{
   /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

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
        \Magento\Checkout\Model\Session $checkoutSession,
        \Hivewyre\Magentoconnector\Helper\Data $HivewyreConnectorData,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->_checkoutSession       = $checkoutSession;
        $this->_HivewyreConnectorData = $HivewyreConnectorData;
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
     * Get order Id
     * @return [type]
     * @author edudeleon
     * @date   2016-02-10
     */
    public function getOrderId()
    {
        return $this->_checkoutSession->getLastRealOrderId();
    }

    /**
     * Get order amount (subtotal)
     * @return [type]
     * @author edudeleon
     * @date   2016-02-10
     */
    public function getOrderSubtotal()
    {
        //Get last order from session
        $order = $this->_checkoutSession->getLastRealOrder();
        return number_format($order->getSubtotal(), 2);
    }
}