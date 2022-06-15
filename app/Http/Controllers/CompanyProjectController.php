<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class CompanyProjectController extends Controller
{
  public function getAllProjects(Request $request)
  {
    $id = $request->input('id');
    $limit = $request->input('limit', 5);
    $active = $request->input('active');


    if ($id) {
      $project = Project::find($id);

      if ($project) {
        return ResponseFormatter::success($project, 'Project found');
      } else {
        return ResponseFormatter::error(null, 'Project not found', 404, "{$id} not found");
      }
    }

    $project = Project::with(['company'])->latest();

    return ResponseFormatter::success($project->paginate($limit), "Data Successfully Retrieved");
  }

  public function createProject(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'description' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error(null, 'Validation Error', 400, $validator->errors());
    }
    $validatedData = $validator->validated();

    if ($request->hasFile('image')) {
      $file = $request->file('image');
      $file->move(public_path('images/project'), $file->getClientOriginalName());
      $validatedData['images'] =  public_path("images\project\\" . $file->getClientOriginalName());
    } else {
      $validatedData['images'] =  public_path("images\project\\" . "default.png");
    }

    $validatedData["company_id"] = 0;

    $project = Project::create($validatedData);

    return ResponseFormatter::success(Project::find($project->id), "Project has been created");
  }

  public function getAllApplicants(Request $request)
  {
  }
}
