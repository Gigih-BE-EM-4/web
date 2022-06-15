<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
      $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('images/project'), $fileName);
      $validatedData['images'] =  "\images\project\\" . $fileName;
    } else {
      $validatedData['images'] =  "\images\project\\" . "default.png";
    }

    $validatedData["company_id"] = 0;

    $project = Project::create($validatedData);

    return ResponseFormatter::success(Project::find($project->id), "Project has been created");
  }

  public function updateProject(Request $request, $id)
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'description' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error(null, 'Validation Error', 400, $validator->errors());
    }

    $validatedData = $validator->validated();

    $project = Project::find($id);

    if ($project) {
      if ($request->hasFile('image')) {
        File::exists(public_path() . $project->images) && $project->images != '/images/project/default.png' ? File::delete(public_path() . $project->images) : '';
        $file = $request->file('image');
        $fileName = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/project'), $fileName);
        $validatedData['images'] =  "\images\project\\" . $fileName;
      } else {
        $validatedData['images'] =  "\images\project\\" . "default.png";
      }

      $project->update($validatedData);

      return ResponseFormatter::success(Project::find($project->id), "Project has been updated");
    } else {
      return ResponseFormatter::error(null, "Project not found", 404, "Project {$id} not found");
    }
  }

  public function getAllApplicants(Request $request)
  {
  }
}
