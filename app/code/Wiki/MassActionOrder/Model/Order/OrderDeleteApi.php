<?php
namespace Wiki\MassActionOrder\Model\Order;

use Wiki\MassActionOrder\Model\Order\Data\OrderDeleteResponseFactory;

class OrderDeleteApi implements \Wiki\MassActionOrder\Api\OrderDeleteInterface
{
    public function __construct(
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Wiki\MassActionOrder\Model\Order\Delete $delete,
        OrderDeleteResponseFactory $orderDeleteResponse
    )
    {
        $this->orderDeleteResponse = $orderDeleteResponse;
        $this->orderFactory = $orderFactory;
        $this->delete = $delete;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteOrders($orderListId)
    {
        $error = [];
        $orderResponse = $this->orderDeleteResponse->create();
        if(!empty($orderListId)){
            foreach($orderListId as $id){
                $orderId = $this->getOrderIdByIncrementId($id);

                if($orderId !== 'error'){
                    $this->deleteOrder($orderId);
                }else {
                    array_push($error,$id);
                }
            }
            if(!empty($error)){
                $orderResponse->setError(implode(",",$error));
            }
        }
        return $orderResponse;

    }

    protected function getOrderIdByIncrementId($incrementId)
    {
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);

        return $order->getId() ?: 'error';
    }

    protected function deleteOrder($orderId)
    {
        $this->delete->deleteOrder($orderId);
    }
}
