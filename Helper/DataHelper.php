<?php 

namespace Aplace\AddressAutocomplete\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class DataHelper extends AbstractHelper {

    public function getDefaultConfig()
    {
        return [
            'live_mode' => 0,
            'field_address_name' => 'street[0]',
            'field_city_name' => 'city',
            'field_postcode_name' => 'postcode',
            'field_country_name' => 'country_id',
            'field_region_name' => 'region_id'
        ];
    }

    public function getConfigKeys()
    {
        return [
            'live_mode',
            'field_address_name',
            'field_city_name',
            'field_postcode_name',
            'field_country_name',
            'field_region_name'
        ];
    }

    public function getStoreKey() {
        return 'Aplace/AddressAutocomplete/';
    }
}