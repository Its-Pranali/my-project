<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $data['menu'] = '2';
        $data['submenu'] = '2';
        return view("admin/Role", ['data' => $data]);
    }

    function roleList()
    {
        if (request()->ajax()) {

            return datatables()->of(Role::select('id', 'role_name', 'status', 'created_by', 'added_by', 'created_at', 'updated_at'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d-m-Y H:i');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button title="Edit Role" onClick="editRole(' . $row->id . ')"  class="text-center btn btn-primary btn-xs"><span class="fa fa-pencil"></span></button> <button title="Delete Role" onClick="deleteRole(' . $row->id . ')"  class="text-center btn btn-danger btn-xs"><span class="fa fa-trash"></span></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    function addNewRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name'      => 'required',
            'status' => 'required'
        ]);
        if (($validator->fails())) {
            return redirect('/addRole')
                ->withErrors($validator)
                ->withInput();
        } else {
            $talukaAdd               = new Role;
            $talukaAdd->role_name    = $request->role_name;
            $talukaAdd->status       = $request->status;
            $talukaAdd->created_by   = session('id');
            $talukaAdd->added_by     = session('name');

            $result = $talukaAdd->save();
            if ($result) {
                return response()->json(['status' => true, 'message' => "Role added successfully!", 'code' => 200]);
            } else {
                return response()->json(['status' => false, "message" => "Error while adding Role", "code" => 500]);
            }
        }
    }
    function getRoleDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $geTalukaDetails = Role::find($id);
        if (!empty($geTalukaDetails->toArray())) {
            return response()->json(['status' => true, 'message' => $geTalukaDetails->toArray(), 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Records Not Found", 'code' => 500]);
        }
    }

    function updateRole(Request $request)
    {
        $id = $request->id;
        unset($request['id']);
        $talukaUpdate               = Role::find($id);
        $talukaUpdate->role_name    = $request->role_name;
        $talukaUpdate->status       = $request->status;
        $talukaUpdate->created_by   = session('id');
        $talukaUpdate->added_by     = session('name');

        $updateTaluka = $talukaUpdate->save();
        if ($updateTaluka) {
            return response()->json(['status' => true, 'message' => "Role Updated Successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error While Updating Role", 'code' => 500]);
        }
    }

    function deleteRole(Request $request)
    {
        $id = base64_decode($request->id);

        $taluka = Role::find($id);
        $taluka->status = 2;

        $deleteTaluka = $taluka->save();
        if ($deleteTaluka) {
            return response()->json(['status' => true, 'message' => "Role Deleted Successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error While Deleting Role ", 'code' => 500]);
        }
    }
}
