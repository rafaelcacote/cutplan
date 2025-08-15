<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrcamento extends Model
{
    use HasFactory;

    protected $table = 'itens_orcamento';
    public $timestamps = false;

    protected $fillable = [
        'orcamento_id',
        'servico_id',
        'servicos_itens_id',
        'descricao',
        'quantidade',
        'unidade_id',
        'preco_unitario',
        'total',
        'eh_servico',
    ];

    protected $casts = [
        'quantidade' => 'decimal:4',
        'preco_unitario' => 'decimal:4',
        'total' => 'decimal:4',
        'eh_servico' => 'boolean',
    ];

    // Relacionamentos
    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class);
    }

    public function servicoItem()
    {
        return $this->belongsTo(ServicoItem::class, 'servicos_itens_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }

    // Métodos auxiliares
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->total = $item->quantidade * $item->preco_unitario;
        });

        static::saved(function ($item) {
            $item->orcamento->calcularTotais();
        });

        static::deleted(function ($item) {
            $item->orcamento->calcularTotais();
        });
    }
}
