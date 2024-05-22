<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'material' => 'required|file|mimes:doc,docx,pdf,txt|max:10240',
            'course' => 'required'
        ], [
            'material.required' => 'Please select a document to upload',
            'material.file' => 'The uploaded file is invalid.',
            'material.mimes' => 'Only documents in .doc, .docx, .pdf, or .txt format are allowed.',
            'file.max' => 'The document must not be larger than 10MB',
            'course.required' => 'Course is required'
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
        }

        $file = $request->file('material');

        $data = [];
        $data['uploader'] = auth()->id();
        $data['url'] = $file->store('materials', 'public');
        $data['name'] = preg_replace('/\.([^\.]+)$/', '', $file->getClientOriginalName());
        $data['type'] = $file->getClientMimeType();
        $data['extension'] = $file->getClientOriginalExtension();
        $data['size'] = $file->getSize();
        $data['course_code'] = $request->course;

        $material = Material::create($data);

        if ($material) {

            ActivityLog::log(
                auth()->user(),
                'material_upload', "upload material titled '{$data['name']}' for {$data['course_code']}"
            );
            
            return redirect()->back()->with('message', 'Material Uploaded Successfully');
        }

        return redirect()->back()->withErrors(['error' => 'Failed to upload material'])->onlyInput();
    }



    public function insert()
    {
        return view('pages.materials.insert');
    }


    public function show(Material $material)
    {
        dd($material);
    }
}
