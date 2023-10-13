<?php
namespace Wiki\Anoway\Controller\Adminhtml\Grid;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) 
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * Grid List page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        
        // header('Content-Type: text/xml');
        // $layoutString = $resultPage->getLayout()->getXmlString();
        // echo '<layouts
        // xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' .
        // $layoutString .'</layouts>';
        // die;
        $resultPage->setActiveMenu('Wiki_Anoway::grid_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Grid Menu'));

        return $resultPage;
    }

     /**
     * Check Grid List Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        $a = $this->_authorization->isAllowed('Wiki_Anoway::menu');
        return $this->_authorization->isAllowed('Wiki_Anoway::menu');
    }
}
