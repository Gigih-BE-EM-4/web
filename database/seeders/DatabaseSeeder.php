<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
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
    // Company::create([
    //     "name" => "Golek",
    //     "profile" => "img.jpg",
    //     "bio" => "Golek is the best bro",
    //     "address" => "Jakarta Selatan",
    //     "category" => "Technology",
    //     "email" => "golek@gmail.com",
    //     "contact" => "081938713808"
    // ]);
    // Company::create([
    //     "name" => "DanaSegar",
    //     "profile" => "img.jpg",
    //     "bio" => "DanaSegar is the best bro",
    //     "address" => "Jakarta Selatan",
    //     "category" => "Technology",
    //     "email" => "danasegar@gmail.com",
    //     "contact" => "081938713808"
    // ]);
    // Company::create([
    //     "name" => "MoguMogu",
    //     "profile" => "img.jpg",
    //     "bio" => "Mogumogu is the best bro",
    //     "address" => "Jakarta Selatan",
    //     "category" => "Technology",
    //     "email" => "mogumogu@gmail.com",
    //     "contact" => "081938713808"
    // ]);

    // Company::create([
    //     "name" => "TiketTiket",
    //     "profile" => "img.jpg",
    //     "bio" => "TiketTiket is the best bro",
    //     "address" => "Jakarta Selatan",
    //     "category" => "Technology",
    //     "email" => "tikettiket@gmail.com",
    //     "contact" => "081938713808"
    // ]);

    // Company::create([
    //     "name" => "TokuToku",
    //     "profile" => "img.jpg",
    //     "bio" => "TokuToku is the best bro",
    //     "address" => "Jakarta Selatan",
    //     "category" => "Technology",
    //     "email" => "tokutoku@gmail.com",
    //     "contact" => "081938713808"
    // ]);
    // Company::create([
    //     "name" => "Tokopedia",
    //     "profile" => "img.jpg",
    //     "bio" => "Mulai aja dulu",
    //     "address" => "Jakarta Selatan",
    //     "category" => "Technology",
    //     "email" => "tokopedia@gmail.com",
    //     "contact" => "081938713808"
    // ]);

    // Company::create([
    //     "name" => "BukaBuku",
    //     "profile" => "img.jpg",
    //     "bio" => "BukaBuku is the best bro",
    //     "address" => "Jakarta Selatan",
    //     "category" => "Technology",
    //     "email" => "bukabuku@gmail.com",
    //     "contact" => "081938713808"
    // ]);

    Company::factory()->count(10)->create();
    Project::factory()->count(10)->create();
    $this->call([

      UserSeeder::class,
    ]);
  }
}
