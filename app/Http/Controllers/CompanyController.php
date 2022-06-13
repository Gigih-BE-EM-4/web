<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    private function makeValidator(Request $request){
        return Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
            'category' => 'required',
            'email' => 'required',
            'contact' => 'required'
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
        $request['profile'] = $this->uploadImage($request);
        $validator = $this->makeValidator($request);

        if($validator->fails()){
            ResponseFormatter::error(null, "Unprocessable Entity", 422, $validator->errors());
        }

        $company = Company::create($validator->validated());
        ResponseFormatter::success($company->toArray(), "Success Created Company", 201);
    }
}
