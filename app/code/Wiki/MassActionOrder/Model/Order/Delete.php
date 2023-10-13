<?php
namespace Wiki\MassActionOrder\Model\Order;

use Magento\Framework\App\ResourceConnection;
class Delete 
{

    /**
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * @var \Wiki\MassActionOrder\Helper\Data
     */
    protected $data;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @param ResourceConnection $resource
     * @param \Wiki\MassActionOrder\Helper\Data $data
     * @param \Magento\Sales\Model\Order $order 
     */
    public function __construct(
        ResourceConnection $resource,
        \Wiki\MassActionOrder\Helper\Data $data,
        \Magento\Sales\Model\Order $order
    ){
        $this->resource = $resource;
        $this->data = $data;
        $this->order = $order;
    }
    

    /**
     * @params orderId
     * @throws \Exception
     */
    public function deleteOrder($orderId)
    {
        $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);

        $invoiceGridTable = $connection->getTableName($this->data->getTableName('sales_invoice_grid'));
        $shipmentGridTable = $connection->getTableName($this->data->getTableName('sales_shipment_grid'));
        $creditmemoGridTable = $connection->getTableName($this->data->getTableName('sales_creditmemo_grid'));

        $order = $this->order->load($orderId);

        if($order->hasInvoices()){
            $connection->rawQuery('DELETE FROM `'.$invoiceGridTable.'`WHERRE order_id='.$orderId);
        }

        if($order->hasShipments()){
            $connection->rawQuery('DELETE FROM `'.$shipmentGridTable.'`WHERRE order_id='.$orderId);
        }

        if($order->hasCreditmemos()){
            $connection->rawQuery('DELETE FROM `'.$creditmemoGridTable.'`WHERRE order_id='.$orderId);
        }

        $order->delete();
    }
}
