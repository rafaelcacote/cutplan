<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MembroEquipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'membros_equipe';

    protected $fillable = [
        'equipe_id',
        'user_id',
        'papel',
    ];

    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'equipe_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
