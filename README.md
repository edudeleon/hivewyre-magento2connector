Hivewyre_Magentoconnector
==========================

Hivewyre Connector Magento2 extension

Module integrates hivewyre app with Magento 2


Install
=======

1. Open your command line terminal and go to your Magento 2 root installation directory.

2. Run the following commands to install the module:

	```bash
   		composer require hivewyre/magento2-connector
   		php bin/magento setup:upgrade
    ```


3. Clean Magento Cache. In the Magento Admin. Go to System > Tools > Cache Management and click Flush Static Files Cache.

4. Enable the extension by going to Magento Admin Panel -> Marketing -> User Content

Uninstall
=========

1. Run the following commands to Uninstall the module:

	```bash
   		composer remove hivewyre/magento2-connector
   		php bin/magento setup:upgrade
    ```
2. Clean Magento Cache. In the Magento Admin. Go to System > Tools > Cache Management and click Flush Static Files Cache.