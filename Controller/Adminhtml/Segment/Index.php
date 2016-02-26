<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

namespace Hivewyre\Magentoconnector\Controller\Adminhtml\Segment;


class Index extends \Magento\Backend\App\Action{

	/**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $hivewyreHelper;


	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Hivewyre\Magentoconnector\Helper\Data $hivewyreHelperData
	) {
		$this->hivewyreHelper  = $hivewyreHelperData;
		parent::__construct($context);
	}

    public function execute(){
        
       	$segments = $this->hivewyreHelper->getSegments();
      
        $this->getResponse()->setHeader('Content-type','application/json', true);
        $this->getResponse()->setBody(json_encode($segments));
    }
}