<?php
namespace Wiki\MassActionOrder\Model\Order\Data;

use Magento\Framework\Api\AbstractSimpleObject;
use Wiki\MassActionOrder\Api\Data\OrderDeleteResponseInterface;

class OrderDeleteResponse extends AbstractSimpleObject implements OrderDeleteResponseInterface
{

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->_get(OrderDeleteResponseInterface::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        return $this->setData(OrderDeleteResponseInterface::STATUS, $status);
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        return $this->_get(OrderDeleteResponseInterface::ERROR);
    }

    /**
     * {@inheritdoc}
     */
    public function setError($error)
    {
        return $this->setData(OrderDeleteResponseInterface::ERROR,$error);
    }
}
