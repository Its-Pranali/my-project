<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function category()
    {
        return view('admin/Category');
    }


    function categoryDatatable()
    {
        if (request()->ajax()) {

            return datatables()->of(Category::select('id', 'category_name', 'status', 'created_at', 'updated_at', 'deleted_at'))
                ->editColumn('created_at', function ($request) {
                    return $request->created_at->format('d-m-Y H:i');
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->addColumn('action', function ($row) {
                    $btn = "<a title='Edit Category' onClick = editCategory($row->id) class='btn btn-primary btn-sm text-light mx-1' id = 'editCategory'><i class='fa fa-edit'></i></a> <a title='Delete Category' onClick = deleteCategory($row->id) class='btn btn-danger btn-sm text-light mx-1' id = 'deleteCategory'><i class='fa fa-trash'></i></a>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function saveCategoryDetails(Request $request)
    {
        $validator = validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'status' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors(), 'code' => 500]);
        }
        $data = new Category;
        $data->category_name = $request->category_name;
        $data->status = $request->status;


        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => " save succesfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, 'message' => "something wents wrong", 'code' => 500]);
        }
    }


    function editCategoryDetails(Request $request)
    {
        $data =  Category::find(base64_decode($request->id));
        if ($data) {
            return response()->json(['status' => true, 'message' => $data, 'code' => 200]);
        } else {
            return response()->json(['status' => false, "message" => "Record Not found", "code" => 500]);
        }
    }

    public function updateCategory(Request $request)
    {
        $data = Category::find($request->id);

        if (empty($data)) {
            return response()->json(['status' => false, "message" => "record not found", "code" => 500]);
        }
        $data->category_name = $request->category_name;
        $data->status = $request->status;

        $result = $data->save();

        if ($result) {
            return response()->json(['status' => true, 'message' => "Category updated successfully!", 'code' => 200]);
        } else {
            return response()->json(['status' => false, "message" => "Error while updating Master", "code" => 500]);
        }
    }

    function deleteCategory(Request $request)
    {
        $id = base64_decode($request->id); 
       // received id and decode
        $data = Category::find($id);
        $data->status = 2;
        $result = $data->save();
        if ($result) {
            return response()->json(['status' => true, 'message' => "Deleted Sucessfully", 'code' => 200]);
        } else {
            return response()->json(['status' => false, "message" => "Something Wrong", "code" => 500]);
        }
    }
}
