<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $entitiesNames = [

            '1' => 'Secretaría de Salud',
            '1-1' => 'Hospital Cruz Verde',
            '1-2' => 'Hospital Velitas',
            '1-3' => 'Hospital Chimpire',
            '1-4' => 'Hospital Universitario Dr. Alfredo Van Grieken',
        ];

        $name = fake()->name();
        $lastName = fake()->lastName();
        $entityCode = fake()->randomElement(['1','1-1','1-2','1-3','1-4']);
        $charge = fake()->randomElement(['Jefe de Almacén','Jefe de departamento de sistemas','Administrador de almacén']);
        $username = substr(fake()->unique()->username(), 0, 20);
        $ci = strval(fake()->unique()->numberBetween(14000000,33000000));
        $phoneNumber = fake()->e164PhoneNumber();
        $address = fake()->address();
        $email = fake()->unique()->safeEmail();
        $password = static::$password ??= Hash::make('password');
        $rememberToken = Str::random(10);


        return [
            'entity_code' => $entityCode,
            'charge' => $charge,
            'username' => $username, 
            'name' => $name,
            'last_name' => $lastName,
            'ci' => $ci, 
            'phone_number' => $phoneNumber,
            'address' => $address, 
            'email' => $email,
            'password' => $password, 
            'remember_token' => $rememberToken, 
            'search' => $name . ' ' . $lastName . ' ' . $entityCode . ' ' . $entitiesNames[$entityCode] . ' ' . $charge . ' ' . $username . ' ' . $ci . ' ' . $phoneNumber . ' ' . $address . ' ' . $email,
        ];
    }

}
