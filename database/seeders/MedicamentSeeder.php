<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class MedicamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = 
        [
            ['name' => 'N/A'],
            ['name' => 'Antihistamínicos'],
            ['name' => 'Analépticos'],
            ['name' => 'Analgésicos'],
            ['name' => 'Anestésicos'],
            ['name' => 'Anti amebianos'],
            ['name' => 'Anti anginosos'],
            ['name' => 'Anti arrítmicos'],
            ['name' => 'Anti infecciosos'],
            ['name' => 'Anti tiroideos'],
            ['name' => 'Anti ulcerosos'],
            ['name' => 'Antiácidos'],
            ['name' => 'Antiácidos y anti ulcerosos'],
            ['name' => 'Antialérgicos'],
            ['name' => 'Antibióticos'],
            ['name' => 'Anticonvulsivos'],
            ['name' => 'Antidepresivos'],
            ['name' => 'Antidiarreicos'],
            ['name' => 'Antiespasmódicos'],
            ['name' => 'Antihelmínticos'],
            ['name' => 'Antiinflamatorio'],
            ['name' => 'Antimicrobianos'],
            ['name' => 'Antiparasitarios'],
            ['name' => 'Antipiréticos'],
            ['name' => 'Antisépticos'],
            ['name' => 'Antitusivos y mucoliticos'],
            ['name' => 'Astringentes'],
            ['name' => 'Broncodilatadores'],
            ['name' => 'Carminativos'],
            ['name' => 'Colagogos'],
            ['name' => 'Desinfectantes'],
            ['name' => 'Diuréticos'],
            ['name' => 'Ecbólicos'],
            ['name' => 'Eméticos'],
            ['name' => 'Esteroides'],
            ['name' => 'Estimulantes'],
            ['name' => 'Expectorantes'],
            ['name' => 'Fungicidas'],
            ['name' => 'Hematúricos'],
            ['name' => 'Hipnóticos'],
            ['name' => 'Hipotensores'],
            ['name' => 'Laxantes'],
            ['name' => 'Meióticos'],
            ['name' => 'Midriáticos'],
            ['name' => 'Narcóticos'],
            ['name' => 'Sedantes'],
            ['name' => 'Vaso dilatadores cerebrales'],
            ['name' => 'Vitaminas y minerales'],

         ];   

         DB::table('medicaments')->insert($fields);
    }
}



