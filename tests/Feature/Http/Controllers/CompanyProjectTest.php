<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectRole;
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
  public function test_get_project()
  {
    $user = User::factory()->create();
    Sanctum::actingAs(
      $user
    );
    $response = $this->get('api/user/company/projects');
    $response->assertStatus(201);
  }
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
    $response = $this->postJson("api/company/project", $project);
    $response->assertStatus(201);
  }
  public function test_update_project()
  {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    Sanctum::actingAs(
      $user
    );
    $id = Project::where('company_id', $user->company_id)->first()->id;
    $project = [
      "name" => "Project 1",
      "description" => "Lorem Ipsum"
    ];
    $response = $this->postJson("api/company/project/" . $id, $project);
    $response->assertStatus(201);
  }

  public function test_add_project_role()
  {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    Sanctum::actingAs(
      $user->first()
    );
    $id = Project::where('company_id', $user->company_id)->first()->id;
    $projectRole = [
      "name" => "Backend",
      "quota" => 2,
      "description" => "Lorem Ipsum"
    ];
    $response = $this->postJson("api/company/project/" . $id . "/role", $projectRole);
    $response->assertStatus(201);
  }

  public function test_update_project_role()
  {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $projectRole = ProjectRole::factory()->create();

    Sanctum::actingAs(
      $user->first()
    );
    $project_id = Project::where('company_id', $user->company_id)->first()->id;
    $id = ProjectRole::where('project_id', $project_id)->first()->id;
    $projectRole = [
      "name" => "Backend",
      "quota" => 2,
      "description" => "Lorem Ipsum"
    ];
    $response = $this->postJson("api/company/project/" . $project_id . "/role/" . $id, $projectRole);
    $response->assertStatus(201);
  }

  public function test_add_project_member()
  {
    $user = User::factory()->create();
    $project = Project::factory()->create();
    $projectRole = ProjectRole::factory()->create();

    Sanctum::actingAs(
      $user->first()
    );
    $project_id = Project::where('company_id', $user->company_id)->first()->id;
    $project_role_id = ProjectRole::where('project_id', $project_id)->first()->id;

    $projectMember = [
      "user_id" => $user->id,
    ];
    $response = $this->postJson("api/company/project/" . $project_id . "/" . $project_role_id . "/project-member/add", $projectMember);
    $response->assertStatus(201);
  }

  public function test_get_project_member()
  {
    $user = User::factory()->create();
    Project::factory()->create();
    Sanctum::actingAs(
      $user
    );
    $project_id = Project::where('company_id', $user->company_id)->first()->id;

    $response = $this->get('api/company/project/' . $project_id . '/project-member');
    $response->assertStatus(201);
  }

  public function test_remove_project_member()
  {
    $user = User::factory()->create();
    Project::factory()->create();
    ProjectRole::factory()->create();
    ProjectMember::factory()->create();
    Sanctum::actingAs(
      $user
    );

    $project_id = Project::where('company_id', $user->company_id)->first()->id;
    $role_id = ProjectRole::where('project_id', $project_id)->first()->id;
    $id = ProjectMember::where('project_role_id', $role_id)->first()->id;

    $response = $this->delete("/company/project/" . $project_id . "/role/" . $role_id . "/remove/" . $id);
    $response->assertStatus(201);
  }

  public function test_get_all_applicants()
  {
    $user = User::factory()->create();
    Project::factory()->create();
    ProjectRole::factory()->create();
    Sanctum::actingAs(
      $user
    );

    $project_id = Project::where('company_id', $user->company_id)->first()->id;
    $role_id = ProjectRole::where('project_id', $project_id)->first()->id;

    $response = $this->get('company/project/' . $project_id . '/role/' . $role_id . '/applicants');
    $response->assertStatus(201);
  }

  public function test_finish_project()
  {
    $user = User::factory()->create();
    Project::factory()->count(10)->create();
    Sanctum::actingAs(
      $user
    );

    $project_id = Project::where('company_id', $user->company_id)->first()->id;

    $response = $this->postJson("api/company/project/" . $project_id . "/finish");
    $response->assertStatus(201);
  }

  public function test_send_certificate()
  {
    $user = User::factory()->create();
    Project::factory()->count(10)->create();
    ProjectMember::factory()->count(20)->create();
    Sanctum::actingAs(
      $user
    );

    $project_id = Project::where('company_id', $user->company_id)->first()->id;
    $project_member_id = ProjectMember::where('project_id', $project_id)->first()->id;

    $certificate = UploadedFile::fake()->create("certificate", 1024, "pdf");

    $data = [
      "certificate" => $certificate
    ];

    $response = $this->postJson("/company/project/" . $project_id . "/project-member/" . $project_member_id . "/send-certificate", $data);
    $response->assertStatus(201);
  }
}
