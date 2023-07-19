<?php

namespace Aplace\AddressAutocomplete\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Locale\Resolver;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\Random;
use Aplace\AddressAutocomplete\Helper\DataHelper;

class Autocomplete extends Template
{
    /**
     * @param Context $context
     * @param Resolver $_localeResolver
     * @param DataHelper $_dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        private Resolver $_localeResolver,
        protected DataHelper $_dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    /**
     * @return bool
     */
    public function getLiveMode() {
        return $this->_scopeConfig->getValue(
            $this->_dataHelper->getStoreKey() . 'live_mode'
        ) === "1";
    }

    /**
     * @return string
     */
    public function getAplaceAddressAutocompleteData()
    {
        $config = [
            'mage_default_language' => $this->getDefaultLang(),
            'live_mode' => $this->_scopeConfig->getValue(
                $this->_dataHelper->getStoreKey() . 'live_mode'
            ),
            'aplace_autocomplete_field_address' => $this->_scopeConfig->getValue(
                $this->_dataHelper->getStoreKey() . 'field_address_name'
            ),
            'aplace_autocomplete_field_city' => $this->_scopeConfig->getValue(
                $this->_dataHelper->getStoreKey() . 'field_city_name'
            ),
            'aplace_autocomplete_field_postcode' => $this->_scopeConfig->getValue(
                $this->_dataHelper->getStoreKey() . 'field_postcode_name'
            ),
            'aplace_autocomplete_field_country' => $this->_scopeConfig->getValue(
                $this->_dataHelper->getStoreKey() . 'field_country_name'
            ),
            'aplace_autocomplete_field_region' => $this->_scopeConfig->getValue(
                $this->_dataHelper->getStoreKey() . 'field_region_name'
            )
        ];
        return $config;
    }

    public function getAccessToken() {
        $aplaceAPIKey = $this->_scopeConfig->getValue(
            $this->_dataHelper->getStoreKey() . 'api_key'
        );
        if (!$aplaceAPIKey) {
            return null;
        }
        // one hour
        $ttl = round(time() + 60 * 60);
        $messageData = $aplaceAPIKey . '|' . $ttl;
        $aplaceEncryptionKey =  $this->_scopeConfig->getValue(
            $this->_dataHelper->getStoreKey() . 'encryption_key'
        );
        $accessToken = $aplaceAPIKey;
        if ($aplaceEncryptionKey) {
            try {
                $aes = new AES('gcm');
                $aes->setKey($aplaceEncryptionKey);
                $iv = Random::string(12);
                $aes->setNonce($iv);
                $encrypted = $aes->encrypt($messageData);
                $combinedEncrypted = $iv . $encrypted . $aes->getTag();
                $accessToken = urlencode(substr($aplaceAPIKey, 0, 5) . base64_encode($combinedEncrypted));
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }
        return $accessToken;
    } 

    public function getDefaultLang()
    {
        $currentLocaleCode = $this->_localeResolver->getLocale(); // en_US
        $languageCode = strstr($currentLocaleCode, '_', true);
        if (!$languageCode) {
            return 'en';
        }
        return $languageCode;
    }
}