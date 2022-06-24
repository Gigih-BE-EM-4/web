<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Project;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyProjectTest extends TestCase
{
  use RefreshDatabase;
  /**
   * A basic unit test example.
   *
   * @return void
   */
  public function test_create_project_with_valid_data()
  {
    $user = User::factory()->create();
    Sanctum::actingAs(
      $user
    );
    $images = "a";
    $project = [
      "name" => "Project 1",
      "images" => UploadedFile::fake()->image("Image.png"),
      "description" => "Lorem Ipsum"
    ];
    $response = $this->postJson("/company/project", $project);
    $response->assertStatus(201);
  }
}
