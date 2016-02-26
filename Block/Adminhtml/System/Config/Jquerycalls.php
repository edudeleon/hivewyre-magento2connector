<?php
/**
 * Copyright © 2016 Hivewyre.com
 * @autor eduedeleon
 **/

namespace Hivewyre\Magentoconnector\Block\Adminhtml\System\Config;

/**
 * Renderer for Iframe in System Configuration
 */
class Jquerycalls extends \Magento\Backend\Block\Template implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * Render fieldset html
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
    	return '<script> 
            var segments_url        = "'.$this->getUrl('hivewyre/segment').'"; 
            var registration_url    = "'.$this->getUrl('hivewyre/account/register').'";  
            var login_url           = "'.$this->getUrl('hivewyre/account/login').'";  
            var connect_url         = "'.$this->getUrl('hivewyre/account/connect').'";
            var magento_rap         = "'.\Hivewyre\Magentoconnector\Model\Config::MAGENTO_RAP.'";

            require(["jquery"], function($){
                $(document).ready(function() {
                    getDropdownSegments();
                    setWebisteName();
                });
            });
        </script>';
    }
}