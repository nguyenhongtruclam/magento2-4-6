<?php
namespace Wiki\CustomNewOrderState\Setup;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Status;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\Status as StatusResource;
use Magento\Sales\Model\ResourceModel\Order\StatusFactory as StatusResourceFactory;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;

    /**
     * Custom Order State Code
     */
    const ORDER_STATE_CUSTOM_CODE = 'delivering';

    /**
     * Custom Order Status Code
     */
    const ORDER_STATUS_CUSTOM_CODE = "delivering";

    /**
     * Custom Order Status Label 
     */
    const ORDER_STATUS_CUSTOM_LABEL = "Delivering";


    /**
     * Status Factory
     * @var StatusFactory
     */
    protected $statusFactory;


    /**
     * Status Resource factory
     * @var StatusResourceFactory
     */
    protected $statusResourceFactory;
    


    public function __construct(
        StatusFactory $statusFactory,
        StatusResourceFactory $statusResourceFactory
    )
    {
        $this->statusFactory = $statusFactory;
        $this->statusResourceFactory = $statusResourceFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
       $this->addNewOrderStateAndStatus();
    }

    /**
     * Create new custom order status and assign it to the new custom order state
     * 
     * @return void
     * 
     * $throws Exception
     */
    protected function addNewOrderStateAndStatus(){
        
        $status = $this->statusFactory->create();

        $statusResource = $this->statusResourceFactory->create();

        $status->setData(
            [
                'status' => self::ORDER_STATUS_CUSTOM_CODE,
                'label' => self::ORDER_STATUS_CUSTOM_LABEL
            ]
        );

        try {
            $statusResource->save($status);
        } catch (AlreadyExistsException $exception ) {
            return;
        }

        $status->assignState(self::ORDER_STATE_CUSTOM_CODE, true, true);
    }
}