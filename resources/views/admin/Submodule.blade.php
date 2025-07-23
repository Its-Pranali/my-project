@extends('layout/Main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Submodule List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Submodule</li>
                <li class="breadcrumb-item active">Submodule List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addSubmodule()">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-stripped" id="submoduleTable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Module Name</th>
                                        <th>Submodule Name</th>
                                        <th>Display Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEditSubmoduleModal">
            Launch demo modal
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addEditSubmoduleModal" tabindex="-1" role="dialog" aria-labelledby="addEditSubmoduleModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEditSubmoduleModal"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="submoduleForm" id="submoduleForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="module_name" class="form-label">Module Name</label>
                                    <select name="module_name" id="module_name" class="form-control">
                                        <option value="">Select Module Name</option>
                                    </select>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="submodule_name" class="form-label">Submodule Name</label>
                                    <input type="text" name="submodule_name" id="submodule_name" class="form-control">
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="display_name" class="form-label">Display Name</label>
                                    <input type="text" name="display_name" id="display_name" class="form-control">
                                </div>

                                <div class="col-md-12 form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        @foreach (talukaStatus() as $key=>$taluka )
                                        <option value="{{ $key }}">{{ $taluka }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveSubmodule()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection