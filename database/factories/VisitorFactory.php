<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitor>
 */
class VisitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('ru_RU');

        $gender = $faker->randomElement(['male', 'female']);
        $lastName = $faker->lastName($gender);
        $firstName = $faker->firstName($gender);
        $middleName = $faker->middleName($gender);

        $entryDateTime = $faker->dateTimeBetween('-10 days', 'now');
        $exitDateTime = (clone $entryDateTime)->modify('+' . $faker->numberBetween(6, 10) . ' hours');

        $positions = [
            'Директор',
            'Менеджер',
            'Специалист',
            'Инженер',
            'Бухгалтер',
            'Юрист',
            'Программист',
            'Аналитик',
            'Консультант',
            'Координатор',
            'Администратор',
            'Секретарь',
            'Руководитель отдела',
            'Старший специалист',
            'Ведущий инженер',
        ];

        return [
            'full_name' => "{$lastName} {$firstName} {$middleName}",
            'department_id' => Department::inRandomOrder()->first()?->id ?? Department::factory(),
            'birth_date' => fake()->dateTimeBetween('-65 years', '-18 years'),
            'position' => fake()->randomElement($positions),
            'phone' => '+7' . fake()->numerify('##########'),
            'entry_datetime' => $entryDateTime,
            'exit_datetime' => $exitDateTime,
            'remarks' => fake()->boolean(30) ? fake()->sentence() : null,
            'created_by' => auth()->id(),
            'updated_by' => null,
            'deleted_by' => null,
        ];
    }
}
