<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fornecedores';

    protected $fillable = [
        'nome',
        'documento',
        'email',
        'telefone',
        'endereco_id',
        'user_id',
        'observacoes',
    ];

    public function endereco()
    {
        return $this->belongsTo(Endereco::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
