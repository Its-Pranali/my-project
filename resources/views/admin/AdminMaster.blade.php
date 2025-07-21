@extends('layout/Main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Admin Dashboards</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Admin Dashboards</li>
            </ol>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('addRole') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add Role</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('taluka') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add Taluka</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('category') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add Category</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>





            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('department') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add Department</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('form') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add Form</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('subdepartment') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add Subdepartment</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('user') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add User</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('module') }}">
                            <div class="master-heading">
                                <div class="col-12">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-md-10 p-0">
                                            <h5 class="mb-0">Add Module</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <h2 class="m-b-0 text-right">
                                                <i class="fa-solid fa-briefcase text-primary"></i>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    @endsection