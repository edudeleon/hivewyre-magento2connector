<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

namespace Hivewyre\Magentoconnector\Controller\Adminhtml\Account;

class Connect extends \Magento\Backend\App\Action{

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

    	$request = $this->getRequest();

        $result = $this->hivewyreHelper->connectMerchant(
            $request->getParam('domain_id'),
            $request->getParam('token'),
            $request->getParam('rap')
        );

        //Validate Success action
        if(!empty($result['success'])){
            $this->messageManager->addSuccess(__('Your account has been linked to Magento Successfully.'));
        }

        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json', true);
        $this->getResponse()->setBody(json_encode($result));
    }   
}