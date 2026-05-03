<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DomadorSeeder::class,  // 1º — sin FK
            DragonSeeder::class,   // 2º — necesita domadores
            TorneoSeeder::class,   // 3º — necesita dragons
        ]);
    }
}
