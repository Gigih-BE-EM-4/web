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
        Sanctum::actingAs(
            User::factory()->create()
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
                'status' => 'created',
                'message' => 'Company has been created'
            ],
            'data' => [
                'name' => 'Sobat Kerja',
                'profile' => public_path() . '/Company/Profile/' . $time . $fileName,
                'bio' => 'Perusahaan terbaik di jakarta raya',
                'address' => 'Jakarta Selatan',
                'category' => 'Technology',
                'email' => 'sobatkerja1@gmail.com',
                'contact' => '081287127817271'
            ]
        ]);
    }

    // public function test_create_company_without_name_field(){
    //     Sanctum::actingAs(
    //         User::factory()->create()
    //     );
    //     $profile = UploadedFile::fake()->image('company_profile.jpg');
    //     $response = $this->postJson('/api/company', [
    //         'profile' => $profile,
    //         'bio' => 'Perusahaan terbaik di jakarta raya',
    //         'address' => 'Jakarta Selatan',
    //         'category' => 'Technology',
    //         'email' => 'sobatkerja2@gmail.com',
    //         'contact' => '081287127817271'
    //     ]);

    //     $response->assertJsonValidationErrors([
    //         'name' => 'The name field is required.'
    //     ]);
    // }

    // public function test_create_company_without_address_field(){
    //     Sanctum::actingAs(
    //         User::factory()->create()
    //     );
    //     $profile = UploadedFile::fake()->image('company_profile.jpg');
    //     $response = $this->postJson('/api/company', [
    //         'name' => 'Sobat Kerja',
    //         'profile' => $profile,
    //         'bio' => 'Perusahaan terbaik di jakarta raya',
    //         'category' => 'Technology',
    //         'email' => 'sobatkerja3@gmail.com',
    //         'contact' => '081287127817271'
    //     ]);

    //     $response->assertJsonValidationErrors([
    //         'address' => 'The address field is required.'
    //     ]);
    // }

    // public function test_create_company_without_category_field(){
    //     Sanctum::actingAs(
    //         User::factory()->create()
    //     );
    //     $profile = UploadedFile::fake()->image('company_profile.jpg');
    //     $response = $this->postJson('/api/company', [
    //         'name' => 'Sobat Kerja',
    //         'profile' => $profile,
    //         'address' => 'Jakarta Selatan',
    //         'bio' => 'Perusahaan terbaik di jakarta raya',
    //         'email' => 'sobatkerja4@gmail.com',
    //         'contact' => '081287127817271'
    //     ]);

    //     $response->assertJsonValidationErrors([
    //         'category' => 'The category field is required.'
    //     ]);
    // }

    // public function test_create_company_without_email_field(){
    //     Sanctum::actingAs(
    //         User::factory()->create()
    //     );
    //     $profile = UploadedFile::fake()->image('company_profile.jpg');
    //     $response = $this->postJson('/api/company', [
    //         'name' => 'Sobat Kerja',
    //         'profile' => $profile,
    //         'address' => 'Jakarta Selatan',
    //         'bio' => 'Perusahaan terbaik di jakarta raya',
    //         'category' => 'Technology',
    //         'contact' => '081287127817271'
    //     ]);

    //     $response->assertJsonValidationErrors([
    //         'email' => 'The email field is required.'
    //     ]);
    // }

    // public function test_create_company_without_contact_field(){
    //     Sanctum::actingAs(
    //         User::factory()->create()
    //     );
    //     $profile = UploadedFile::fake()->image('company_profile.jpg');
    //     $response = $this->postJson('/api/company', [
    //         'name' => 'Sobat Kerja',
    //         'profile' => $profile,
    //         'address' => 'Jakarta Selatan',
    //         'bio' => 'Perusahaan terbaik di jakarta raya',
    //         'email' => 'sobatkerja5@gmail.com',
    //         'category' => 'Technology',
    //     ]);

    //     $response->assertJsonValidationErrors([
    //         'contact' => 'The contact field is required.'
    //     ]);
    // }
}
