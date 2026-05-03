<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domador extends Model
{
    use HasFactory; // ← OBLIGATORIO para los tests

    protected $table = 'domadores';

    protected $fillable = ['nombre', 'ciudad', 'experiencia'];

    // Un domador tiene muchos dragones
    public function dragons(): HasMany
    {
        return $this->hasMany(Dragon::class, 'domador_id');
    }
}