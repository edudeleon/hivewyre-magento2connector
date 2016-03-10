<?php
/**
 * Copyright © 2016 hivewyre.com
 * @autor eduedeleon
 * */
namespace Hivewyre\Magentoconnector\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Hivewyre\Magentoconnector\Model\Api\Account
     */
    protected $_accountModel;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Hivewyre\Magentoconnector\Model\Api\Account $accountModel
    ){
        $this->_accountModel = $accountModel;
        parent::__construct($context);
    }

    /**
     * Config paths for using throughout the code
     */
    const XML_PATH_ACTIVE       = 'hivewyre_magentoconnector/account_settings/active';
    const XML_PATH_SITE_ID      = 'hivewyre_magentoconnector/account_settings/site_id';


    /**
     * Verify if module is active
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isModuleActive($store = null)
    {
        return $this->getShopId() && $this->scopeConfig->isSetFlag(self::XML_PATH_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Get Shop Id
     * @return [type]
     * @author edudeleon
     * @date   2016-02-10
     */
    public function getShopId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SITE_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Call Method to get Segments and handle the result
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function getSegments(){
        $categories = array();

        try {
            $segments    = $this->_accountModel->getSegments();
            
            if(!empty($segments)){
                foreach ($segments as $value) {
                    $categories[$value['id']] = $value['name'];
                }
            }

        } catch (Exception $ex) {
            $error = array(
                    'success'   => FALSE,
                    'msg'       => $ex->getMessage(),
            );

        }
        
        return $categories;
    }

     /**
     * Call method that registers a new Merchant and handle the result
     * @param  [type]     $email
     * @param  [type]     $website
     * @param  [type]     $segment
     * @param  [type]     $password
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function registerMerchant($email, $website, $segment, $password){

        try {
            $result  = $this->_accountModel->registerMerchant($email, $website, $segment, $password);

            //Checking for Errors
            $error_msg = $this->_getErrorMessages($result);
            if(empty($error_msg)){
                return array(
                    'success'   => TRUE,
                    'site_id'   => $result['site_id']
                );
            } else {
                return array(
                    'success' => FALSE,
                    'msg'     => $error_msg
                );
            }
           
        } catch (Exception $ex) {
            $error_msg = __('Connection with Hivewyre API failed') .
                '<br />' . $ex->getCode() . ': ' . $ex->getMessage();
                
            return array(
                'success'   => FALSE,
                'msg'       => $error_msg,
            );
        }
    }

    /**
     * Login user and get list of websites associated to the account.
     * @param  [type]     $email
     * @param  [type]     $password
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    public function loginMerchant($email, $password){
        try {
            $result = $this->_accountModel->loginMerchant($email, $password);

        
            //Check for Errors
            $error_msg = $this->_getErrorMessages($result);
            if(empty($error_msg)){

                //Get website list
                $token   = $result["token"];
                $result2 = $this->_accountModel->getMerchantWebsites($token);

                //Checking errors second call
                $error_msg2 = $this->_getErrorMessages($result2);
                if(empty($error_msg2)){

                    $websites = array();
                    if(!empty($result2)){
                        $websites[0] = "Select a Website";
                    }
                    foreach ($result2 as $value) {
                        if($value["name_of_rap"] == "None" || $value["name_of_rap"] == NULL){
                            $websites[$value['id']] = $value['domain']  . " (Not Connected)";
                        } else {
                            $websites[$value['id']] = $value['domain'] . " (Connected to ".$value['name_of_rap'].")";
                        }
                    }

                    return array(
                        'success'   => TRUE,
                        'token'     => $token,
                        'sites'     => $websites
                    );
                } else {
                    return array(
                        'success' => FALSE,
                        'msg'     => $error_msg2
                    );
                }

            } else {
                return array(
                    'success' => FALSE,
                    'msg'     => $error_msg
                );
            }
           
        } catch (Exception $ex) {
            $error_msg = __('Connection with Hivewyre API failed') .
                '<br />' . $ex->getCode() . ': ' . $ex->getMessage();
                
            return array(
                'success'   => FALSE,
                'msg'       => $error_msg,
            );
        }
    }

    /**
     * Connect Merchant 
     * @param  [type]     $domain_id
     * @param  [type]     $token
     * @param  [type]     $rap
     * @return [type]
     * @author edudeleon
     * @date   2016-02-18
     */
     public function connectMerchant($domain_id, $token, $rap){
        try {
            $result = $this->_accountModel->connectMerchant($domain_id, $token, $rap);
            
            //Checking for Errors
            $error_msg = $this->_getErrorMessages($result);
            if(empty($error_msg)){
                return array(
                    'success'   => TRUE,
                    'site_id'   => $result['site_id']
                );
            } else {
                return array(
                    'success' => FALSE,
                    'msg'     => $error_msg,
                );
            }
           
        } catch (Exception $ex) {
            $error_msg = __('Connection with Hivewyre API failed') .
                '<br />' . $ex->getCode() . ': ' . $ex->getMessage();
                
            return array(
                'success'   => FALSE,
                'msg'       => $error_msg,
            );
        }
    }

    /**
     * Get error messages
     * @param  [type]     $result
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    private function _getErrorMessages($result){
       //Checking for Errors
        $error_msg = '';
        if(isset($result['errors'])){
            foreach ($result['errors'] as $key => $value) {
                $error_msg .= "[". strtoupper($key) ."] ";
                
                foreach ($value as $msg) {
                    $error_msg .= $msg;
                }

                $error_msg .= "</br>";
            }
        }

        return $error_msg;
    }
}