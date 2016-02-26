<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

namespace Hivewyre\Magentoconnector\Controller\Adminhtml\Account;

class Register extends \Magento\Backend\App\Action{

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

        $result = $this->hivewyreHelper->registerMerchant(
            $request->getParam('email'),
            $request->getParam('website'),
            $request->getParam('segment'),
            $request->getParam('password')
        );

        //Validate Success action
        if(!empty($result['success'])){
            $this->messageManager->addSuccess( __('Thank you for registering. Please confirm your email.'));
        } 

        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json', true);
        $this->getResponse()->setBody(json_encode($result));
    }   
}