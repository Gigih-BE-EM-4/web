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
            ]
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
            'company_id' => 1
        ]);
        $this->assertDatabaseCount('companies', 1);
        unlink(public_path() . '/Company/Profile/' . $time . $fileName);
    }

    public function test_create_company_without_name_field(){
        Sanctum::actingAs(
            User::factory()->create()
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
            'company_id' => 2
        ]);
    }

    public function test_create_company_without_address_field(){
        Sanctum::actingAs(
            User::factory()->create()
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Kerja',
            'profile' => $profile,
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'category' => 'Technology',
            'email' => 'sobatkerja3@gmail.com',
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
            'name' => 'Sobat Kerja',
            'category' => 'Technology',
            'email' => 'sobatkerja3@gmail.com',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'company_id' => 2
        ]);
    }

    public function test_create_company_without_category_field(){
        Sanctum::actingAs(
            User::factory()->create()
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Kerja',
            'profile' => $profile,
            'address' => 'Jakarta Selatan',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'email' => 'sobatkerja3@gmail.com',
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
            'name' => 'Sobat Kerja',
            'address' => 'Jakarta Selatan',
            'email' => 'sobatkerja3@gmail.com',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'company_id' => 2
        ]);
    }

    public function test_create_company_without_email_field(){
        Sanctum::actingAs(
            User::factory()->create()
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Kerja',
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
            'name' => 'Sobat Kerja',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'contact' => '081287127817271',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'company_id' => 2
        ]);
    }

    public function test_create_company_without_contact_field(){
        Sanctum::actingAs(
            User::factory()->create()
        );
        $profile = UploadedFile::fake()->image('company_profile.jpg');
        $response = $this->postJson('/api/company', [
            'name' => 'Sobat Kerja',
            'profile' => $profile,
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'bio' => 'Perusahaan terbaik di jakarta raya',
            'email' => 'sobatkerja3@gmail.com',
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
            'name' => 'Sobat Kerja',
            'address' => 'Jakarta Selatan',
            'category' => 'Technology',
            'email' => 'sobatkerja3@gmail.com',
            'profile' => '/Company/Profile/' . $time . $fileName,
            'bio' => 'Perusahaan terbaik di jakarta raya',
        ]);
        $this->assertFileDoesNotExist(public_path() . '/Company/Profile/' . $time . $fileName);
        $this->assertDatabaseMissing('users', [
            'company_id' => 2
        ]);
    }
}
