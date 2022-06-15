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

        $responseLogin->assertStatus(201);
    }

    public function test_register_with_invalid_name_field()
    {
        //without name
        $response = $this->postJson('/api/user/register', [
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();
        $response->assertStatus(422);
        $this->assertContains("The name field is required.",$content["errors"]["name"]);
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
        ]);

        $responseLogin = $this->postJson('/api/user/login', [
            'username' => 'rifaldy',
            'password' => 'rifaldi111',
        ]);

        $responseLogin->assertStatus(401);
    }
    public function test_register_with_invalid_email_field()
    {   
        //without email
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The email field is required.",$content["errors"]["email"], );
        $this->assertDatabaseMissing('users', [
            'name' => 'Rifaldy Elninoru',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
        ]);

        $responseLogin = $this->postJson('/api/user/login', [
            'username' => 'rifaldy',
            'password' => 'rifaldi111',
        ]);

        $responseLogin->assertStatus(401);

        //invalid email
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The email must be a valid email address.",$content["errors"]["email"], );
        $this->assertDatabaseMissing('users', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
        ]);

        $responseLogin = $this->postJson('/api/user/login', [
            'username' => 'rifaldy',
            'password' => 'rifaldi111',
        ]);

        $responseLogin->assertStatus(401);

        //email already exist
        $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);

        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi222',
            'confirm_password' => 'rifaldi222',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The email has already been taken.",$content["errors"]["email"], );

        $responseLogin = $this->postJson('/api/user/login', [
            'username' => 'rifaldy',
            'password' => 'rifaldi222',
        ]);

        $responseLogin->assertStatus(401);

    }
    public function test_register_with_invalid_username_field(){
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The username field is required.",$content["errors"]["username"], );
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
        ]);

        $responseLogin = $this->postJson('/api/user/login', [
            'username' => 'rifaldy@gmail.com',
            'password' => 'rifaldi111',
        ]);

        $responseLogin->assertStatus(401);

        //username under 5 character
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifa',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The username must be at least 5 characters.",$content["errors"]["username"], );
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
        ]);

        $responseLogin = $this->postJson('/api/user/login', [
            'username' => 'rifaldy@gmail.com',
            'password' => 'rifaldi111',
        ]);

        $responseLogin->assertStatus(401);
    }


    

}
