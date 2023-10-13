<?php
namespace Wiki\MassActionOrder\Api;

interface OrderDeleteInterface
{
    /**
     * @api
     * @param mixed $orderListId
     * @return \Wiki\MassActionOrder\Api\Data\OrderDeleteResponseInterface
     * @throws Exception
     */
    public function deleteOrders($orderListId);

}
