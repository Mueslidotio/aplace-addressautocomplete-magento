<?php

namespace Aplace\AddressAutocomplete\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Locale\Resolver;
use Aplace\AddressAutocomplete\Helper\DataHelper;

class Config extends Template
{
    /**
     * @param Context $context
     * @param Resolver $_localeResolver
     * @param DataHelper $_dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context, 
        protected Resolver $_localeResolver,
        protected DataHelper $_dataHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getConfigValue($key)
    {
        $defaultValues = $this->_dataHelper->getDefaultConfig(); 
        if (array_key_exists($key, $defaultValues) && null === $this->_scopeConfig->getValue($this->_dataHelper->getStoreKey() . $key)) {
            return $defaultValues[$key];
        }
        return $this->_scopeConfig->getValue($this->_dataHelper->getStoreKey() . $key);
    }

    public function getDefaultLang()
    {
        $currentLocaleCode = $this->_localeResolver->getDefaultLocale(); // en_US
        $languageCode = strstr($currentLocaleCode, '_', true);
        if (!$languageCode) {
            return 'en';
        }
        return $languageCode;
    }
}