<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function form()
    {
        return view('admin/Form');
    }

    public function formDatatable(Request $request)
    {
        if (request()->ajax()) {
            return datatables()->of(Form::select('id', 'form_name', 'form_description', 'status', 'created_at', 'updated_at', 'deleted_at'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d-m-Y H:i');
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a title='Edit Form' onClick = editForm($row->id) class='btn btn-primary btn-sm text-light mx-1' id = 'editForm'><i class='fa fa-edit'></i></a> <a title='Delete Form' onClick = deleteForm($row->id) class='btn btn-danger btn-sm text-light mx-1' id = 'deleteForm'><i class='fa fa-trash'></i></a>";

                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function saveFormDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'form_name' => 'required|string|max:255',
            'form_description' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }

        $data = new Form;
        $data->form_name = $request->form_name;
        $data->form_description = $request->form_description;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "save successfully"]);
        } else {
            return response()->json(['status' => true, 'message' => "Error while save the form"]);
        }
    }

    public function editFormDetails(Request $request)
    {
        $data = Form::find(base64_decode($request->id));
        if ($data) {
            return response()->json(['status' => true, 'message' => $data, 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Record Not Found", 'code' => 500]);
        }
    }

    public function updateForm(Request $request)
    {
        $data = Form::find($request->id);
        if (empty($data)) {
            return response()->json(['status' => false, 'message' => "Record Not Found",'code'=>500]);
        }

        $data->form_name = $request->form_name;
        $data->form_description = $request->form_description;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Form Updated sucessfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "error while save the form", 'code' => 500]);
        }
    }

    public function deleteFormDetails(Request $request){
        $id=base64_decode($request->id);
        $data=Form::find($id);
        $data->status=2;
        $result=$data->save();
        if($result){
            return response()->json(['status'=>true,'message'=>"Deleted Sucessfully",'code'=>200]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Error while delete the form",'code'=>500]);
        }
    }
}
