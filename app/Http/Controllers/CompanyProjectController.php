<?php

namespace App\Http\Controllers;

use App\Models\Apply;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectRole;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\ValidatedData;

class CompanyProjectController extends Controller
{
  public function getAllProjects(Request $request)
  {
    $id = $request->input('id');
    $limit = $request->input('limit', 5);

    if ($id) {
      $project = Project::find($id);

      if ($project) {
        $data["project"] = $project;
        $data["project"]["roles"] = $project->projectRoles;
        return ResponseFormatter::success($data, 'Project found');
      } else {
        return ResponseFormatter::error(null, 'Project not found', 404, "{$id} not found");
      }
    }

    $project = Project::where('active', 1)
      ->where('isfinished', 0)
      ->paginate($limit);
    $role = ProjectRole::all();

    if ($project) {
      foreach ($project as $data) {
        $data->project_role =
          $role->filter(function ($value, $key) use ($data) {
            if ($value['project_id'] == $data->id) {
              return $value;
            }
          })->values();
      }


      return ResponseFormatter::success($project, "Data Successfully Retrieved");
    } else {
      return ResponseFormatter::error(null, "Data not found", 400, "Data not found");
    }
  }

  public function getAllCompanyProjects(Request $request)
  {
    $id = $request->input('id');
    $limit = $request->input('limit', 5);
    $active = $request->input('active');


    if ($id) {
      $project = Project::find($id);

      if ($project) {
        $data["project"] = $project;
        $data["project"]["roles"] = $project->projectRoles;
        return ResponseFormatter::success($data, 'Project found');
      } else {
        return ResponseFormatter::error(null, 'Project not found', 404, "{$id} not found");
      }
    }

    $project = Project::where('company_id', Auth::user()->company_id)->paginate($limit);
    $role = ProjectRole::all();


    if ($project) {
      foreach ($project as $data) {
        $data->project_role =
          $role->filter(function ($value, $key) use ($data) {
            if ($value['project_id'] == $data->id) {
              return $value;
            }
          })->values();
      }


      return ResponseFormatter::success($project, "Data Successfully Retrieved");
    } else {
      return ResponseFormatter::error(null, "Data not found", 400, "Data not found");
    }
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
      $fileName = time() . str_replace(" ", "_", $file->getClientOriginalName());
      $file->move(public_path('images/project'), $fileName);
      $validatedData['images'] =  "\images\project\\" . $fileName;
    } else {
      $validatedData['images'] =  "\images\project\\" . "default.png";
    }

    $validatedData["company_id"] = Auth::user()->company_id;

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
      if (Auth::user()->company_id != $project->company_id) {
        return ResponseFormatter::error(null, 'You are not in this company', 401, "You are not in this company");
      }
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

  public function addProjectRole(Request $request, $id)
  {
    // return $request['extra-question'];
    $project = Project::find($id);

    if (Auth::user()->company_id != $project->company_id) {
      return ResponseFormatter::error(null, 'You are not in this company', 401, "You are not in this company");
    }

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'quota' => 'required|integer',
      'description' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error(null, 'Validation Error', 400, $validator->errors());
    }

    $data = $request->all();

    $data['project_id'] = $id;

    // return $data;

    $projectRole = ProjectRole::create($data);
    return ResponseFormatter::success(ProjectRole::find($projectRole->id), "Project Role has been created");
  }

  public function updateProjectRole(Request $request, $project_id, $role_id)
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'quota' => 'required|integer',
      'description' => 'required',
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error(null, 'Validation Error', 400, $validator->errors());
    }

    $data = $request->all();

    // return $data;

    $projectRole = ProjectRole::where('id', $role_id)->where('project_id', $project_id)->first();

    // return $projectRole;

    if ($projectRole) {
      if (Auth::user()->company_id != Project::find($project_id)->company_id) {
        return ResponseFormatter::error(null, 'You are not in this company', 401, "You are not in this company");
      }
      $projectRole->update($data);
      return ResponseFormatter::success(ProjectRole::find($projectRole->id), "Project Role has been updated");
    } else {
      return ResponseFormatter::error(null, "Project Role not found", 404, "Project Role {$data['id']} not found");
    }
  }

  public function addProjectMember(Request $request, $project_id, $project_role_id)
  {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|integer',
      'certificate' => 'nullable|file|mimes:pdf'
    ]);

    if ($validator->fails()) {
      return ResponseFormatter::error(null, 'Validation Error', 400, $validator->errors());
    }

    $data = [
      'project_id' => $project_id,
      'user_id' => $request->input('user_id'),
      'project_role_id' => $project_role_id,
      'certificate' => $request->certificate
    ];

    $projectMember = ProjectMember::create($data)->id;

    return ResponseFormatter::success(ProjectMember::find($projectMember), "Project Member has been created");
  }

  public function getProjectMember(Request $request, $project_id)
  {
    $role = $request->input('role');

    $projectMember = ProjectMember::with(['projectRole'])->where('project_id', $project_id);

    if ($role) {
      $projectMember->where('project_role_id', $role);
    }

    if ($projectMember->get()) {
      return ResponseFormatter::success($projectMember->get(), 'Data Retrieved');
    } else {
      return ResponseFormatter::error(null, 'Data not found', 404, 'Data not found');
    }
  }

  public function removeProjectMember($project_id, $role_id, $id)
  {


    if (Auth::user()->company_id == Project::find($project_id)->company_id) {
      if (ProjectMember::destroy($id)) {
        return ResponseFormatter::success(null, "Project Member has been removed");
      } else {
        return ResponseFormatter::error(null, "Project Member not found", 404, "Project Member {$id} not found");
      }
    } else {
      return ResponseFormatter::error(null, 'You are not in this company', 401, "You are not in this company");
    }
  }

  private function UploadCVValidator(Request $request)
  {
    return Validator::make($request->all(), [
      'cv' => 'mimes:pdf|max:2048',
      'project_role_id' => 'required',
      'extra_answer' => 'array',
    ]);
  }
  private function uploadCV($request)
  {
    if ($file = $request->hasFile('cv')) {
      $file = $request->file('cv');
      $fileName = time() . $file->getClientOriginalName();
      $destinationPath = public_path() . '/user/cv/' . Auth::user()->id . "/";
      $file->move($destinationPath, $fileName);
      return "/User/cv/" . Auth::user()->id . "/" . $fileName;
    } else {
      return "";
    }
  }
  public function applyProject(Request $request)
  {
    $validate = $this->UploadCVValidator($request);
    if (!$validate->fails()) {
      $x = $this->uploadCV($request);
      if ($x != "") {
        $project_role = ProjectRole::find($request->project_role_id);
        $apply = Apply::create([
          "user_id" => Auth::user()->id,
          "project_id" => $project_role->project->id,
          "project_role_id" => $request->project_role_id,
          "cv" => $x,
          "extra_answer" => implode(",", $request->extra_answer),
        ]);
        if ($apply) {
          return ResponseFormatter::success($apply, "apply has been sent", 200, 'success');
        } else {
          return ResponseFormatter::error(null, "Gagal mengirim apply, silahkan coba beberapa saat lagi", 400, "fail to send applicant");
        }
      } else {
        return ResponseFormatter::error(null, "Unprocessable Entity", 422, "CV not uploaded");
      }
    } else {
      return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validate->errors());
    }
  }

  public function getAllApplicants(Request $request, $project_id, $role_id)
  {
    try {
      $project = Project::find($project_id);

      if (Auth::user()->company_id != $project->company_id) {
        return ResponseFormatter::error(null, 'You are not in this company', 401, "You are not in this company");
      }

      $applicants = Apply::with(['user', 'projectRole'])->where('project_id', $project_id)->where('project_role_id', $role_id)->get();

      if (count($applicants) > 0) {
        return ResponseFormatter::success($applicants, "Applicants has been retrieved");
      } else {
        return ResponseFormatter::error(null, "Applicants not found", 404, "Applicants not found");
      }
    } catch (Exception $err) {
      return ResponseFormatter::error(null, "Something went wrong", 500, $err->getMessage());
    }
  }

  public function finishProject($project_id)
  {
    $project = Project::find($project_id);
    if (Auth::user()->company_id != $project->company_id) {
      return ResponseFormatter::error(null, 'You are not in this company', 401, "You are not in this company");
    }

    $project->update(['isfinished' => 1]);

    if ($project->wasChanged()) {
      return ResponseFormatter::success($project, $project->name . " is Done");
    } else {
      return ResponseFormatter::error(null, "Something Went Wrong", 400, "Something Went Wrong");
    }
  }

  public function sendCertificate(Request $request, $project_id, $project_member_id)
  {
    $validator = Validator::make($request->all(), [
      "certificate" => "required|file|mimes:pdf"
    ]);
    if ($validator->fails()) {
      return ResponseFormatter::error(null, 'Validation Error', 400, $validator->errors());
    }
    $certificate = asset("storage/" . $request->file('certificate')->store('certificates'));

    $projectMember = ProjectMember::where("id", $project_member_id)
      ->where("project_id", $project_id);



    if ($projectMember->update(["certificate" => $certificate])) {
      return ResponseFormatter::success($projectMember->find($project_member_id), "Certificate has been sent");
    } else {
      return ResponseFormatter::error(null, "Something Went Wrong", 400, "Something Went Wrong");
    }
  }
}
