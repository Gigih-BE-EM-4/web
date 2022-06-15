<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

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

    $project = Project::with(['company']);

    return ResponseFormatter::success($project->paginate($limit), "Data Successfully Retrieved");
  }
}
