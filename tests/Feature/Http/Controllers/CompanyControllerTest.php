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
            'contact' => '081287127817271'
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
            'contact' => '081287127817271',
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
            'contact' => '081287127817271',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertDatabaseMissing('companies', [
            'name' => 'Travi1',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'travi@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '081287127817271',
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
            'contact' => '081287127817271'
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
            'contact' => '081287127817271',
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
            'contact' => '081287127817271',
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);

        $this->assertDatabaseMissing('companies', [
            'name' => 'KitFood',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'kitfood1@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'contact' => '081287127817271',
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
            'company_id' => 4
        ]);
    }

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

        $user1 = User::factory()->create([
            'company_id' => $company->id
        ]);
        
        Sanctum::actingAs(
            $user1
        );

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

        $user = User::factory()->create([
            'company_id' => $company->id
        ]);
        
        Sanctum::actingAs(
            $user
        );

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
}
