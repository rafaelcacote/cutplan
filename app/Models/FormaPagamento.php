<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormaPagamento extends Model
{
    use HasFactory;

    protected $table = 'formas_pagamento';

    protected $fillable = [
        'nome',
    ];

    /**
     * Relacionamento com Gasto
     */
    public function gastos(): HasMany
    {
        return $this->hasMany(Gasto::class, 'forma_pagamento_id');
    }
}
