<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orcamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orcamentos';

    protected $fillable = [
        'cliente_id',
        'versao',
        'status',
        'moeda',
        'subtotal',
        'desconto',
        'impostos',
        'total',
        'validade',
        'criado_por',
        'observacoes',
    ];

    protected $casts = [
        'versao' => 'integer',
        'subtotal' => 'decimal:4',
        'desconto' => 'decimal:4',
        'impostos' => 'decimal:4',
        'total' => 'decimal:4',
        'validade' => 'date',
        'criado_por' => 'integer',
    ];

    // Relacionamentos
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function itens()
    {
        return $this->hasMany(ItemOrcamento::class, 'orcamento_id');
    }

    // Métodos auxiliares
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Rascunho',
            'sent' => 'Enviado',
            'approved' => 'Aprovado',
            'rejected' => 'Rejeitado',
            'expired' => 'Expirado',
            default => 'Desconhecido'
        };
    }

    public function calcularTotais()
    {
        $subtotal = $this->itens->sum('total');
        $total = $subtotal - $this->desconto + $this->impostos;

        $this->update([
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    }
}
