<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

namespace Hivewyre\Magentoconnector\Model\Api;

class Account extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Magento Logger
     * @var Psr\Log\LoggerInterface
     */
    protected $_logger;

    public function __construct(
            \Psr\Log\LoggerInterface $logger
        ){
        $this->_logger  = $logger;
    }

    /**
     * Returns the method URL
     * @param  [type]     $path
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    protected function _getUrl($path)
    {
        return \Hivewyre\Magentoconnector\Model\Config::ENDPOINT_URL.$path;
    }

    /**
     * Return the Authorization String encoded
     * @param  [type]     $method
     * @param  [type]     $endpoint
     * @param  [type]     $data
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    private function _getAuthorizationString($method, $endpoint, $data){
        //Get this from config
        $public_key = \Hivewyre\Magentoconnector\Model\Config::HIVEWYRE_PUBLIC_KEY;
        $secret_key = \Hivewyre\Magentoconnector\Model\Config::HIVEWYRE_SECRET_KEY;

        if($data){
            $request_body = utf8_encode(json_encode($data));
        } else {
            $request_body = '';
        }
        
        $data_request    = $method . "\n" . $endpoint . "\n" . $request_body;
        $encoded_request = 'Hivewyre '.$public_key.':'.base64_encode(hash_hmac("sha1", $data_request, $secret_key, $raw_output=TRUE));
        
        return $encoded_request;
    }

    /**
     * Makes the API Call
     * @param  [type]     $endpoint
     * @param  string     $method
     * @param  [type]     $data
     * @param  boolean    $token
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    protected function _call($endpoint, $method = 'POST', $data = null, $token = null)
    {
        //Prepare URL
        $url = $this->_getUrl($endpoint);
       
        $method = strtoupper($method);

        //Getting Authorization String
        $auth_string = $this->_getAuthorizationString($method, $endpoint, $data);
        
        //Instantiate the
        $client = new \Zend_Http_Client($url);
        $client->setMethod($method);

        //Preparing headers
        $headers = array(
            'Authorization' => $auth_string,
            'Content-Type'  => 'application/json'
        );

        if($token){
            $headers['Advertiser-Authorization'] = "Token ".$token;
        }

        $client->setHeaders($headers);

        if($method == 'POST' || $method == "PUT" || $method == "PATCH") {
            $client->setRawData(json_encode($data), 'application/json');
        }

        $this->_logger->info("[HIVEWYRE] ".
            print_r(
                array(
                   'url'     => $url,
                   'method'  => $method,
                   'headers' => $headers,
                   'data'    => json_encode($data),
                ),
                true
            )
        );

        try {

            $response = $client->request();

        } catch ( Zend_Http_Client_Exception $ex ) {   
            //Logging exceptions         
            $this->_logger->info("[HIVEWYRE] ".'Call to ' . $url . ' resulted in: ' . $ex->getMessage());
            $this->_logger->info("[HIVEWYRE] ".'--Last Request: ' . $client->getLastRequest());
            $this->_logger->info("[HIVEWYRE] ".'--Last Response: ' . $client->getLastResponse());

            return $ex->getMessage();
        }

        //Prepare response
        $body = json_decode($response->getBody(), true);

        //Log response
        $log_msg = var_export($body, true);
        $this->_logger->info("[HIVEWYRE] ".$log_msg);
        
        return $body;
    }

    /**
     * Make a request to get the segments.
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function getSegments(){

        $response = $this->_call('/external_api/v1/segments','GET');

        return $response; 
    }

    /**
     * Make a request to accounts method in order to save the a new account
     * @param  [type]     $email
     * @param  [type]     $website
     * @param  [type]     $segment
     * @param  [type]     $password
     * @param  [type]     $rap
     * @return [type]
     * @author edudeleon
     * @date   2016-02-09
     */
    public function registerMerchant($email, $website, $segment, $password, $rap = null){

        $data = array(
            'email'    => $email,
            'password' => $password,
            'website' => array(
                'domain'    => $website,
                'segment'   => $segment,
                'rap'       => \Hivewyre\Magentoconnector\Model\Config::MAGENTO_RAP,
            )
        );

        $response = $this->_call('/external_api/v1/accounts', 'POST', $data);
        return $response;
    }

    /**
     * Login Merchant
     * @param  [type]     $email
     * @param  [type]     $password
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    public function loginMerchant($email, $password){

        $data = array(
            'username' => $email,
            'password' => $password
        );

        $response = $this->_call('/external_api/v1/login', 'POST', $data);

        return $response;
    }

    /**
     * Get Merchant webisites
     * @param  [type]     $token
     * @return [type]
     * @author edudeleon
     * @date   2016-02-16
     */
    public function getMerchantWebsites($token){

        $response = $this->_call('/external_api/v1/advertiser/sites', 'GET', NULL, $token);

        return $response;
    }

    /**
     * Assign RAP to domain/site ID
     * @param  [type]     $domain_id
     * @param  [type]     $token
     * @param  [type]     $rap
     * @return [type]
     * @author edudeleon
     * @date   2016-02-18
     */
    public function connectMerchant($domain_id, $token, $rap){

        $data = array(
            'rap' => $rap,
        );

        $response = $this->_call('/external_api/v1/advertiser/sites/'.$domain_id, 'PATCH', $data, $token);

        return $response;
    }
}