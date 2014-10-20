SGH_BundleTierPrices
================

Magento bugfix extension: Allow tier prices within bundle product options

This extension addresses the following Bugs in Magento:

1. Associated products with tier prices and qty > 1 in the bundle
2. "Price as configured" display on bundle products with tier prices

Tested with Magento CE 1.8 and Magento CE 1.9

Read more at http://www.schmengler-se.de/en/2014/10/magento-buendelprodukte-staffelpreise-der-einfachen-produkte-nutzen/

Installation of the Extension
====

With Composer
----

    "require": {
        "sgh/bundletierprices": "dev-master",
    }
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/sgh-it/bundletierprices.git"
        }
    ]

Manually
----

1. Download Source Code
2. Copy app into the Magento installation directory (no files are overwritten, just added)
