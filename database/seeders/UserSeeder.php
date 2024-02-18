<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        $entitiesNames = [

            '1' => 'Secretaría de Salud',
            '1-1' => 'Hospital Cruz Verde',
            '1-2' => 'Hospital Velitas',
            '1-3' => 'Hospital Chimpire',
            '1-4' => 'Hospital Universitario Dr. Alfredo Van Grieken',
        ];

        $fields = [

            [
                'entity_code' => 1,
                'charge' =>'Jefe de Almacén',
                'username' => 'admin',
                'name' => 'Admin',
                'last_name' => 'Admin',
                'ci' => '123456789',
                'phone_number' => '04125800610',
                'address' => 'Caracas',
                'email' => 'juancho070902@gmail.com',
                'password' => Hash::make('admin'),
                'remember_token' => null,
                'search' => 'Admin' . ' ' . 'Admin' . ' ' . 1 . ' ' . 'Secretaría de Salud' . ' ' . 'Jefe de Almacén' . ' ' . 'admin' . ' ' . '123456789' . ' ' . '04125800610' . ' ' . 'Caracas' . ' ' . 'juancho070902@gmail.com',
            ],
        
        ];
        DB::table('users')->insert($fields);
        User::factory(300)->create();
        
    }
}
