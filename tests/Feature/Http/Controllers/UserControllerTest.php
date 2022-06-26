<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ProjectMember;
use App\Models\Project;
use App\Models\ProjectRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
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
    }

    public function test_register_without_name_field()
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
    }
    public function test_register_without_email_field()
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
    }
    public function test_register_with_invalid_email_field(){
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
    }

    public function test_register_with_duplicate_email_field(){
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
    }

    public function test_register_without_username_field(){
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

    }

    public function test_register_with_invalid_username_length_field(){
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
    }

    public function test_register_without_address_field(){
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The address field is required.",$content["errors"]["address"], );
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
        ]);
    }

    public function test_register_with_invalid_password(){
        //without password field
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'address' => 'Jakarta',
            'username' => 'rifaldy',
            'confirm_password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The password field is required.",$content["errors"]["password"], );
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
        ]);

        
    }

    public function test_register_with_invalid_password_field_length(){
        //password field under 8 character
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'address' => 'Jakarta',
            'username' => 'rifaldy',
            'password' => '12345',
            'confirm_password' => '12345',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The password must be at least 8 characters.",$content["errors"]["password"], );
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
        ]);
    }

    public function test_register_without_confirm_password_field(){
         //without confirm password field
         $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'address' => 'Jakarta',
            'username' => 'rifaldy',
            'confirm' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The confirm password field is required.",$content["errors"]["confirm_password"], );
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
        ]);
    }

    public function test_register_with_password_and_cofirm_password_missmatch(){
        $response = $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'address' => 'Jakarta',
            'username' => 'rifaldy',
            'confirm' => 'rifaldi111',
            'confirm_password' => 'rifaldi1113',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(422);
        $this->assertContains("The confirm password and password must match.",$content["errors"]["confirm_password"], );
        $this->assertDatabaseMissing('users', [
            'email' => 'rifaldy@gmail.com',
        ]);
    }


    // ===========================================================================================================
    // ================================ TEST LOGIN ============================================================
    // ===========================================================================================================

    public function test_login_with_valid_username_and_password()
    {
        $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);

        $response = $this->postJson('/api/user/login', [
            'username' => 'rifaldy',
            'password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(201);
        $this->assertNotNull($content["data"]["token"]);
        $this->assertNull($content["errors"]);
        
        

        // $response = $this->get('/api/user/ping', [
        //     'Authorization' => '',
        // ]);
        // $response->assertStatus(200);
    }
    public function test_login_with_valid_email_and_password(){
        $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $response = $this->postJson('/api/user/login', [
            'username' => 'rifaldy@gmail.com',
            'password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(201);
        $this->assertNotNull($content["data"]["token"]);
        $this->assertNull($content["errors"]);
    }

    public function test_login_with_invalid_username(){
        $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $response = $this->postJson('/api/user/login', [
            'username' => 'rifalds',
            'password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(401);
        $this->assertEquals("user/password not match",$content["errors"] );
        $this->assertNotNull($content["errors"]);
    }

    public function test_login_with_invalid_email(){
        $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $response = $this->postJson('/api/user/login', [
            'username' => 'rifaldy@gmail.coms',
            'password' => 'rifaldi111',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(401);
        $this->assertEquals("user/password not match",$content["errors"] );
        $this->assertNotNull($content["errors"]);
    }

    public function test_login_with_invalid_password(){
        $this->postJson('/api/user/register', [
            'name' => 'Rifaldy Elninoru',
            'email' => 'rifaldy@gmail.com',
            'username' => 'rifaldy',
            'address' => 'Jakarta Selatan',
            'password' => 'rifaldi111',
            'confirm_password' => 'rifaldi111',
        ]);
        $response = $this->postJson('/api/user/login', [
            'username' => 'rifaldy',
            'password' => 'rifaldi1112',
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(401);
        $this->assertEquals("user/password not match",$content["errors"] );
        $this->assertNotNull($content["errors"]);
    }

    // public function test_logout_with_valid_data() {
    //     $user = User::factory()->create();

    //     Sanctum::actingAs(
    //         $user
    //     );

    //     $response = $this->postJson('/api/user/logout');

    //     $response->assertStatus(200);
    // }

    public function test_verify_with_valid_token() {
        $token = "test333";
        $user = User::factory()->create([
            "verify"=>$token,
        ]);
        $response = $this->get('/api/user/verify/'.$token);
    
        $response->assertStatus(201);
        
        $response = $this->get('/api/user/verify/');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'verify' => null,
        ]);
    }

    public function test_verify_with_invalid_token() {
        $token = "test333";
        $user = User::factory()->create([
            "verify"=>$token,
        ]);
        $response = $this->get('/api/user/verify/'.$token."1");
    
        $response->assertStatus(404);
        
        $response = $this->get('/api/user/verify/');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'verify' => $token,
        ]);
    }

    public function test_forgot_password_with_true_email(){
        $user = User::factory()->create();
        $response = $this->get('/api/user/forgot-password/'.$user->email);

        $response->assertStatus(200);

        $content = $response->decodeResponseJson();
        $this->assertNull($content["errors"]);


        $pass = '12345678';
        $response = $this->postJson('/api/user/login', [
            'username' => $user->username,
            'password' => $pass,
        ]);
        $content = $response->decodeResponseJson();

        $response->assertStatus(201);
        $this->assertNotNull($content["data"]["token"]);
        $this->assertNull($content["errors"]);

    }

    public function test_forgot_password_with_false_email(){
        $response = $this->get('/api/user/forgot-password/test@gmail.com');
        $content = $response->decodeResponseJson();
        $response->assertStatus(404);
        $this->assertEquals($content["errors"],"user not found");

    }

    public function test_update_user_with_valid_data(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user', [
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
    }

    public function test_update_user_without_token(){
        $response = $this->postJson('/api/user', [
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('users', [
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
    }

    public function test_update_user_without_name(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user', [
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
    }

    public function test_update_user_without_bio(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user', [
            'name' => 'Rifaldy Elninoru',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Rifaldy Elninoru',
            'bio' => $user->bio,
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
        
    }
    public function test_update_user_without_address(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user', [
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'last_education' => 'SMK',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => $user->address,
            'last_education' => 'SMK',
        ]);
    }
    
    public function test_update_user_without_last_education(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user', [
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => $user->last_education,
        ]);
    }

    public function test_update_user_with_other_data(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user', [
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
            'verify' => 'test',
            'password' => 'test',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Rifaldy Elninoru',
            'bio' => 'Hello Im rifaldy',
            'address' => 'jl.x',
            'last_education' => 'SMK',
        ]);
        $this->assertDatabaseMissing('users', [
            'verify' => 'test',
        ]);
        $this->assertDatabaseMissing('users', [
            'password' => 'test',
        ]);
    }

    public function test_change_password_with_valid_data(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user/changepassword', [
            'password' => '12345678',
            'confirm_password' => '12345678',
        ]);
        $response->assertStatus(201);
        
        // $responses = $this->postJson('/api/user/login', [
        //     'username' => $user->username,
        //     'password' => '12345678',
        // ]);

        // $responses->assertStatus(201);
    }
    public function test_change_password_with_invalid_token(){
        
        $response = $this->postJson('/api/user/changepassword', [
            'password' => '12345678',
            'confirm_password' => '12345678',
        ]);
        $response->assertStatus(401);
        
    }

    public function test_change_password_without_password_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user/changepassword', [
            'confirm_password' => '12345678',
        ]);
        $response->assertStatus(422);

        $content = $response->decodeResponseJson();
        $this->assertContains("The password field is required.",$content["errors"]["password"]);
    }
    public function test_change_password_without_invalid_password_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user/changepassword', [
            'password' => '111',
            'confirm_password' => '12345678',
        ]);
        $response->assertStatus(422);

        $content = $response->decodeResponseJson();
        $this->assertContains("The password must be at least 8 characters.",$content["errors"]["password"]);
    }

    public function test_change_password_without_confirm_password_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user/changepassword', [
            'password' => '12345678',
        ]);
        $response->assertStatus(422);

        $content = $response->decodeResponseJson();
        $this->assertContains("The confirm password field is required.",$content["errors"]["confirm_password"]);
    }

    public function test_change_password_with_invalid_confirm_password_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user/changepassword', [
            'password' => '12345678',
            'confirm_password' => '111',
        ]);
        $response->assertStatus(422);

        $content = $response->decodeResponseJson();
        $this->assertContains("The confirm password must be at least 8 characters.",$content["errors"]["confirm_password"]);
        
    }

    public function test_change_password_with_unmatch_password_and_confirm_password_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $response = $this->postJson('/api/user/changepassword', [
            'password' => '12345678',
            'confirm_password' => '123456789',
        ]);
        $response->assertStatus(422);

        $content = $response->decodeResponseJson();
        $this->assertContains("The confirm password and password must match.",$content["errors"]["confirm_password"]);
        
    }

    public function test_change_profile_with_valid_jpg_data(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('profile.jpg');

        $response = $this->postJson('/api/user/changeprofile', [
            'profile' => $profile,
        ]);
        $time = time();
        $response->assertStatus(201);
        $fileName = $profile->getClientOriginalName();
        $this->assertFileExists(public_path() . '/User/Profile/' . $time . $fileName);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile' =>  '/User/Profile/' . $time . $fileName,
        ]);
        unlink(public_path() . '/User/Profile/' . $time . $fileName);

    }

    public function test_change_profile_with_valid_png_data(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('profile.png');

        $response = $this->postJson('/api/user/changeprofile', [
            'profile' => $profile,
        ]);
        $time = time();
        $response->assertStatus(201);
        $fileName = $profile->getClientOriginalName();
        $this->assertFileExists(public_path() . '/User/Profile/' . $time . $fileName);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile' =>  '/User/Profile/' . $time . $fileName,
        ]);
        unlink(public_path() . '/User/Profile/' . $time . $fileName);

    }

    public function test_change_profile_with_valid_data(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('profile.gif');

        $response = $this->postJson('/api/user/changeprofile', [
            'profile' => $profile,
        ]);
        $time = time();
        $response->assertStatus(201);
        $fileName = $profile->getClientOriginalName();
        $this->assertFileExists(public_path() . '/User/Profile/' . $time . $fileName);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'profile' =>  '/User/Profile/' . $time . $fileName,
        ]);
        unlink(public_path() . '/User/Profile/' . $time . $fileName);

    }

    public function test_change_profile_without_token(){
        $profile = UploadedFile::fake()->image('profile.jpg');

        $response = $this->postJson('/api/user/changeprofile', [
            'profile' => $profile,
        ]);
        $time = time();
        $response->assertStatus(401);
        $fileName = $profile->getClientOriginalName();
        $this->assertFileDoesNotExist(public_path() . '/User/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'profile' =>  '/User/Profile/' . $time . $fileName,
        ]);

    }

    public function test_change_profile_with_invalid_data(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('profile.pdf');

        $response = $this->postJson('/api/user/changeprofile', [
            'profile' => $profile,
        ]);
        $response->assertStatus(422);

        $content = $response->decodeResponseJson();
        $this->assertContains("The profile must be an image.",$content["errors"]["profile"]);

        $time = time();
        $fileName = $profile->getClientOriginalName();
        $this->assertFileDoesNotExist(public_path() . '/User/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'profile' =>  '/User/Profile/' . $time . $fileName,
        ]);

    }

    // 'project_id',
    // 'project_role_id',
    // 'user_id',
    // 'certificate',
    public function test_get_all_user_certificate(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $certificate= ["test.pdf","test1.pdf"];
        ProjectMember::create([
            'project_id' => 1,
            'project_role_id' => 1,
            'user_id' => $user->id,
            'certificate' => $certificate[0],
        ]);
        ProjectMember::create([
            'project_id' => 2,
            'project_role_id' => 2,
            'user_id' => $user->id,
            'certificate' => $certificate[1],
        ]);

        $response = $this->get('/api/user/certificates');

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertContains($certificate[0],$content["data"][0]);
        $this->assertContains($certificate[1],$content["data"][1]);
    }

    public function test_get_all_user_certificate_without_token(){
        $response = $this->get('/api/user/certificates');

        $response->assertStatus(401);
        $content = $response->decodeResponseJson();

        $this->assertEquals("Unauthenticated.",$content["errors"]);
    }

    public function test_get_all_user_certificate_if_user_dosent_have_certificate(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );

        $response = $this->get('/api/user/certificates');

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertEmpty($content["data"]);
    }
    public function test_get_all_user_projects(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $project = Project::factory()->create();
        $project2 = Project::factory()->create();
        $project_role = ProjectRole::factory()->create([
            "project_id" => $project->id,
        ]);
        $project_role2 = ProjectRole::factory()->create([
            "project_id" => $project2->id,
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'project_role_id' => $project_role->id,
            'user_id' => $user->id,
            'certificate' => "test.pdf",
        ]);

        ProjectMember::create([
            'project_id' => $project2->id,
            'project_role_id' => $project_role2->id,
            'user_id' => $user->id,
            'certificate' => "test2.pdf",
        ]);

        $response = $this->get('/api/user/projects');
        $content = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals($project->id,$content["data"][0]["project"]["id"]);
        $this->assertEquals($project->name,$content["data"][0]["project"]["name"]);
        $this->assertEquals($project->images,$content["data"][0]["project"]["images"]);
        $this->assertEquals($project->description,$content["data"][0]["project"]["description"]);
        $this->assertEquals($project->active,$content["data"][0]["project"]["active"]);
        $this->assertEquals($project->isfinished,$content["data"][0]["project"]["isfinished"]);
        $this->assertEquals($project->company_id,$content["data"][0]["project"]["company_id"]);

        $this->assertEquals($project_role->id,$content["data"][0]["project"]["role"]["id"]);
        $this->assertEquals($project_role->name,$content["data"][0]["project"]["role"]["name"]);
        $this->assertEquals($project_role->quota,$content["data"][0]["project"]["role"]["quota"]);
        $this->assertEquals($project_role->description,$content["data"][0]["project"]["role"]["description"]);
        $this->assertEquals($project_role->project_id,$content["data"][0]["project"]["role"]["project_id"]);
        $this->assertEquals($project_role->extra_question,$content["data"][0]["project"]["role"]["extra_question"]);

        $this->assertEquals($project2->id,$content["data"][1]["project"]["id"]);
        $this->assertEquals($project2->name,$content["data"][1]["project"]["name"]);
        $this->assertEquals($project2->images,$content["data"][1]["project"]["images"]);
        $this->assertEquals($project2->description,$content["data"][1]["project"]["description"]);
        $this->assertEquals($project2->active,$content["data"][1]["project"]["active"]);
        $this->assertEquals($project2->isfinished,$content["data"][1]["project"]["isfinished"]);
        $this->assertEquals($project2->company_id,$content["data"][1]["project"]["company_id"]);

        $this->assertEquals($project_role2->id,$content["data"][1]["project"]["role"]["id"]);
        $this->assertEquals($project_role2->name,$content["data"][1]["project"]["role"]["name"]);
        $this->assertEquals($project_role2->quota,$content["data"][1]["project"]["role"]["quota"]);
        $this->assertEquals($project_role2->description,$content["data"][1]["project"]["role"]["description"]);
        $this->assertEquals($project_role2->project_id,$content["data"][1]["project"]["role"]["project_id"]);
        $this->assertEquals($project_role2->extra_question,$content["data"][1]["project"]["role"]["extra_question"]);
            
    }

    public function test_get_all_user_projects_without_token(){
        
        
        $response = $this->get('/api/user/projects');

        $response->assertStatus(401);
        $content = $response->decodeResponseJson();

        $this->assertEquals("Unauthenticated.",$content["errors"]);
    }
    public function test_get_all_user_projects_if_user_dosent_have_projects(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );

        $response = $this->get('/api/user/projects');

        $response->assertStatus(200);
        $content = $response->decodeResponseJson();

        $this->assertEmpty($content["data"]);
    }

    

    
}   
