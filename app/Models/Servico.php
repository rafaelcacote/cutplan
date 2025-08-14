<?php
namespace App\Models;
use App\Models\ServicoItem;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = [
        'nome',
        'descricao',
        'preco_base',
        'categoria',
        'ativo',
    ];
    /**
     * Itens vinculados a este serviço
     */
    public function itens()
    {
        return $this->hasMany(ServicoItem::class, 'servico_id');
    }
}
