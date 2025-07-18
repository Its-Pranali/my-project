<?php

namespace App\Http\Controllers;

use App\Models\Taluka;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TalukaController extends Controller
{
    public function taluka()
    {
        return view('admin/Taluka');
    }

    public function talukaDatatable()
    {
        if (request()->ajax()) {
            return datatables()->of(Taluka::select('id', 'taluka_name', 'status', 'created_at', 'updated_at', 'deleted_at'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d-m-Y H:i');
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a title='Edit Taluka' onClick=editTaluka($row->id) class='btn btn-primary btn-xs py-1 px-2' id='editTaluka'><i class='fas fa-edit text-light'></i></a> <a title='Delete Taluka' onClick=deleteTaluka($row->id) class='btn btn-danger btn-xs py-1 px-2' id='deleteTaluka'><i class='fas fa-trash text-light'></i></a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);  
        }
    }
    public function saveTalukaDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'taluka_name' => 'required|string|max:255',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }

        $data = new Taluka;
        $data->taluka_name = $request->taluka_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "save successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => 'false', 'message' => "Something wents wrong!", 'code' => 500]);
        }
    }
    public function editTalukaDetails(Request $request)
    {
        $data = Taluka::find(base64_decode($request->id));
        if ($data) {
            return response()->json(['status' => true, 'message' => $data, 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Record not found", 'code' => 500]);
        }
    }

    public function updateTaluka(Request $request)
    {
        $data = Taluka::find($request->id);
        if (empty($data)) {
            return response()->json(['status' => false, 'message' => "Record not found", 'code' => 500]);
        }
        $data->taluka_name = $request->taluka_name;
        $data->status = $request->status;

        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Taluka Updated Successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error While updating records", 'code' => '500']);
        }
    }

    public function deleteTalukaDetails(Request $request)
    {
        $id = base64_decode($request->id);
        $data = Taluka::find($id);
        $data->status=2;
        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Taluka Deleted successfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "Error while deleting taluka", 'code' => 500]);
        }
    }
}
