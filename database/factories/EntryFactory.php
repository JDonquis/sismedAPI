<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public static $entryCode = 0;

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
        self::$entryCode+= 1;
        $entityCode = fake()->randomElement(['1','1-1','1-2','1-3','1-4']);
        $entryCode = self::$entryCode;
        $productId = $this->faker->randomElement([1,2]);
        $quantity = $this->faker->randomElement([100,25,10,30]);
        $organizationId = 2;
        $guide = '1';
        $loteNumber = $this->faker->numberBetween(10000000,30000000);
        $authorityFullname = 'El vigilante';
        $authorityCi = '13443387';
        $arrivalTime = '9:00';
        $description = $this->faker->text;

        $userId = User::where('entity_code',$entityCode)->pluck('id')->first();

        return [
            'entity_code' => $entityCode,
            'entry_code' => $entryCode,
            'product_id' => $productId,
            'quantity' => $quantity,
            'organization_id' => $organizationId,
            'guide' => $guide,
            'lote_number' => $loteNumber,
            'expiration_date' => $randomDate,
            'condition_id' => 1,
            'authority_fullname' => $authorityFullname,
            'authority_ci' => $authorityCi,
            'arrival_time' => $arrivalTime,
            'day' => $day,
            'month' => $month,
            'year' => $year,
            'description' => $description,
            'user_id' => $userId,
            'search' => $entityCode . ' ' . $entitiesNames[$entityCode] . ' ' . $products[$productId] . ' ' . $quantity . ' ' . 'MPPS' . ' ' . $guide . ' ' . $loteNumber . ' ' . $randomDate . ' ' . 'Buen estado' . ' ' . $authorityFullname . ' ' . $authorityCi . ' ' . $arrivalTime . ' ' . $day . ' ' . $month . ' ' . $year . ' ' . $description . ' ' . $entryCode,
        ];
    }
}
