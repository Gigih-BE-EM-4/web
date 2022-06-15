<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\type;

class CompanyController extends Controller
{
    private function makeValidator(Request $request){
        return Validator::make($request->all(), [
            'name' => 'required|unique:companies',
            'address' => 'required',
            'category' => 'required',
            'email' => 'required|email:dns|unique:companies',
            'contact' => 'required|unique:companies'
        ]);
    }

    private function uploadImage($request){
        if($file = $request->hasFile('profile')) {
            $file = $request->file('profile') ;
            $fileName = $file->getClientOriginalName() ;
            $destinationPath = public_path().'/Company/Profile';
            $waktu = time();
            $file->move($destinationPath,$waktu . $fileName);
            return "/Company/Profile/" . $waktu . $fileName;
        }else {
            return "";
        }
    }

    public function store(Request $request){
        $validator = $this->makeValidator($request);

        if($validator->fails()){
            return ResponseFormatter::error(null, "Unprocessable Entity", 422, $validator->errors());
        }

        $validatedData = $validator->validated();
        $validatedData['bio'] = $request['bio'];
        $validatedData['profile'] = $this->uploadImage($request);
        
        $company = Company::create($validatedData);

        User::find(Auth::user()->id)->update([
            'company_id' => $company->id
        ]);
    
        return ResponseFormatter::success($validatedData, "Company has been created", 201, 'success');
    }

    public function companyDetail($company_id){
        $company = Company::getCompanyDetail($company_id);
        return ResponseFormatter::success($company, "Success Get Company Detail", 200, 'success');
    }

    public function joinCompany(Request $request){
        $company = Company::find($request->company_id);
        $newCompanyMember = User::find($request->user_id);

        if($company == null){
            return ResponseFormatter::success(null, "Company Not Found", 200, 'success');
        }

        if($newCompanyMember == null){
            return ResponseFormatter::success(null, "User Not Found", 200, 'success');
        }

        if(Auth::user()->company_id != $company->id){
            return ResponseFormatter::error(null, "Unauthorized User", 401, 'Unauthorized.');
        }
        $newCompanyMember->update([
            'company_id' => $company->id
        ]);
        return ResponseFormatter::success(['user name' => $newCompanyMember->name, 'company name' => $company->name], "Success Getting Other People Into The Company", 200, 'success');
    }
}
