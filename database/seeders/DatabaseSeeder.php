<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Company::create([
            "name" => "Golek",
            "profile" => "img.jpg",
            "bio" => "Golek is the best bro",
            "address" => "Jakarta Selatan",
            "category" => "Technology",
            "email" => "golek@gmail.com",
            "contact" => "081938713808"
        ]);

        Company::create([
            "name" => "TokuToku",
            "profile" => "img.jpg",
            "bio" => "TokuToku is the best bro",
            "address" => "Jakarta Selatan",
            "category" => "Technology",
            "email" => "tokutoku@gmail.com",
            "contact" => "081938713808"
        ]);
        Company::create([
            "name" => "Tokopedia",
            "profile" => "img.jpg",
            "bio" => "Mulai aja dulu",
            "address" => "Jakarta Selatan",
            "category" => "Technology",
            "email" => "tokopedia@gmail.com",
            "contact" => "081938713808"
        ]);

        Company::create([
            "name" => "BukaBuku",
            "profile" => "img.jpg",
            "bio" => "BukaBuku is the best bro",
            "address" => "Jakarta Selatan",
            "category" => "Technology",
            "email" => "bukabuku@gmail.com",
            "contact" => "081938713808"
        ]);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
