<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Operator;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operators =  [
            ["Operator 1", "08123456789"],
            ["Operator 2", "08123456729"],
            ["Operator 3", "08123456759"],
        ];

        foreach ($operators as $operator) {
            Operator::create([
                'nama_petugas' => $operator[0],
                'no_hp' => $operator[1],
            ]);
        }
    }
}
