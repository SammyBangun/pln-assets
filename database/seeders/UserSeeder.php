<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => '2205102066',
                'name' => 'Admin Kahlil',
                'email' => 'admink@gmail.com',
                'password' => bcrypt('123123123'),
                'divisi' => '2',
                'role' => 'admin',
            ],
            [
                'id' => '2205102069',
                'name' => 'Rahmat Tahalu',
                'email' => 'rt@gmail.com',
                'password' => bcrypt('123123123'),
                'divisi' => '4',
                'role' => 'petugas',
            ],
            [
                'id' => '2205102062',
                'name' => 'Gatra Parlefi',
                'email' => 'gp@gmail.com',
                'password' => bcrypt('123123123'),
                'divisi' => '3',
                'role' => 'user',
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
