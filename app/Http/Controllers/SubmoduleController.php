<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Submodule;
use App\Models\Module;
use Illuminate\Http\Request;

class SubmoduleController extends Controller
{
    public function submodule()
    {
        $data['module'] = Module::all();
        return view('admin/Submodule', ['data' => $data]);
    }

    public function submoduleDatatable(Request $request)
    {
        return datatables()->of(Submodule::join("modules as m", 'm.id', '=', 'submodules.module_name')
            ->select('submodules.id','submodules.module_name', 'submodules.submodule_name', 'submodules.display_name', 'submodules.status', 'submodules.created_at', 'submodules.updated_at', 'submodules.deleted_at', 'm.module_name as m_name'))
            ->editColumn('created_at', function ($request) {
                return $request->created_at->format('d-m-Y H:i');
            })
            ->order(function ($query) {
                $query->orderBy('submodules.id', 'asc');
            })
            ->addColumn('action', function ($row) {
                $btn = "<a title='Edit Submodule' onClick=editSubmodule($row->id) class='btn btn-primary btn-sm' id='editSubmodule'><i class='fas fa-edit text-light'></i></a> <a title='Delete Submodule' onClick=deleteSubmodule($row->id) class='btn btn-sm btn-danger' id='deleteSubmodule'><i class='fa fa-trash text-light' aria-hidden='true'></i></a>";
                return $btn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveSubmoduleDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'module_name' => 'required|string|max:255',
            'submodule_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }

        $data = new Submodule();
        $data->module_name = $request->module_name;
        $data->submodule_name = $request->submodule_name;
        $data->display_name = $request->display_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Submodule has been saved successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while saving submodule", 'code' => 500]);
        }
    }

    public function editSubmoduleDetails(Request $request)
    {
        $data = Submodule::find(base64_decode($request->id));
        if ($data) {
            return response()->json(['status' => true, 'message' => $data, 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Record not found", 'code' => 500]);
        }
    }

    public function updateSubmodule(Request $request)
    {
        $data = Submodule::find($request->id);
        if (empty($data)) {
            return response()->json(['status' => false, 'message' => "Record not found", 'code' => 500]);
        }

        $data->module_name = $request->module_name;
        $data->submodule_name = $request->submodule_name;
        $data->display_name = $request->display_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Submodule has been updated successfully", 'code' => 200]);
        } else {
            return response(['status' => false, 'message' => "Error while updating the submodule", 'code' => 500]);
        }
    }

    public function deleteSubmoduleDetails(Request $request){
        $id=base64_decode($request->id);
        $data=Submodule::find($id);
        $data->status=2;
        $result=$data->save();
        if($result){
            return response()->json(['status'=>true,'message'=>"Submodule has been deleted successfully",'code'=>200]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Error while delete the submodule",'code'=>500]);
        }
    }
}
