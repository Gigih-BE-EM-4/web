<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\type;

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
        $validator = $this->makeValidator($request);

        if($validator->fails()){
            ResponseFormatter::error(null, "Unprocessable Entity", 422, $validator->errors());
        }

        $request['profile'] = $this->uploadImage($request);

        Company::create($request->toArray());
        dd(Company::all());
        ResponseFormatter::success(null, "Success Created Company", 201, 'success');
    }
}
