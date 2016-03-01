Hivewyre_Magentoconnector
==========================

Hivewyre Connector Magento2 extension

Module integrates hivewyre app with Magento 2


Install
=======

1. Open your command line terminal and go to your Magento 2 root installation directory.

2. Run the following commands to install the module:

	```bash
   		composer require hivewyre/magento2-connector --clear-static-content
   		php bin/magento setup:upgrade
    ```

3. Clean Magento Cache and enable the extension by going to Magento Admin Panel -> Marketing -> User Content

Uninstall
=========

1. Run the following commands to Uninstall the module:

	```bash
   		composer remove hivewyre/magento2-connector --clear-static-content
   		php bin/magento setup:upgrade
    ```