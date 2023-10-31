<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoBarras extends Model
{
    use HasFactory;

    protected $fillable = ['codigo_barras', 'usuario_id'];
    protected $table = 'codigos_barras';

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

