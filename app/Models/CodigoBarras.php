<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class CodigoBarras extends Model
{
    use HasFactory;

    protected $fillable = ['codigo_barras', 'usuario_id','product_id','imagen_codigo_barras','impresion'];
    protected $table = 'codigos_barras';

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
}


