<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    /**
     * Relacionamento com Gasto
     */
    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class, 'categoria_id');
    }
}
