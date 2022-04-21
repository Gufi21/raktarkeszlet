<?php

namespace Warehouse\Service;

use Exception;
use Throwable;

class WarehouseException extends Exception
{
    public const NOT_ENOUGH_SPACE = 1;
    public const OUT_OF_STOCK     = 2;

    /**
     * @param int $code Hibakód
     * @param string $message Üzenet
     * @param Throwable|null $previous Kiváltó kivétel
     */
    public function __construct(int $code, string $message = "", Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
