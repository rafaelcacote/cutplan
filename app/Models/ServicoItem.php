<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicoItem extends Model
{
    use HasFactory;

    protected $table = 'servico_itens';

    protected $fillable = [
        'descricao_item',
        'ordem',
        'opcional',
    ];

    protected $casts = [
        'opcional' => 'boolean',
        'ordem' => 'integer',
    ];

    // Removido relacionamento com Servico
}
