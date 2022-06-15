<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_with_valid_data()
    {
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(201);
        $this->assertNull($content["errors"]);
        $this->assertDatabaseHas('users', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
        ]);

        $responseLogin = $this->postJson('/api/user/login', [
            'username' => 'rifaldy',
            'password' => 'rifaldi111',
        ]);

        $response->assertStatus(201);
    }
}
