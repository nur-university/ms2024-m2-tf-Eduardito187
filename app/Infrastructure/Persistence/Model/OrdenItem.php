<?php

namespace App\Infrastructure\Persistence\Model;

use App\Infrastructure\Persistence\Model\OrdenProduccion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Infrastructure\Persistence\Model\Products;
use Illuminate\Database\Eloquent\Model;

class OrdenItem extends Model
{
    protected $table = 'order_item';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ['op_id', 'p_id', 'qty', 'price', 'final_price'];
    public $timestamps = true;

    /**
     * @return BelongsTo
     */
    public function ordenProduccion() : BelongsTo
    {
        return $this->belongsTo(OrdenProduccion::class, 'op_id', 'id');
    }

    
    /**
     * @return BelongsTo
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Products::class, 'p_id', 'id');
    }
}