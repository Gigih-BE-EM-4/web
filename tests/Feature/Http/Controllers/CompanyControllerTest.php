<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

use function PHPSTORM_META\type;

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

    public function test_get_all_companies_with_search_parameter_matches_company_name_and_without_page_parameter(){
        $companyMatch1 = Company::factory()->create([
            'name' => 'z123lo'
        ]);
        $companyMatch2 = Company::factory()->create([
            'name' => 'z123li'
        ]);
        Company::factory()->create([
            'name' => 'Barudak Bandung'
        ]);

        $response1 = $this->get('/api/companies?search=z123');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch1->toArray(),
                    $companyMatch2->toArray(),
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 2
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=z123li');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch2->toArray(),
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 1
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_address_and_without_page_parameter(){
        $companyMatch1 = Company::factory()->create([
            'address' => 'z123loa'
        ]);
        $companyMatch2 = Company::factory()->create([
            'address' => 'z123lia'
        ]);
        Company::factory()->create([
            'address' => 'Penarungan'
        ]);

        $response1 = $this->get('/api/companies?search=z123l');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch1->toArray(),
                    $companyMatch2->toArray(),
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 2
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=z123loa');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch1->toArray(),
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 1
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_category_and_without_page_parameter(){
        $companyMatch1 = Company::factory()->create([
            'category' => 'z0999'
        ]);
        $companyMatch2 = Company::factory()->create([
            'category' => 'z0999'
        ]);

        $companyMatch3 = Company::factory()->create([
            'category' => 'z0888'
        ]);

        Company::factory()->create([
            'category' => 'Tech'
        ]);

        $response1 = $this->get('/api/companies?search=z0');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch1->toArray(),
                    $companyMatch2->toArray(),
                    $companyMatch3->toArray()
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 3
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=z0999');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch1->toArray(),
                    $companyMatch2->toArray(),
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 2
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_name_and_address_and_without_page_parameter(){
        $companies = Company::factory()->count(3)->create();
        $companies[0]['address'] = 'Penarungan';
        $companies[1]['address'] = 'Penarukan';
        $companies[2]['address'] = 'Subang';

        $companies[0]['name'] = 'Penarukan';
        $companies[1]['name'] = 'Sobangan';
        $companies[2]['name'] = 'SubangKerta';

        $companies[0]['category'] = 'Technology';
        $companies[1]['category'] = 'Technology';
        $companies[2]['category'] = 'Technology';

        $companies[0]->save();
        $companies[1]->save();
        $companies[2]->save();

        $companyMatch1 = $companies[0];
        $companyMatch2 = $companies[1];

        $response1 = $this->get('/api/companies?search=ngan');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch1->toArray(),$companyMatch2->toArray()
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 2
            ],
            'errors' => null
        ]);
        $response1 = $this->get('/api/companies?search=Penarukan');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companyMatch1->toArray(),$companyMatch2->toArray()
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 2
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_name_and_category_and_without_page_parameter(){
        $companies = Company::factory()->count(3)->create();
        $companies[0]['address'] = 'Penarungan';
        $companies[1]['address'] = 'Penarukan';
        $companies[2]['address'] = 'Subang';

        $companies[0]['name'] = 'Technology';
        $companies[1]['name'] = 'TechPark';
        $companies[2]['name'] = 'SubangDumayu';

        $companies[0]['category'] = 'Retail';
        $companies[1]['category'] = 'Retail';
        $companies[2]['category'] = 'Technology';

        $companies[0]->save();
        $companies[1]->save();
        $companies[2]->save();

        $response1 = $this->get('/api/companies?search=Tech');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companies[0]->toArray(), $companies[1]->toArray(), $companies[2]->toArray()
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 3
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=Technology');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companies[0]->toArray(), $companies[2]->toArray()
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 2
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_name_category_address_and_without_page_parameter(){
        $companies = Company::factory()->count(3)->create();
        $companies[0]['address'] = 'TechBandung';
        $companies[1]['address'] = 'TechPark';
        $companies[2]['address'] = 'Technology';

        $companies[0]['name'] = 'Technology';
        $companies[1]['name'] = 'TechPark';
        $companies[2]['name'] = 'Alibis';

        $companies[0]['category'] = 'Retail';
        $companies[1]['category'] = 'Tech';
        $companies[2]['category'] = 'Technology';

        $companies[0]->save();
        $companies[1]->save();
        $companies[2]->save();

        $response1 = $this->get('/api/companies?search=Tech');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companies[0]->toArray(), $companies[1]->toArray(), $companies[2]->toArray()
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 3
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=Technology');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => [
                    $companies[0]->toArray(), $companies[2]->toArray()
                ],
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 2
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_name_and_with_page_parameter(){
        $companiesMatch1 = Company::factory()->count(20)->make();
        $companiesNotMatch1 = Company::factory()->count(20)->make();
        $companiesMatch2 = Company::factory()->count(20)->make();
        $companiesNotMatch2 = Company::factory()->count(20)->make();

        $namePostFix = 0;
        for ($i=0; $i < count($companiesMatch1); $i++) { 
            $companiesMatch1[$i]['name'] = 'AdiRasa'.$namePostFix;
            $companiesMatch1[$i]['address'] = 'Penarungan';
            $companiesMatch1[$i]['category'] = 'Technology';

            $companiesNotMatch1[$i]['name'] = 'BaruAja'.$namePostFix;
            $companiesNotMatch1[$i]['address'] = 'Penarungan';
            $companiesNotMatch1[$i]['category'] = 'Technology';

            $companiesMatch1[$i]->save();
            $companiesNotMatch1[$i]->save();
            $namePostFix++;
        }
        for ($i=0; $i < count($companiesMatch2); $i++) { 
            $companiesMatch2[$i]['name'] = 'AdiRasa'.$namePostFix;
            $companiesMatch2[$i]['address'] = 'Penarungan';
            $companiesMatch2[$i]['category'] = 'Technology';

            $companiesNotMatch2[$i]['name'] = 'BaruAaja'.$namePostFix;
            $companiesNotMatch2[$i]['address'] = 'Penarungan';
            $companiesNotMatch2[$i]['category'] = 'Technology';

            $companiesMatch2[$i]->save();
            $companiesNotMatch2[$i]->save();
            $namePostFix++;
        }

        $response1 = $this->get('/api/companies?search=AdiRasa&page=1');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => $companiesMatch1->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=AdiRasa&page=2');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 2,
                'data' => $companiesMatch2->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 21,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=1',
                'to' => 40
            ],
            'errors' => null
        ]);
    }
    
    public function test_get_all_companies_with_search_parameter_matches_company_address_and_with_page_parameter(){
        $companiesMatch1 = Company::factory()->count(20)->make();
        $companiesNotMatch1 = Company::factory()->count(20)->make();
        $companiesMatch2 = Company::factory()->count(20)->make();
        $companiesNotMatch2 = Company::factory()->count(20)->make();

        $namePostFix = 0;
        for ($i=0; $i < count($companiesMatch1); $i++) { 
            $companiesMatch1[$i]['name'] = 'Barbara'.$namePostFix;
            $companiesMatch1[$i]['address'] = 'Badung';
            $companiesMatch1[$i]['category'] = 'Technology';

            $companiesNotMatch1[$i]['name'] = 'Carcara'.$namePostFix;
            $companiesNotMatch1[$i]['address'] = 'Ciroyom';
            $companiesNotMatch1[$i]['category'] = 'Technology';

            $companiesMatch1[$i]->save();
            $companiesNotMatch1[$i]->save();
            $namePostFix++;
        }
        for ($i=0; $i < count($companiesMatch2); $i++) { 
            $companiesMatch2[$i]['name'] = 'Barbara'.$namePostFix;
            $companiesMatch2[$i]['address'] = 'Badung';
            $companiesMatch2[$i]['category'] = 'Technology';

            $companiesNotMatch2[$i]['name'] = 'Carcara'.$namePostFix;
            $companiesNotMatch2[$i]['address'] = 'Ciroyom';
            $companiesNotMatch2[$i]['category'] = 'Technology';

            $companiesMatch2[$i]->save();
            $companiesNotMatch2[$i]->save();
            $namePostFix++;
        }

        $response1 = $this->get('/api/companies?search=Badung&page=1');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => $companiesMatch1->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=Badung&page=2');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 2,
                'data' => $companiesMatch2->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 21,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=1',
                'to' => 40
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_category_and_with_page_parameter(){
        $companiesMatch1 = Company::factory()->count(20)->make();
        $companiesNotMatch1 = Company::factory()->count(20)->make();
        $companiesMatch2 = Company::factory()->count(20)->make();
        $companiesNotMatch2 = Company::factory()->count(20)->make();

        $namePostFix = 0;
        for ($i=0; $i < count($companiesMatch1); $i++) { 
            $companiesMatch1[$i]['name'] = 'Pusu'.$namePostFix;
            $companiesMatch1[$i]['address'] = 'Tabanan';
            $companiesMatch1[$i]['category'] = 'Science';

            $companiesNotMatch1[$i]['name'] = 'Dusu'.$namePostFix;
            $companiesNotMatch1[$i]['address'] = 'Denpasar';
            $companiesNotMatch1[$i]['category'] = 'Teknik';

            $companiesMatch1[$i]->save();
            $companiesNotMatch1[$i]->save();
            $namePostFix++;
        }
        for ($i=0; $i < count($companiesMatch2); $i++) { 
            $companiesMatch2[$i]['name'] = 'Pusu'.$namePostFix;
            $companiesMatch2[$i]['address'] = 'Tabanan';
            $companiesMatch2[$i]['category'] = 'Science';

            $companiesNotMatch2[$i]['name'] = 'Dusu'.$namePostFix;
            $companiesNotMatch2[$i]['address'] = 'Cisarua';
            $companiesNotMatch2[$i]['category'] = 'Teknik';

            $companiesMatch2[$i]->save();
            $companiesNotMatch2[$i]->save();
            $namePostFix++;
        }

        $response1 = $this->get('/api/companies?search=science&page=1');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => $companiesMatch1->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=science&page=2');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 2,
                'data' => $companiesMatch2->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 21,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=1',
                'to' => 40
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_name_address_and_with_page_parameter(){
        $companiesMatch1 = Company::factory()->count(20)->make();
        $companiesNotMatch1 = Company::factory()->count(20)->make();
        $companiesMatch2 = Company::factory()->count(15)->make();
        $companiesNotMatch2 = Company::factory()->count(20)->make();

        $namePostFix = 0;
        for ($i=0; $i < count($companiesMatch1); $i++) { 
            $companiesMatch1[$i]['name'] = 'BangliTech'.$namePostFix;
            $companiesMatch1[$i]['address'] = 'Bangli';
            $companiesMatch1[$i]['category'] = 'Learning';

            $companiesNotMatch1[$i]['name'] = 'Wusu'.$namePostFix;
            $companiesNotMatch1[$i]['address'] = 'DelodPeken';
            $companiesNotMatch1[$i]['category'] = 'Learning';

            $companiesMatch1[$i]->save();
            $companiesNotMatch1[$i]->save();
            $namePostFix++;
        }
        for ($i=0; $i < count($companiesMatch2); $i++) { 
            $companiesMatch2[$i]['name'] = 'Dusu'.$namePostFix;
            $companiesMatch2[$i]['address'] = 'Bangli';
            $companiesMatch2[$i]['category'] = 'Learning';

            $companiesNotMatch2[$i]['name'] = 'Wusu'.$namePostFix;
            $companiesNotMatch2[$i]['address'] = 'DelodPeken';
            $companiesNotMatch2[$i]['category'] = 'Learning';

            $companiesMatch2[$i]->save();
            $companiesNotMatch2[$i]->save();
            $namePostFix++;
        }

        $response1 = $this->get('/api/companies?search=bangli&page=1');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => $companiesMatch1->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=Bangli&page=2');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 2,
                'data' => $companiesMatch2->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 21,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=1',
                'to' => 35
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_name_category_and_with_page_parameter(){
        $companiesNotMatch1 = Company::factory()->count(20)->make();
        $companiesMatch1 = Company::factory()->count(20)->make();
        $companiesMatch2 = Company::factory()->count(20)->make();
        $companiesNotMatch2 = Company::factory()->count(20)->make();

        $namePostFix = 0;
        for ($i=0; $i < 20; $i++) { 
            $companiesNotMatch1[$i]['name'] = 'lulu'.$namePostFix;
            $companiesNotMatch1[$i]['address'] = 'DajanBambangan';
            $companiesNotMatch1[$i]['category'] = 'Pembelajaran';

            $companiesMatch1[$i]['name'] = 'DeepCom'.$namePostFix;
            $companiesMatch1[$i]['address'] = 'Gianyar';
            $companiesMatch1[$i]['category'] = 'Pembelajaran';

            $companiesNotMatch1[$i]->save();
            $companiesMatch1[$i]->save();
            $namePostFix++;
        }
        for ($i=0; $i < 20; $i++) { 
            $companiesMatch2[$i]['name'] = 'yuyu'.$namePostFix;
            $companiesMatch2[$i]['address'] = 'Balangan';
            $companiesMatch2[$i]['category'] = 'DeepReasoning';

            $companiesNotMatch2[$i]['name'] = 'bubu'.$namePostFix;
            $companiesNotMatch2[$i]['address'] = 'Balangan';
            $companiesNotMatch2[$i]['category'] = 'Pembelajaran';

            $companiesMatch2[$i]->save();
            $companiesNotMatch2[$i]->save();
            $namePostFix++;
        }

        $response1 = $this->get('/api/companies?search=deep&page=1');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => $companiesMatch1->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=Deep&page=2');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 2,
                'data' => $companiesMatch2->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 21,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=1',
                'to' => 40
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_with_search_parameter_matches_company_name_category_address_and_with_page_parameter(){
        $companiesNotMatch1 = Company::factory()->count(20)->make();
        $companiesMatch1 = Company::factory()->count(20)->make();
        $companiesMatch2 = Company::factory()->count(20)->make();
        $companiesMatch3 = Company::factory()->count(10)->make();
        $companiesNotMatch2 = Company::factory()->count(20)->make();

        $namePostFix = 0;
        for ($i=0; $i < 20; $i++) { 
            $companiesNotMatch1[$i]['name'] = 'Bufalo'.$namePostFix;
            $companiesNotMatch1[$i]['address'] = 'Mengwi';
            $companiesNotMatch1[$i]['category'] = 'Kelautan';

            $companiesMatch1[$i]['name'] = 'YoloYokato'.$namePostFix;
            $companiesMatch1[$i]['address'] = 'Mengwi';
            $companiesMatch1[$i]['category'] = 'Ketenagakerjaan';

            $companiesNotMatch1[$i]->save();
            $companiesMatch1[$i]->save();
            $namePostFix++;
        }
        for ($i=0; $i < 20; $i++) { 
            $companiesMatch2[$i]['name'] = 'dudu'.$namePostFix;
            $companiesMatch2[$i]['address'] = 'Jalan yolo';
            $companiesMatch2[$i]['category'] = 'Ketenagakerjaan';

            $companiesNotMatch2[$i]['name'] = 'yuyu'.$namePostFix;
            $companiesNotMatch2[$i]['address'] = 'Mengwi';
            $companiesNotMatch2[$i]['category'] = 'Kelautan';

            $companiesMatch2[$i]->save();
            $companiesNotMatch2[$i]->save();
            $namePostFix++;
        }

        for ($i=0; $i < 10; $i++) { 
            $companiesMatch3[$i]['name'] = 'poporo'.$namePostFix;
            $companiesMatch3[$i]['address'] = 'Jalan deli';
            $companiesMatch3[$i]['category'] = 'Category Yolo';
            $companiesMatch3[$i]->save();
            $namePostFix++;
        }

        $response1 = $this->get('/api/companies?search=YoLo&page=1');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => $companiesMatch1->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=yoLo&page=2');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 2,
                'data' => $companiesMatch2->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 21,
                'next_page_url' => 'http://localhost/api/companies?page=3',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=1',
                'to' => 40
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?search=YOLO&page=3');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 3,
                'data' => $companiesMatch3->toArray(),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 41,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=2',
                'to' => 50
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_without_search_parameter_and_with_page_parameter(){
        $companies = Company::factory()->count(55)->make();

        for ($i=0; $i < count($companies); $i++) { 
            $companies[$i]['name'] = 'Test12'.$i;
            $companies[$i]->save();
        }

        $response1 = $this->get('/api/companies?page=1');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => array_slice($companies->toArray(), 0, 20),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?page=2');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 2,
                'data' => array_slice($companies->toArray(), 20, 20),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 21,
                'next_page_url' => 'http://localhost/api/companies?page=3',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=1',
                'to' => 40
            ],
            'errors' => null
        ]);

        $response1 = $this->get('/api/companies?page=3');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 3,
                'data' => array_slice($companies->toArray(), 40, 15),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 41,
                'next_page_url' => null,
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => 'http://localhost/api/companies?page=2',
                'to' => 55
            ],
            'errors' => null
        ]);
    }

    public function test_get_all_companies_without_search_parameter_and_without_page_parameter(){
        $companies = Company::factory()->count(25)->make();

        for ($i=0; $i < count($companies); $i++) { 
            $companies[$i]['name'] = 'Kunoro'.$i;
            $companies[$i]->save();
        }

        $response1 = $this->get('/api/companies');
        $response1->assertOk()->assertExactJson([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Success Get All Companies'
            ],
            'data' => [
                'current_page' => 1,
                'data' => array_slice($companies->toArray(), 0, 20),
                'first_page_url' => 'http://localhost/api/companies?page=1',
                'from' => 1,
                'next_page_url' => 'http://localhost/api/companies?page=2',
                'path' => 'http://localhost/api/companies',
                'per_page' => 20,
                'prev_page_url' => null,
                'to' => 20
            ],
            'errors' => null
        ]);
    }

        // $response1 = $this->get('/api/companies?search=z123li');
        // $response1->assertOk()->assertExactJson([
        //     'meta' => [
        //         'code' => 200,
        //         'status' => 'success',
        //         'message' => 'Success Get All Companies'
        //     ],
        //     'data' => [
        //         'current_page' => 1,
        //         'data' => [
        //         ],
        //         'first_page_url' => 'http://localhost/api/companies?page=1',
        //         'from' => 1,
        //         'next_page_url' => null,
        //         'path' => 'http://localhost/api/companies',
        //         'per_page' => 20,
        //         'prev_page_url' => null,
        //         'to' => 1
        //     ],
        //     'errors' => null
        // ]);
    // }//

    
    // public function test_aja(){

    //     Company::factory()->create([
    //         'id' => 1000,
    //         'category' => 'Technology',
    //         'profile' => '/Company/Profile/company1.jpg'
    //     ]);

    //     Company::factory()->create([
    //         'name' => 'Bravi',
    //         'id' => 1001,
    //         'category' => 'Technology',
    //         'profile' => '/Company/Profile/company1.jpg'
    //     ]);

    //     Company::factory()->create([
    //         'name' => 'Bravo',
    //         'id' => 1002,
    //         'category' => 'Technology',
    //         'profile' => '/Company/Profile/company1.jpg'
    //     ]);

    //     $response = $this->get('/api/companies?search=bra&page=1');
    //     $response->dd();
    // }

    // public function testimoni(){
    //     $companies = Company::factory()->count(3)->create();
    //     $response = $this->get('/api/companies?page=1');
    //     $response->dd();
    // }
}
