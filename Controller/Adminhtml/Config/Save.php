<?php

namespace Aplace\AddressAutocomplete\Controller\Adminhtml\Config;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\Type\Config as ConfigCacheType;
use Aplace\AddressAutocomplete\Helper\DataHelper;

class Save extends Action implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @param Context $context
     * @param Config $_resourceConfig
     * @param TypeListInterface $_cacheTypeList
     * @param Pool $_cacheFrontendPool
     * @param ManagerInterface $_messageManager
     * @param DataHelper $_dataHelper
     */
    public function __construct(
        Context $context,
        protected Config $_resourceConfig,
        protected TypeListInterface $_cacheTypeList,
        protected Pool $_cacheFrontendPool,
        protected ManagerInterface $_messageManager,
        protected DataHelper $_dataHelper
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();

        try {
            $keys = [
                'api_key',
                'encryption_key',
                'field_address_name',
                'field_city_name',
                'field_postcode_name',
                'field_country_name',
                'field_region_name'
            ];

            foreach ($keys as $key) {
                if (isset($data[$key])) {
                    $this->_resourceConfig->saveConfig($this->_dataHelper->getStoreKey() . $key, $data[$key], ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
                }
            }

            if (isset($data['live_mode']) && $data['live_mode'] === "live") {
                $this->_resourceConfig->saveConfig($this->_dataHelper->getStoreKey() . 'live_mode', '1', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            } else {
                $this->_resourceConfig->saveConfig($this->_dataHelper->getStoreKey() . 'live_mode', '0', ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        $this->_messageManager->addSuccessMessage(__("Configuration saved successfully!"));

        // clear cache
        $this->cleanConfigCache();

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('aplace/config/index');
    }

    protected function cleanConfigCache()
    {
        $cacheType = ConfigCacheType::TYPE_IDENTIFIER;
        $cacheFrontend = $this->_cacheFrontendPool->get($cacheType);

        if ($cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }

        $this->_cacheTypeList->cleanType($cacheType);
    }
}