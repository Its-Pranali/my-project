<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Subdepartment;
use App\Models\Department;

class SubdepartmentController extends Controller
{
    public function subdepartment()
    {
        $data['department'] = Department::all();
        return view('admin/Subdepartment',['data'=>$data]);
    }

    public function subdepartmentDatatable(Request $request)
    {
        if (request()->ajax()) {
            return datatables()->of(Subdepartment::join("departments as d",'d.id','=','subdepartments.department_name')
                ->select('subdepartments.id', 'subdepartments.department_name', 'subdepartments.subdepartment_name', 'subdepartments.status', 'subdepartments.created_at', 'subdepartments.updated_at', 'subdepartments.deleted_at','d.department_name as dept_name'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d-m-Y H:i');
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a title='Edit Sub Department' class='btn btn-primary btn-sm' onClick=editSubdepartment($row->id) id='editSubdepartment'> <i class='fa fa-edit text-light'></i></a> <a title='Delete Sub Department' class='btn btn-danger btn-sm' onClick=deleteSubdepartment($row->id) id='deleteSubdepartment'> <i class='fa fa-trash text-light'></i></a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function saveSubdepartmentDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'department_name' => 'required|string|max:255',
            'subdepartment_name' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }

        $data = new Subdepartment;
        $data->department_name = $request->department_name;
        $data->subdepartment_name = $request->subdepartment_name;
        $data->status = $request->status;

        $result=$data->save();
        if($result){
            return response()->json(['status'=>true,'message'=>"Save Sucessfully",'code'=>200]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Error while save the subdepartment",'code'=>500]);
        }
    }

    public function editSubdepartmentDetails(Request $request){
        $data=Subdepartment::find(base64_decode($request->id));
        if($data){
            return response()->json(['status'=>true,'message'=>$data,'code'=>200]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Record not found",'code'=>500]);
        }
    }

    public function updateSubdepartment(Request $request){
        $data=Subdepartment::find($request->id);
        if(empty($data)){
            return response()->json(['status'=>false,'message'=>"Record not found",'code'=>500]);
        }

        $data->department_name=$request->department_name;
        $data->subdepartment_name=$request->subdepartment_name;
        $data->status=$request->status;
        $result=$data->save();

        if($result){
            return response()->json(['status'=>true,'message'=>"Subdepartment updated successfully",'code'=>200]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Error while updating subdepartment",'code'=>500]);
        }
    }


    public function deleteSubdepartmentDetails(Request $request){
        $id=base64_decode($request->id);
        $data=Subdepartment::find($id);
        $data->status=2;
        $result=$data->save();
        if($result){
            return response()->json(['status'=>true,'message'=>"Deleted Successfully",'code'=>200]);
        }
        else{
            return response()->json(['status'=>false,'message'=>"Error while deleting the subdepartment",'code'=>500]);
        }
    }
}
