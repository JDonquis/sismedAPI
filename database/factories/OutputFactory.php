<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Output>
 */
class OutputFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public static $outputCode = 0;

    public function definition(): array
    {
        $randomDate = $this->faker->date();
        $day = date('d', strtotime($randomDate));
        $month = date('m', strtotime($randomDate));
        $year = date('Y', strtotime($randomDate));

        $entitiesNames = [

            '1' => 'Secretaría de Salud',
            '1-1' => 'Hospital Cruz Verde',
            '1-2' => 'Hospital Velitas',
            '1-3' => 'Hospital Chimpire',
            '1-4' => 'Hospital Universitario Dr. Alfredo Van Grieken',
        ];

        $products = [

            1 => 'Acetaminofen 30 Tabletas 100mg',
            2 => 'Guantes 2 N/A 10cm',

        ];

        // return [
        //     'entity_code' => $this->faker->randomElement(['1','1-1','1-2','1-3','1-4']),
        //     'product_id' => $this->faker->randomElement([1,2]),
        //     'quantity' => $this->faker->randomElement([10,5,8]),
        //     'organization_id' => 3,
        //     'guide' => $this->faker->unique()->numberBetween(1,100),
        //     'lote_number' => ,
        //     'authority_fullname' => 'Jesús Ramirez',
        //     'authority_ci' => '13443385',
        //     'receiver_fullname' => 'Juan Pablo',
        //     'receiver_ci' => '30847627',
        //     'departure_time' => '9:00',
        //     'day' => $day,
        //     'month' => $month,
        //     'year' => $year,
        //     'description' => $this->faker->text,
        //     'search' => $this->entity_code . ' ' . $entitiesNames[$this->entity_code] . ' ' . $products[$this->product_id] . ' ' . $this->quantity . ' ' . 'Secretaría de Salud' . ' ' . $this->guide . ' ' . $this->lote_number . ' ' . $this->expiration_date . ' ' . 'Buen estado' . ' ' . $this->authority_fullname . ' ' . $this->authority_ci . ' ' . $this->arrival_time . ' ' . $this->day . ' ' . $this->month . ' ' . $this->year . ' ' . $this->description,
        // ];
    }
}
