<?php

namespace App\Infrastructure\Persistence\Model;

use App\Infrastructure\Persistence\Model\OrdenProduccion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Infrastructure\Persistence\Model\Products;
use Illuminate\Database\Eloquent\Model;

class ProduccionBatch extends Model
{
  protected $table = 'produccion_batch';
  protected $primaryKey = 'id';
  public $incrementing = true;
  protected $keyType = 'int';
  protected $fillable = ['op_id', 'p_id', 'estacion_id', 'receta_version_id', 'porcion_id', 'cant_planificada', 'cant_producida', 'merma_gr', 'rendimiento', 'estado', 'qty', 'posicion', 'ruta'];
  protected $casts = ['qty' => 'int', 'posicion'=> 'int', 'ruta'=> 'array'];
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