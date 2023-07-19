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

`php bin/magento setup:di:compile`
`php bin/magento setup:upgrade`
`php bin/magento cache:clean`

## Disable maintenance mode:

`bin/magento maintenance:disable`

## Configuration

• Visit https://aplace.io/en/auth/sign-up to sign up on Aplace.io

• On the APlace dashboard, create an API key and an Encryption key on https://aplace.io/en/dashboard/tokens.

• To configure the module, go in the Backend and click on Content / Aplace Address Autocomplete / Configuration link.

• Enable the Live mode.

• Copy and paste the API key and the Encryption key in the fields in the Prestashop module configuration page form.

• If you changed the checkout form, check the name of "Address", "City", "Post code", "Country" and "State" inputs and copy / paste the name value in the configuration form.

• Click on the Save button.

### Note

Tested on Magento 2.4.6