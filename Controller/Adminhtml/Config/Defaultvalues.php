<?php

namespace Aplace\AddressAutocomplete\Controller\Adminhtml\Config;

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

class Defaultvalues extends Action implements HttpGetActionInterface
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
        protected Context $context,
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
        try {
            $defaultValues = $this->_dataHelper->getDefaultConfig(); 

            foreach ($defaultValues as $key => $value) {
                $this->_resourceConfig->saveConfig($this->_dataHelper->getStoreKey() . $key, $value, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, 0);
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        $this->_messageManager->addSuccessMessage(__("Default values set!"));

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