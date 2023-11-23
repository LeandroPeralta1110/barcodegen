<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';
    protected $fillable = ['nombre'];

    /**
     * Obtén todos los usuarios asociados a esta sucursal.
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'sucursal_id');
    }
}
