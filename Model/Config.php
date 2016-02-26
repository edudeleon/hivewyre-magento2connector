<?php
/**
 * Copyright © 2016 HiveWyre.com
 * @autor eduedeleon
 * */

namespace Hivewyre\Magentoconnector\Model;

class Config extends \Magento\Framework\Model\AbstractModel
{
   /*
    * API Endpoint
    */
    const ENDPOINT_URL        = 'https://air-staging.hivewyre.com';
    
    /*
    * Registered Account Partner
    */
    const MAGENTO_RAP         = '1dfc3238c2294c688073f56c64559cb4';
    
    /**
    * Hivewyre Public API Key
    */
    const HIVEWYRE_PUBLIC_KEY = "71844299c97f4b1c8ba1250eefdc21eb";
    
    /**
    * Hivewyre Secret API Key
    */
    const HIVEWYRE_SECRET_KEY = "7c7771bd58f5459aa6eaf6bfa24bdf21";
}