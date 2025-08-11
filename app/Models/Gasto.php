<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gasto extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_gasto',
        'valor',
        'descricao',
        'categoria_id',
        'forma_pagamento_id',
    ];

    protected $casts = [
        'data_gasto' => 'date',
        'valor' => 'decimal:2',
    ];

    /**
     * Relacionamento com Category
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    /**
     * Relacionamento com FormaPagamento
     */
    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }
}
