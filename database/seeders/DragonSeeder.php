<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dragon;
use App\Models\Domador;

class DragonSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar domadores por nombre para obtener IDs dinámicamente
        $daenerys = Domador::where('nombre', 'Daenerys Targaryen')->first();
        $hiccup   = Domador::where('nombre', 'Hiccup Haddock')->first();
        $eragon   = Domador::where('nombre', 'Eragon Bromsson')->first();

        $dragons = [
            // Dragones de Daenerys
            ['nombre' => 'Drogon',   'tipo' => 'Fuego',  'poder' => 95, 'domador_id' => $daenerys->id],
            ['nombre' => 'Rhaegal',  'tipo' => 'Fuego',  'poder' => 88, 'domador_id' => $daenerys->id],
            ['nombre' => 'Viserion', 'tipo' => 'Sombra', 'poder' => 85, 'domador_id' => $daenerys->id],

            // Dragones de Hiccup
            ['nombre' => 'Desdentado',  'tipo' => 'Rayo',  'poder' => 90, 'domador_id' => $hiccup->id],
            ['nombre' => 'Garraferrua', 'tipo' => 'Fuego', 'poder' => 75, 'domador_id' => $hiccup->id],

            // Dragones de Eragon
            ['nombre' => 'Saphira',  'tipo' => 'Hielo', 'poder' => 92, 'domador_id' => $eragon->id],
            ['nombre' => 'Thorn',    'tipo' => 'Fuego', 'poder' => 88, 'domador_id' => $eragon->id],
        ];

        foreach ($dragons as $data) {
            Dragon::create($data);
        }
    }
}
