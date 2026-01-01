<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $departments = [
            'Отдел кадров',
            'Бухгалтерия',
            'Отдел маркетинга',
            'Юридический отдел',
            'Отдел продаж',
            'IT-отдел',
            'Отдел разработки',
            'Отдел логистики',
            'Отдел закупок',
            'Служба безопасности',
            'Административный отдел',
            'Отдел контроля качества',
            'Производственный отдел',
            'Финансовый отдел',
            'Отдел по работе с клиентами',
        ];

        return [
            'name' => fake()->unique()->randomElement($departments),
        ];
    }
}
