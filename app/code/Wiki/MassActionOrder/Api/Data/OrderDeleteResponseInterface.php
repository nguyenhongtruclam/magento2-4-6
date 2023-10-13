<?php
namespace Wiki\MassActionOrder\Api\Data;

interface OrderDeleteResponseInterface
{
    const STATUS = 'status';
    const ERROR = 'error';

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getError();

    /**
     * @param string $error
     * @return $this
     */
    public function setError($error);

}
