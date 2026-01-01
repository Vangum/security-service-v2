<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Document;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Visitor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'username' => 'admin',
        ]);

        Department::factory(15)->create();


        Visitor::factory(100)
            ->has(Document::factory()->passport())
            ->create();

        Visitor::factory(100)
            ->has(Document::factory()->license())
            ->create();

        Visitor::factory(100)
            ->has(Document::factory()->other())
            ->create();
    }
}
