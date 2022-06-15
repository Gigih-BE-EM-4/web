<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    User::insert([
      "name" => "user1",
      "email" => "user@gmail.com",
      "profile" => "test.jpg",
      "bio" => "halo",
      "username" => "user1",
      "address" => "test",
      "last_education" => "SMK",
      "company_id" => "",
      "verify" => "asdawdawdawdasdaw",
      "password" => "12345678",
    ]);
    //    User::insert([
    //     "name" => "user1",
    //     "email" => "user1@gmail.com",
    //     "profile" => "test.jpg",
    //     "bio" => "halo",
    //     "username" => "user1",
    //     "address" => "test",
    //     "last_education" => "SMK",
    //     "company_id" => "",
    //     "verify" => "asdawdawdawdasdaw",
    //     "password" => "12345678",
    // ]);
    User::insert([
      "name" => "user2",
      "email" => "user2@gmail.com",
      "profile" => "test.jpg",
      "bio" => "halo",
      "username" => "user2",
      "address" => "awsdawdasdaw",
      "last_education" => "SMK",
      "company_id" => "",
      "verify" => "asdawdawdawdasdaw",
      "password" => "12345678",
    ]);
    User::insert([
      "name" => "user3",
      "email" => "user3@gmail.com",
      "profile" => "test.jpg",
      "bio" => "halo",
      "username" => "user3",
      "address" => "test",
      "last_education" => "SMK",
      "company_id" => "",
      "verify" => "asdawdawdawdasdaw",
      "password" => "12345678",
    ]);
  }
}
