<?php

namespace Database\Factories;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'visitor_id' => Visitor::factory(),
            'type' => 'passport',
        ];
    }

    public function passport(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'passport',
            'passport_series' => fake()->numerify('####'),
            'passport_number' => fake()->numerify('######'),
            'passport_issue_date' => fake()->dateTimeBetween('-20 years', '-1 year'),
            'passport_issued_by' => 'УМВД России по ' . fake()->city() . ' области',
            'passport_department_code' => fake()->numerify('######'),
        ]);
    }

    public function license(): static
    {
        $regions = [
            'Московская область',
            'Санкт-Петербург',
            'Новосибирская область',
            'Свердловская область',
            'Краснодарский край',
            'Татарстан',
            'Нижегородская область',
        ];

        return $this->state(fn (array $attributes) => [
            'type' => 'license',
            'license_series_number' => fake()->numerify('##########'),
            'license_issue_date' => fake()->dateTimeBetween('-15 years', '-1 year'),
            'license_region' => fake()->randomElement($regions),
            'license_issued_by' => 'ГИБДД ' . fake()->city(),
        ]);
    }

    public function other(): static
    {
        $documents = [
            'Заграничный паспорт',
            'Военный билет',
            'Служебное удостоверение',
            'Удостоверение личности',
        ];

        return $this->state(fn (array $attributes) => [
            'type' => 'other',
            'other_document_name' => fake()->randomElement($documents),
            'other_series_number' => fake()->numerify('##########'),
            'other_series_number_original' => fake()->numerify('## ## ######'),
            'other_issue_date' => fake()->dateTimeBetween('-10 years', '-1 year'),
            'other_issued_by' => fake()->company(),
        ]);
    }
}
