<?php
namespace Wiki\MassActionOrder\Controller\Adminhtml\Delete;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Api\OrderManagementInterface;
class MassOrder extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
   /**
     * @var OrderManagementInterface
     */
    protected $orderManagement;

    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Bss\DeleteOrder\Model\Order\Delete
     */
    protected $delete;

    /**
     * MassOrder constructor.
     * @param Context $context
     * @param Filter $filter
     * @param OrderManagementInterface $orderManagement
     * @param CollectionFactory $orderCollectionFactory
     * @param \Bss\DeleteOrder\Model\Order\Delete $delete
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        OrderManagementInterface $orderManagement,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Wiki\MassActionOrder\Model\Order\Delete $delete
    ) {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
        $this->orderManagement = $orderManagement;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->delete = $delete;
    }


    /**
     * @param AbstractCollection $collection
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function massAction(AbstractCollection $collection){

        $collectionInvoice = $this->filter->getCollection($this->orderCollectionFactory->create());

        $successOrder = []; $failedOrder = [];
        foreach($collectionInvoice as $order){
            $orderId = $order->getId(); $incrementId = $order->getIncrementId();
            try {
                $this->deleteOrder($orderId);
                array_push($successOrder,$incrementId);
            }catch(\Exception $e){
                array_push($failedOrder,$incrementId.''.$e);
            }
        }
        if(count($successOrder) > 0){
            $this->messageManager->addSuccessMessage(__('Successfully deleted orders #%1.', implode(',',$successOrder)));
        }

        if(count($failedOrder) > 0){
            $this->messageManager->addErrorMessage(__('Error delete order #%1.', implode(',',$failedOrder)));
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('sales/order/');
        return $resultRedirect;
    }

    /**
     * @param $orderId
     * @throws \Exception
     */
    protected function deleteOrder($orderId){
        $this->delete->deleteOrder($orderId);
    }

}
