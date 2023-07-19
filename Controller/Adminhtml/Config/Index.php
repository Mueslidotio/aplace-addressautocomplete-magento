<?php
namespace Aplace\AddressAutocomplete\Controller\Adminhtml\Config;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
    )
    {
        parent::__construct($context);
    }

    public function execute(): Page
    {
        $resultPage = $this->resultFactory->create(type: ResultFactory::TYPE_PAGE);
        if ($resultPage instanceof Page) {
            $resultPage->getConfig()->getTitle()->prepend(__('Aplace Address Autocomplete Configuration'));
        }
        return $resultPage;
    }
}
