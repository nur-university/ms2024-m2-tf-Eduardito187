<?php

namespace App\Domain\Produccion\ValueObjects;

use App\Domain\Shared\ValueObjects\ValueObject;

class ItemDespacho extends ValueObject
{
    /**
     * @var int|null
     */
    public readonly int|null $ordenProduccionId;

    /**
     * @var int|null
     */
    public readonly int|null $productId;

    /**
     * @var int|null
     */
    public readonly int|null $paqueteId;

    /**
     * Constructor
     * 
     * @param int|null $ordenProduccionId
     * @param int|null $productId
     * @param int|null $paqueteId
     */
    public function __construct(
        int|null $ordenProduccionId,
        int|null $productId,
        int|null $paqueteId
    ) {
        $this->ordenProduccionId = $ordenProduccionId;
        $this->productId = $productId;
        $this->paqueteId = $paqueteId;
    }
}