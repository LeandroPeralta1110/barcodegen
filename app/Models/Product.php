<?php

namespace App\Models;
use App\Models\CodigoBarras;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @property CodigosBarra[] $codigosBarras
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Product extends Model
{
    
    static $rules = [
		'nombre' => 'required',
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion', 'sucursal_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function codigosBarras()
    {
        return $this->hasMany(codigoBarras::class, 'product_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
