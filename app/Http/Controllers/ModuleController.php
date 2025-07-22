<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    public function module()
    {
        return view('admin/Module');
    }

    public function moduleDatatable(Request $request)
    {
        return datatables()->of(Module::select('id', 'module_name', 'display_name', 'status', 'created_at', 'updated_at', 'deleted_at'))
            ->editColumn('created_at', function ($request) {
                return $request->created_at->format('d-m-Y H:i');
            })
            ->order(function ($query) {
                $query->orderBy('id', 'asc');
            })
            ->addColumn('action', function ($row) {
                $btn = "<a title='Edit Module' onClick=editModule($row->id) class='btn btn-primary btn-sm' id='editModule'><i class='fas fa-edit text-light'></i></a> <a title='Edit Module' onClick=deleteModule($row->id) class='btn btn-danger btn-sm' id='deleteModule'><i class='fa fa-trash text-light' aria-hidden='true'></i></a>";
                return $btn;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function saveModuleDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'module_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }

        $data = new Module;
        $data->module_name = $request->module_name;
        $data->display_name = $request->display_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Module has been saved successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while saving the module", 'code' => 500]);
        }
    }

    public function editModuleDetails(Request $request)
    {
        $data = Module::find(base64_decode($request->id));
        if ($data) {
            return response()->json(['status' => true, 'message' => $data, 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Record not found", 'code' => '500']);
        }
    }

    public function updateModule(Request $request)
    {
        $data = Module::find($request->id);
        if (empty($data)) {
            return response()->json(['status' => false, 'message' => "Record not found", 'code' => 500]);
        }

        $data->module_name = $request->module_name;
        $data->display_name = $request->display_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Module has been successfully updated"]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while updating module", 'code' => 500]);
        }
    }

    public function deleteModuleDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Module::find($id);
        $data->status = 2;
        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Module has been deleted successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while deleting module", 'code' => 500]);
        }
    }
}
