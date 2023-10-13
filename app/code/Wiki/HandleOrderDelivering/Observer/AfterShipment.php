<?php
namespace Wiki\HandleOrderDelivering\Observer;

use Magento\Sales\Api\InvoiceManagementInterface;
use Magento\Sales\Model\Order;

class AfterShipment implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var InvoiceManagementInterface
     */
    protected $invoiceManagement;

    public function __construct(InvoiceManagementInterface $invoiceManagement)
    {
        $this->invoiceManagement = $invoiceManagement;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $shipment = $observer->getEvent()->getShipment();
        $order = $shipment->getOrder();

        if(!$order->hasInvoice()){
            $invoice = $this->invoiceManagement->prepareInvoice($order);
            $invoice->register();
            $invoice->save();
        }

        $shipment->getOrder()->setState(Order::STATE_COMPLETE);
        $shipment->getOrder()->setStatus('delivering');

        return $this;
    }
}