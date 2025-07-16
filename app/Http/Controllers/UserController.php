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
            return datatables()->of(UserDetails::select('id', 'name', 'mobile_no', 'email', 'user_name', 'role_name', 'department_name', 'subdepartment_name', 'taluka_name', 'status', 'created_at', 'updated_at', 'deleted_at'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->frmat('d-m-Y H:i');
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a title='Edit User' onClick=editUser($row->id) class='btn btn-primary btn-sm'><i></i></a>";
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
        $data->subdepartment_name=$request->subdepartment_name;
        $data->taluka_name=$request->taluka_name;
        $data->status=$request->status;

        $result=$data->save();
        if($result){
            return response()->json(['status'=>true,'message'=>"Saved Successfully",'code'=>200]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Error while save the data",'code'=>500]);
        }
    }
}
