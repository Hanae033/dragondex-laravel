<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dragon extends Model
{
    use HasFactory; // ← OBLIGATORIO para los tests

    protected $table = 'dragons';

    protected $fillable = ['nombre', 'tipo', 'poder', 'domador_id'];

    // Un dragón pertenece a un domador
    public function domador(): BelongsTo
    {
        return $this->belongsTo(Domador::class, 'domador_id');
    }

    // Un dragón puede ser local en muchos torneos
    public function torneosComoLocal(): HasMany
    {
        return $this->hasMany(Torneo::class, 'dragon_local_id');
    }

    // Un dragón puede ser visitante en muchos torneos
    public function torneosComoVisitante(): HasMany
    {
        return $this->hasMany(Torneo::class, 'dragon_visitante_id');
    }
}