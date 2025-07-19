<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserDetails;
use App\Models\Role;
use App\Models\Department;
use App\Models\Subdepartment;
use App\Models\Taluka;

class UserController extends Controller
{
    public function user()
    {
        $data['role'] = Role::all();
        $data['department'] = Department::all();
        $data['subdepartment'] = Subdepartment::all();
        $data['taluka'] = Taluka::all();
        return view('admin/User', ['data' => $data]);
    }

    public function userDatatable(Request $request)
    {
        if (request()->ajax()) {
            // return datatables()->of(UserDetails::select('id', 'name', 'mobile_no', 'email', 'user_name', 'role_name', 'department_name', 'subdepartment_name', 'taluka_name', 'status', 'created_at', 'updated_at', 'deleted_at'))
            //     ->editColumn('created_at', function ($request) {
            //         return $request->created_at->format('d-m-Y H:i');
            //     })
            return datatables()->of(UserDetails::join('roles as r', 'r.id', '=', 'user_details.role_name')
                ->join('departments as d', 'd.id', '=', 'user_details.department_name')
                ->join('subdepartments as sub_dept', 'sub_dept.id', '=', 'user_details.subdepartment_name')
                ->join('talukas as t', 't.id', '=', 'user_details.taluka_name')
                ->select('user_details.id', 'user_details.name', 'user_details.mobile_no', 'user_details.email', 'user_details.user_name', 'user_details.role_name', 'user_details.department_name', 'user_details.subdepartment_name', 'user_details.taluka_name', 'user_details.status', 'user_details.created_at', 'user_details.updated_at', 'user_details.deleted_at', 'r.role_name as r_name', 'd.department_name as dept_name', 'sub_dept.subdepartment_name as s_dept', 't.taluka_name as taluka'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d-m-Y H:i');
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'asc');
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a title='Edit User' onClick=editUser($row->id) class='btn btn-primary btn-sm'><i class='fa fa-edit text-light'></i></a> <a title='Delete User' onClick=deleteUser($row->id) class='btn btn-danger btn-sm'><i class='fa fa-trash text-light' aria-hidden='true'></i></a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function saveUserDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_no' => 'required',
            'email' => 'required',
            'user_name' => 'required',
            'role_name' => 'required',
            'department_name' => 'required',
            'subdepartment_name' => 'required',
            'taluka_name' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }
        $data = new UserDetails;
        $data->name = $request->name;
        $data->mobile_no = $request->mobile_no;
        $data->email = $request->email;
        $data->user_name = $request->user_name;
        $data->role_name = $request->role_name;
        $data->department_name = $request->department_name;
        $data->subdepartment_name = $request->subdepartment_name;
        $data->taluka_name = $request->taluka_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Saved Successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while save the data", 'code' => 500]);
        }
    }

    public function getSubdepartments(Request $request)
    {
        $data = Subdepartment::where('id', $request->id)->get();

        return response()->json($data);
    }

    public function editUserDetails(Request $request){
        $data=User
    }
}
