<?php
namespace Wiki\MassActionOrder\Controller\Adminhtml\Update;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;

class CompleteOrder extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    protected $orderManagement;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
        $this->orderRepository = $orderRepository;
    }

    protected function massAction(AbstractCollection $collection)
    {
        $arrNotComplete = [];
        $countNotComplete = 0;
        $arrComplete = [];
        $countComplete = 0;
        foreach ($collection->getItems() as $order) {
            if ($order->getStatus() == "delivering") {
                $order->setState('complete');
                $order->setStatus('complete');
                $this->orderRepository->save($order);
                array_push($arrComplete, $order->getData('type_order_increment_id'));
                $countComplete++;
            }else {
                array_push($arrNotComplete, $order->getData('type_order_increment_id'));
                $countNotComplete++;
            }
        }

        if(count($arrNotComplete) > 0) {
            $textNotComplete = count($arrNotComplete) > 1 ? implode(", ", $arrNotComplete) : $arrNotComplete[0];
            $this->messageManager->addErrorMessage(__('%1 order(s) cannot be completed. ( %2 )',$countNotComplete, $textNotComplete));
        }
        if(count($arrComplete) > 0) {
            $textComplete = count($arrComplete) > 1 ? implode(", ", $arrComplete) : $arrComplete[0];

            $this->messageManager->addSuccessMessage(__('We completed %1 order(s). ( %2 )',$countComplete, $textComplete));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->getComponentRefererUrl());
        return $resultRedirect;
    }
}
