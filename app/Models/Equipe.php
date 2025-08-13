<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'equipes';

    protected $fillable = [
        'nome',
        'tipo',
        'lider_user_id',
    ];

    public function lider()
    {
        return $this->belongsTo(User::class, 'lider_user_id');
    }

    public function membros()
    {
        return $this->hasMany(MembroEquipe::class, 'equipe_id');
    }
}
