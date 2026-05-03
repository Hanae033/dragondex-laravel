<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Torneo extends Model
{
    use HasFactory;

    protected $table = 'torneos';

    protected $fillable = [
        'dragon_local_id',
        'dragon_visitante_id',
        'fecha',
        'resultado',
    ];

    public function dragonLocal(): BelongsTo
    {
        return $this->belongsTo(Dragon::class, 'dragon_local_id');
    }

    public function dragonVisitante(): BelongsTo
    {
        return $this->belongsTo(Dragon::class, 'dragon_visitante_id');
    }
}