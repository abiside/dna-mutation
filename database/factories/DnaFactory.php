<?php

namespace Database\Factories;

use App\Models\Dna;
use Illuminate\Database\Eloquent\Factories\Factory;

class DnaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dna::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    /**
     * Indicate that the code doesn't have mutation on it
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withoutMutation()
    {
        return $this->state(function (array $attributes) {
            return [
                'code' => 'GTCACT,ACTGCG,GGCAGA,TTTATT,CGTGAC,AGCGTA',
                'mutations' => 0,
            ];
        });
    }

    /**
     * Indicate that the code doesn't have mutation on it
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withMutation()
    {
        return $this->state(function (array $attributes) {
            return [
                'code' => 'GTCACT,ATCCCC,GGAAGA,TGTATT,CGTGAC,AGCGTA',
                'mutations' => 3,
            ];
        });
    }
}
