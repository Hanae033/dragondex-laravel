<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Torneo;
use App\Models\Dragon;

class TorneoSeeder extends Seeder
{
    public function run(): void
    {
        $drogon     = Dragon::where('nombre', 'Drogon')->first();
        $saphira    = Dragon::where('nombre', 'Saphira')->first();
        $desdentado = Dragon::where('nombre', 'Desdentado')->first();
        $rhaegal    = Dragon::where('nombre', 'Rhaegal')->first();

        $torneos = [
            [
                'dragon_local_id'     => $drogon->id,
                'dragon_visitante_id' => $saphira->id,
                'fecha'               => '2025-03-15',
                'resultado'           => 'local',
            ],
            [
                'dragon_local_id'     => $desdentado->id,
                'dragon_visitante_id' => $rhaegal->id,
                'fecha'               => '2025-04-01',
                'resultado'           => 'empate',
            ],
        ];

        foreach ($torneos as $data) {
            Torneo::create($data);
        }
    }
}