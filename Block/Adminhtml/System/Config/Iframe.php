<?php
/**
 * Copyright © 2016 Hivewyre.com
 * @autor eduedeleon
 * */

namespace Hivewyre\Magentoconnector\Block\Adminhtml\System\Config;

/**
 * Renderer for Iframe in System Configuration
 */
class Iframe extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Render fieldset html
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
    	return '<iframe src="'.\Hivewyre\Magentoconnector\Model\Config::MAGENTO_RAP.'" width="100%" height="480" style="border: 0px;"></iframe>'.
        '<br>'.
        '<div style="margin-top:20px; text-align:center;">'.
        '<a href="https://adhesiveco.atlassian.net/wiki/display/AKB" target="_blank">Help</a> | '.
        '<a href="http://hivewyre.com/privacy-policy/" target="_blank">Privacy Policy</a> | '.
        '<a href="http://hivewyre.com/terms-and-conditions/" target="_blank">Terms & Conditions</a></div>';
    }
}