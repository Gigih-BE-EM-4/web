<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test Create Company With Valid Data
    public function test_create_company_with_valid_data()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Kerja',
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'sobatkerja1@gmail.com',
            'contact' => '081287127817271'
        ]);

        $time = time();
        $fileName = $profile->getClientOriginalName();
        $response->assertCreated()->assertExactJson([
            'meta' => [
                'code' => 201,
                'status' => 'success',
                'message' => 'Company has been created'
            ],
            'data' => [
                'name' => 'Sobat Kerja',
                'address' => 'Jakarta Selatan',
                'category' => 'Technology',
                'email' => 'sobatkerja1@gmail.com',
                'contact' => '081287127817271',
                'profile' => '/Company/Profile/' . $time . $fileName,
                'bio' => 'Perusahaan terbaik di jakarta raya',
            ],
            'errors' => null
        ]);
        $this->assertFileExists(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseHas('companies', [
            'name' => 'Sobat Kerja',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'sobatkerja1@gmail.com',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => 1
        ]);
        $this->assertDatabaseCount('companies', 1);
        unlink(public_path() . '/Company/Profile/' . $time . $fileName);
    }

    // Test Create Company Without Name Field
    public function test_create_company_without_name_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'sobatkerja2@gmail.com',
            'contact' => '081287127817271'
        ]);
        $time = time();
        $fileName = $profile->getClientOriginalName();

        $response->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'name' => ['The name field is required.']
            ]
        ]);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Sobat Kerja',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'sobatkerja2@gmail.com',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'company_id' => 2
        ]);
    }

    // Test Create Company Without Address Field
    public function test_create_company_without_address_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Belajar',
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'category' => 'Technology',
            'email' => 'sobatbelajar@gmail.com',
            'contact' => '081287127817271'
        ]);
        $time = time();
        $fileName = $profile->getClientOriginalName();

        $response->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'address' => ['The address field is required.']
            ]
        ]);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Sobat Belajar',
            'category' => 'Technology',
            'email' => 'sobatbelajar@gmail.com',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'company_id' => 2
        ]);
    }

    // Test Create Company Without Category Field
    public function test_create_company_without_category_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Bermain',
            'profile' => $profile,
            'address' => 'Jakarta Selatan',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'email' => 'sobatbermain@gmail.com',
            'contact' => '081287127817271'
        ]);
        $time = time();
        $fileName = $profile->getClientOriginalName();

        $response->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'category' => ['The category field is required.']
            ]
        ]);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Sobat Bermain',
            'address' => 'Jakarta Selatan',
            'email' => 'sobatbermain@gmail.com',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'company_id' => 2
        ]);
    }

    // Test Create Company Without Email Field
    public function test_create_company_without_email_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Belajar',
            'profile' => $profile,
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'contact' => '081287127817271'
        ]);
        $time = time();
        $fileName = $profile->getClientOriginalName();

        $response->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'email' => ['The email field is required.']
            ]
        ]);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Sobat Belajar',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'company_id' => 2
        ]);
    }

    // Test Create Company Without Contact Field
    public function test_create_company_without_contact_field(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Dagang',
            'profile' => $profile,
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'email' => 'sobatdagang@gmail.com',
        ]);
        $time = time();
        $fileName = $profile->getClientOriginalName();

        $response->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'contact' => ['The contact field is required.']
            ]
        ]);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Sobat Dagang',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'sobatdagang@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'company_id' => 2
        ]);
    }

    // Test Create Company With Invalid Email Format
    public function test_create_company_with_invalid_email_format(){
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );

        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Jualan',
            'profile' => $profile,
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'contact' => '081287127817271',
            'email' => 'sobatjualan@gmail',
        ]);
        $time = time();
        $fileName = $profile->getClientOriginalName();

        $response->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'email' => ['The email must be a valid email address.']
            ]
        ]);
        $this->assertDatabaseMissing('companies', [
            'name' => 'Sobat Jualan',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'sobatjualan@gmail',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '081287127817271',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'company_id' => 2
        ]);
    }

    // Test Create Company With Duplicate Email
    public function test_create_company_with_duplicate_email(){
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $profile1 = UploadedFile::fake()->image('company_profile134.jpg');

        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );

        $response = $this->postJson('/api/company', [
            'name' => 'Travi',
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'travi@gmail.com',
            'contact' => '08128712781727110'
        ]);

        $user1 = User::factory()->create();
        Sanctum::actingAs(
            $user1
        );

        $response1 = $this->postJson('/api/company', [
            'name' => 'Travi1',
            'profile' => $profile1,
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'contact' => '08128712781727111',
            'email' => 'travi@gmail.com',
        ]);

        $time = time();
        $fileName = $profile->getClientOriginalName();
        $fileName1 = $profile1->getClientOriginalName();

        $response1->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'email' => [
                    'The email has already been taken.'
                ]
            ]
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Travi',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'travi@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '08128712781727110',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertDatabaseMissing('companies', [
            'name' => 'Travi1',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'travi@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '08128712781727111',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertFileExists(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName1);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => 2
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user1->id,
            'company_id' => 3
        ]);

        unlink(public_path() . '/Company/Profile/' . $time . $fileName);
    }

    // Test Create Company With Duplicate Name
    public function test_create_company_with_duplicate_name(){

        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $profile1 = UploadedFile::fake()->image('company_profile134.jpg');

        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );

        $response = $this->postJson('/api/company', [
            'name' => 'KitFood',
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'kitfood@gmail.com',
            'contact' => '08128712781727112'
        ]);

        $user1 = User::factory()->create();
        Sanctum::actingAs(
            $user1
        );
        $response1 = $this->postJson('/api/company', [
            'name' => 'KitFood',
            'profile' => $profile1,
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'contact' => '08128712781727113',
            'email' => 'kitfood1@gmail.com',
        ]);

        $time = time();
        $fileName = $profile->getClientOriginalName();
        $fileName1 = $profile1->getClientOriginalName();

        $response1->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'name' => [
                    'The name has already been taken.'
                ]
            ]
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'KitFood',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'kitfood@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '08128712781727112',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertDatabaseMissing('companies', [
            'name' => 'KitFood',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'kitfood1@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '08128712781727113',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertFileExists(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName1);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => 3
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user1->id,
            'company_id' => 4
        ]);

        unlink(public_path() . '/Company/Profile/' . $time . $fileName);
    }

    public function test_create_company_with_duplicate_contact(){
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $profile1 = UploadedFile::fake()->image('company_profile134.jpg');

        $user = User::factory()->create();
        Sanctum::actingAs(
            $user
        );

        $response = $this->postJson('/api/company', [
            'name' => 'Jalan Jalan Dot COm',
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'jalanjalan@gmail.com',
            'contact' => '081287127817271'
        ]);

        $user1 = User::factory()->create();
        Sanctum::actingAs(
            $user1
        );

        $response1 = $this->postJson('/api/company', [
            'name' => 'Belanja.com',
            'profile' => $profile1,
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'contact' => '081287127817271',
            'email' => 'belanja@gmail.com',
        ]);

        $time = time();
        $fileName = $profile->getClientOriginalName();
        $fileName1 = $profile1->getClientOriginalName();

        $response1->assertStatus(422)->assertExactJson([
            'meta' => [
                'code' => 422,
                'status' => 'error',
                'message' => 'Unprocessable Entity'
            ],
            'data' => null,
            'errors' => [
                'contact' => [
                    'The contact has already been taken.'
                ]
            ]
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Jalan Jalan Dot COm',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'jalanjalan@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '081287127817271',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertDatabaseMissing('companies', [
            'name' => 'Belanja.com',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'belanja@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '081287127817271',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertFileExists(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName1);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => 4
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user1->id,
            'company_id' => 5
        ]);

        unlink(public_path() . '/Company/Profile/' . $time . $fileName);
    }

    // Test Create Company With Unauthenticated User
    public function test_create_company_with_unauthenticated_user(){

        $profile = UploadedFile::fake()->image('company_profile.jpg');

        $response = $this->postJson('/api/company', [
            'name' => 'Perunas',
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'perunas@gmail.com',
            'contact' => '081287127817271'
        ]);

        $time = time();
        $fileName = $profile->getClientOriginalName();

        $response->assertStatus(401)->assertExactJson([
            'meta' => [
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthenticated'
            ],
            'data' => null,
            'errors' => "Unauthenticated."
        ]);

        $this->assertDatabaseMissing('companies', [
            'name' => 'Perunas',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'perunas@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '081287127817271',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'company_id' => 5
        ]);
    }

    // Test Get Company Detail With Correct Company ID
    public function test_get_company_detail_with_correct_company_id(){
        $company = Company::factory()->create([
            'id' => 100,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        Company::factory()->create([
            'id' => 101,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company2.jpg'
        ]);


        $response = $this->get('/api/company/100');

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get Company Detail'
            ],
            'data' => $company->toArray(),
            'errors' => null
        ]);
    }

    public function test_get_company_detail_with_incorrect_company_id(){
        $company = Company::factory()->create([
            'id' => 102,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        Company::factory()->create([
            'id' => 103,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company2.jpg'
        ]);

        $response = $this->get('/api/company/500');

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get Company Detail'
            ],
            'data' => null,
            'errors' => null
        ]);
    }

    public function test_join_company_with_correct_company_and_user_id(){
        $company = Company::factory()->create([
            'id' => 104,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $newCompanyMember = User::factory()->create();

        $oldCompanyMember = User::factory()->create([
            'company_id' => $company->id
        ]);
        Sanctum::actingAs(
            $oldCompanyMember
        );

        $response = $this->post('/api/company/join', [
            'company_id' => $company->id,
            'user_id' => $newCompanyMember->id
        ]);

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Getting Other People Into The Company'
            ],
            'data' => [
                'user name' => $newCompanyMember->name,
                'company name' => $company->name
            ],
            'errors' => null
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $newCompanyMember->id,
            'company_id' => $company->id
        ]);
    }

    public function test_join_company_with_incorrect_company_id_and_correct_user_id(){
        $company = Company::factory()->create([
            'id' => 105,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $newCompanyMember = User::factory()->create();

        $oldCompanyMember = User::factory()->create([
            'company_id' => $company->id
        ]);

        Sanctum::actingAs(
            $oldCompanyMember
        );

        $response = $this->post('/api/company/join', [
            'company_id' => 300,
            'user_id' => $newCompanyMember->id
        ]);

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Company Not Found'
            ],
            'data' => null,
            'errors' => null
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $newCompanyMember->id,
            'company_id' => $company->id
        ]);
    }

    public function test_join_company_with_correct_company_id_and_incorrect_user_id(){
        $company = Company::factory()->create([
            'id' => 106,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $newCompanyMember = User::factory()->create();

        $oldCompanyMember = User::factory()->create([
            'company_id' => $company->id
        ]);

        Sanctum::actingAs(
            $oldCompanyMember
        );

        $response = $this->post('/api/company/join', [
            'company_id' => $company->id,
            'user_id' => 3002
        ]);

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User Not Found'
            ],
            'data' => null,
            'errors' => null
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $newCompanyMember->id,
            'company_id' => $company->id
        ]);
    }

    public function test_join_company_with_unauthorized_user(){
        $company = Company::factory()->create([
            'id' => 107,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $newCompanyMember = User::factory()->create();

        $unauthorizedCompanyMember = User::factory()->create();

        // old company user
        User::factory()->create([
            'company_id' => $company->id
        ]);

        Sanctum::actingAs(
            $unauthorizedCompanyMember
        );

        $response = $this->post('/api/company/join', [
            'company_id' => $company->id,
            'user_id' => $newCompanyMember->id
        ]);

        $response->assertStatus(401)->assertExactJson([
            'meta' => [
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized User'
            ],
            'data' => null,
            'errors' => "Unauthorized."
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $newCompanyMember->id,
            'company_id' => $company->id
        ]);
    }

    public function test_join_company_with_unauthenticated_user(){
        $company = Company::factory()->create([
            'id' => 108,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $newCompanyMember = User::factory()->create();

        $response = $this->post('/api/company/join', [
            'company_id' => $company->id,
            'user_id' => $newCompanyMember->id
        ]);

        $response->assertStatus(401)->assertExactJson([
            'meta' => [
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthenticated'
            ],
            'data' => null,
            'errors' => "Unauthenticated."
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $newCompanyMember->id,
            'company_id' => $company->id
        ]);
    }

    public function test_success_leave_company(){
        $company = Company::factory()->create([
            'id' => 109,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id
        ]);

        Sanctum::actingAs(
            $user
        );

        $response = $this->post('/api/company/leave');

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Leave Company'
            ],
            'data' => null,
            'errors' => null
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => null
        ]);
    }

    public function test_leave_company_with_unauthenticated_user(){

        $response = $this->post('/api/company/leave');

        $response->assertStatus(401)->assertExactJson([
            'meta' => [
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthenticated'
            ],
            'data' => null,
            'errors' => 'Unauthenticated.'
        ]);
    }

    public function test_success_get_company_mambers(){
        $company = Company::factory()->create([
            'id' => 110,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $user = User::factory()->create([
            'company_id' => (string)$company->id,
            'bio' => 'Seorang pelajar yang tangguh',
            'last_education' => 'Sekolah Menangah Atas',
            'profile' => 'gambar.jpg',
            'verify' => null
        ]);

        $user1 = User::factory()->create([
            'company_id' =>(string)$company->id,
            'bio' => 'Seorang pelajar yang tangguh',
            'last_education' => 'Sekolah Menangah Pertama',
            'profile' => 'gambar.jpg',
            'verify' => null
        ]);

        Sanctum::actingAs(
            $user
        );

        $response = $this->get('/api/company/110/members');

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get Company Members'
            ],
            'data' => [
                $user->toArray(),
                $user1->toArray()
            ],
            'errors' => null
        ]);
    }

    public function test_get_company_mambers_with_incorrect_company_id(){
        $company = Company::factory()->create([
            'id' => 111,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $user = User::factory()->create([
            'company_id' => $company->id
        ]);

        Sanctum::actingAs(
            $user
        );

        $response = $this->get('/api/company/9999/members');

        $response->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Company Not Found'
            ],
            'data' => null,
            'errors' => null
        ]);
    }

    public function test_get_company_mambers_with_unauthorized_user(){
        $company = Company::factory()->create([
            'id' => 112,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $company2 = Company::factory()->create([
            'id' => 113,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        User::factory()->create([
            'company_id' => $company->id
        ]);

        $user1 = User::factory()->create([
            'company_id' => $company2->id
        ]);

        Sanctum::actingAs(
            $user1
        );

        $response = $this->get('/api/company/112/members');

        $response->assertStatus(401)->assertExactJson([
            'meta' => [
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized User'
            ],
            'data' => null,
            'errors' => 'Unauthorized.'
        ]);
    }
    
    public function test_get_company_mambers_with_unauthenticated_user(){
        $company = Company::factory()->create([
            'id' => 112,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        $company2 = Company::factory()->create([
            'id' => 113,
            'category' => 'Technology',
            'profile' => '/Company/Profile/company1.jpg'
        ]);

        User::factory()->create([
            'company_id' => $company2->id
        ]);

        $response = $this->get('/api/company/112/members');

        $response->assertStatus(401)->assertExactJson([
            'meta' => [
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthenticated'
            ],
            'data' => null,
            'errors' => 'Unauthenticated.'
        ]);
    }
    
    // public function test_aja(){

    //     Company::factory()->create([
    //         'id' => 1000,
    //         'category' => 'Technology',
    //         'profile' => '/Company/Profile/company1.jpg'
    //     ]);

    //     Company::factory()->create([
    //         'id' => 1001,
    //         'category' => 'Technology',
    //         'profile' => '/Company/Profile/company1.jpg'
    //     ]);

    //     Company::factory()->create([
    //         'id' => 1002,
    //         'category' => 'Technology',
    //         'profile' => '/Company/Profile/company1.jpg'
    //     ]);

    //     $response = $this->get('/api/companies');
    //     $response->dd();
    // }
}
