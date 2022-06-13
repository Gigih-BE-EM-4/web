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
            'email' => 'sobatkerja@gmail.com',
            'contact' => '081287127817271'
        ]);

        $response->assertCreated()->assertExactJson([
            'meta' => [
                'code' => 201,
                'status' => 'created',
                'message' => 'Company has been created'
            ],
            'data' => $response
        ]);
    }
}
