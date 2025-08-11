<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMaterial extends Model
{
    use HasFactory;

    protected $table = 'tipos_materiais';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nome',
    ];
}
