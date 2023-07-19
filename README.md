# aplace-addressautocomplete-magento
Magento 2 module of aplace autocomplete address

This module adds address autocomplete in the checkout form using APlace.io service.

# Installation
Before installation, you may want to:

Back up your database.

## Enable maintenance mode:

`bin/magento maintenance:enable`

Update the composer.json file in your project with the name and version of the extension by running this command.

`composer require aplace/addressautocomplete:1.0.0`

## Enable and configure the extension.

`bin/magento module:enable Aplace_AddressAutocomplete --clear-static-content`

## Setup and clean the cache

Run theses commands in the directory of your Magento install

`php bin/magento setup:upgrade`
`php bin/magento cache:clean`

## Disable maintenance mode:

`bin/magento maintenance:disable`

### Note

Tested on Magento 2.4.6