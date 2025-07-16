<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function department()
    {
        return view('admin/Department');
    }

    public function departmentDatatable()
    {
        if (request()->ajax()) {
            return datatables()->of(Department::select('id', 'department_name', 'status', 'created_at', 'updated_at', 'deleted_at'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d-m-Y H:i');
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a title='Edit Department' onClick = editDepartment($row->id) class='btn btn-primary btn-sm text-light mx-1' id = 'editDepartment'><i class='fa fa-edit'></i></a> <a title='Delete Department' onClick = deleteDepartment($row->id) class='btn btn-danger btn-sm text-light mx-1' id = 'deleteDepartment'><i class='fa fa-trash'></i></a> ";

                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function saveDepartmentDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'department_name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }
        $data = new Department;
        $data->department_name = $request->department_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Save Successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "error while save the department", 'code' => 500]);
        }
    }

    public function editDepartmentDetails(Request $request)
    {
        $data = Department::find(base64_decode($request->id));
        if ($data) {
            return response()->json(['status' => true, 'message' => $data, 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Record not found", 'code' => 500]);
        }
    }

    public function updateDepartment(Request $request)
    {
        $data = Department::find($request->id);
        if (empty($data)) {
            return response()->json(['status' => false, 'message' => "Record Not Found"]);
        }
        $data->department_name = $request->department_name;
        $data->status = $request->status;
        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Department updated successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while updating department", 'code' => 500]);
        }
    }

    public function deleteDepartmentDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Department::find($id);
        $data->status = 2;
        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Deleted Sucessfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while Deleting Department", 'code' => 500]);
        }
    }
}
