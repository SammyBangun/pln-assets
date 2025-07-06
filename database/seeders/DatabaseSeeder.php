<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(\Database\Seeders\DivisionSeeder::class);
        $this->call(\Database\Seeders\IdentificationSeeder::class);
        $this->call(\Database\Seeders\DisruptionSeeder::class);
        $this->call(\Database\Seeders\AssetTypeSeeder::class);
        $this->call(\Database\Seeders\OperatorSeeder::class);
        $this->call(\Database\Seeders\DetailDisruptionSeeder::class);
        $this->call(\Database\Seeders\DeliverableSeeder::class);
    }
}
