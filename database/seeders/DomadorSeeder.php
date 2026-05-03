<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Domador;

class DomadorSeeder extends Seeder
{
    public function run(): void
    {
        $domadores = [
            ['nombre' => 'Daenerys Targaryen', 'ciudad' => 'Rocadragón',  'experiencia' => 10],
            ['nombre' => 'Hiccup Haddock',     'ciudad' => 'Mema',        'experiencia' => 5],
            ['nombre' => 'Eragon Bromsson',    'ciudad' => 'Ellesméra',   'experiencia' => 8],
            ['nombre' => 'Saphira Rider',      'ciudad' => 'Carvahall',   'experiencia' => 3],
        ];

        foreach ($domadores as $data) {
            Domador::create($data);
        }
    }
}