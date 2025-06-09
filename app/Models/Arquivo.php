<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Arquivo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome_original',
        'caminho',
        'mime_type',
    ];

    // Relacionamento com User (um arquivo pode pertencer a um único usuário)
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
