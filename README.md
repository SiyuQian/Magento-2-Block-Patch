# Magento-2-Block-Patch
Magento 2 Core Block Function Modify

## Why you need this?
Magento 2 does not support ifconfig for referenceBlock's remove handler in default


## Installation
``` bash
composer require siyu/magento-2-core-block-patch:dev-master
./bin/magento module:enable Siyu_BlockPatch
./bin/magento setup:upgrade
./bin/magento setup:di:compile
```


## Uninstall
./bin/magento module:disable Siyu_BlockPatch --clear-static-content
composer remove siyu/magento-2-core-block-patch
./bin/magento setup:di:compile

## CHANGELOG