<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TalukaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\SubdepartmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;

Route::get('/', function () {
    return view('Dashboard');
});

//Route for admin
Route::get("/admin", [AdminController::class, "admin"]);

//Route for Role

Route::get("/addRole", [RoleController::class, "index"]);
Route::get("/roleList", [RoleController::class, "roleList"]);
Route::post("/getRoleDetails", [RoleController::class, "getRoleDetails"]);
Route::post("/addNewRole", [RoleController::class, "addNewRole"]);
Route::post("/updateRole", [RoleController::class, "updateRole"]);
Route::post("/deleteRole", [RoleController::class, "deleteRole"]);



//Route for Taluka


Route::get("/taluka",[TalukaController::class,"taluka"]);
Route::get("/talukaDatatable",[TalukaController::class,"talukaDatatable"]);
Route::post("/saveTalukaDetails",[TalukaController::class,"saveTalukaDetails"]);
Route::post("/editTalukaDetails",[TalukaController::class,"editTalukaDetails"]);
Route::post("/updateTaluka",[TalukaController::class,"updateTaluka"]);
Route::post("/deleteTalukaDetails",[TalukaController::class,"deleteTalukaDetails"]);


// Route for Category 


Route::get("/category",[CategoryController::class,"category"]);
Route::get("/categoryDatatable",[CategoryController::class,"categoryDatatable"]);
Route::post("/saveCategoryDetails",[CategoryController::class,"saveCategoryDetails"]);
Route::post("/editCategoryDetails",[CategoryController::class,"editCategoryDetails"]);
Route::post("/updateCategory",[CategoryController::class,"updateCategory"]);
Route::post("/deleteCategory",[CategoryController::class,"deleteCategory"]);


//Route for Department 


Route::get("/department",[DepartmentController::class,"department"]);
Route::get("/departmentDatatable",[DepartmentController::class,"departmentDatatable"]);
Route::post("/saveDepartmentDetails",[DepartmentController::class,"saveDepartmentDetails"]);
Route::post("/editDepartmentDetails",[DepartmentController::class,"editDepartmentDetails"]);
Route::post("/updateDepartment",[DepartmentController::class,"updateDepartment"]);
Route::post("/deleteDepartmentDetails",[DepartmentController::class,"deleteDepartmentDetails"]);


//Route for Form

Route::get("/form",[FormController::class,"form"]);
Route::get("/formDatatable",[FormController::class,"formDatatable"]);
Route::post("/saveFormDetails",[FormController::class,"saveFormDetails"]);
Route::post("/editFormDetails",[FormController::class,"editFormDetails"]);
Route::post("/updateForm",[FormController::class,"updateForm"]);
Route::post("/deleteFormDetails",[FormController::class,"deleteFormDetails"]);


// Route for Subdepartment

Route::get("/subdepartment",[SubdepartmentController::class,"subdepartment"]);
Route::get("/subdepartmentDatatable",[SubdepartmentController::class,"subdepartmentDatatable"]);
Route::post("/saveSubdepartmentDetails",[SubdepartmentController::class,"saveSubdepartmentDetails"]);
Route::post("/editSubdepartmentDetails",[SubdepartmentController::class,"editSubdepartmentDetails"]);
Route::post("/updateSubdepartment",[SubdepartmentController::class,"updateSubdepartment"]);
Route::post("/deleteSubdepartmentDetails",[SubdepartmentController::class,"deleteSubdepartmentDetails"]);



//Route for User

Route::get("/user",[UserController::class,"user"]);
Route::get("/userDatatable",[UserController::class,"userDatatable"]);
Route::post("/saveUserDetails",[UserController::class,"saveUserDetails"]);
Route::post('/getSubdepartments', [UserController::class, 'getSubdepartments']);
Route::post('/editUserDetails', [UserController::class, 'editUserDetails']);
Route::post('/updateUser', [UserController::class, 'updateUser']);
Route::post('/deleteUserDetails', [UserController::class, 'deleteUserDetails']);



// Route for module

Route::get("/module",[ModuleController::class,"module"]);
Route::get("/moduleDatatable",[ModuleController::class,"moduleDatatable"]);
Route::post("/saveModuleDetails",[ModuleController::class,"saveModuleDetails"]);
Route::post("/editModuleDetails",[ModuleController::class,"editModuleDetails"]);
Route::post("/updateModule",[ModuleController::class,"updateModule"]);
Route::post("/deleteModuleDetails",[ModuleController::class,"deleteModuleDetails"]);
