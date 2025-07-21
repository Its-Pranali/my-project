<?php

namespace App\Http\Controllers;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function module(){
        return view('admin/Module');
    }

    public function moduleDatatable(Request $request){
       return datatables()->of(Module::select('id','module_name','display_name','status','created_at','updated_at','deleted_at'))
       ->editColumn('created_at',function($request){
        return $request->created_at->format('d-m-Y H:i');
       })
       ->order(function($query){
        $query->orderBy('id','desc');
       })
       ->addColumn('action',function($row){
        $btn="<a title='Edit Module' onClick=editModule($row->id) class='btn btn-primary' id='editModule'></a>";
        return $btn;
       })
       ->rawColumns(['action'])
       ->addIndexColumn()
       ->make(true);
    }
}
