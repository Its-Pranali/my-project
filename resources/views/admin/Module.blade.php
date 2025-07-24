@extends('layout/main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Module List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Module</li>
                <li class="breadcrumb-item active">Module List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addModule()">
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
                            <table class="table table-bordered table-stripped moduleTable" id="moduleTable">
                                <thead>
                                    <th>Sr.No</th>
                                    <th>Module Name</th>
                                    <th>Display Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="addEditModuleModel" tabindex="-1" role="dialog" aria-labelledby="addEditModuleModel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEditModuleModel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="addEditModuleForm" id="addEditModuleForm" method="post">
                            @csrf
                            <div class="row">
                                <input type="hidden" id="id" name="id" class="form-control">
                                <div class="col-md-12 form-group">
                                    <label for="module_name" class="form-label">Module Name</label>
                                    <input type="text" name="module_name" id="module_name" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="display_name" class="form-label">Dispaly Name</label>
                                    <input type="text" name="display_name" id="display_name" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">Select Status</option>
                                        @foreach(talukaStatus() as $key => $taluka)
                                        <option value="{{ $key }}">{{ $taluka }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveModule()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('/public/assets/plugins/jquery/jquery.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });
        $("#moduleTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('moduleDatatable') }}",
            lengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            columns: [{
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'module_name',
                    name: 'module_name',
                },
                {
                    data: 'display_name',
                    name: 'display_name',
                },
                {
                    data: 'status',
                    name: 'status',
                    data: function(row, type, value, meta) {
                        if (row.status == 1) {
                            return "Active";
                        } else {
                            return "Inactive";
                        }
                    }
                },
                {
                    data: 'action',
                    data: 'action',
                }
            ]
        });
    });

    function addModule(id) {
        save_method = 'add';
        $("#addEditModuleForm")[0].reset();
        $("#addEditModuleModel").modal('show');
        $(".modal-title").text("Add module");
    }

    function saveModule(id) {
        var module_name = $("#module_name").val();
        var display_name = $("#display_name").val();
        var status = $("#status").val();
        var form = $("#addEditModuleForm")[0];
        var formData = new FormData(form);
        var url;

        if (save_method == 'add') {
            url = "saveModuleDetails";
            msg = "New module has been added successfully";
        } else {
            url = "updateModule";
            msg = "Module updated successfully";
        }

        $.ajax({
            url: url,
            type: "POST",
            processData: false,
            contentType: false,
            data: formData,
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    swal.fire("success", data.message, "success");
                    $("#addEditModuleModel").modal('hide');
                    $("#moduleTable").DataTable().ajax.reload();
                } else {
                    swal.fire("error", data.message, "error");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal.fire("error", "Error adding/updation data", "error");
            }
        });
    }

    function editModule(id) {
        save_method = 'update';
        $("modal-title").text("Edit Module");
        $("#addEditModuleForm")[0].reset();
        $.ajax({
            url: "editModuleDetails",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                id: btoa(id)
            },
            dataType: "JSON",
            success: function(data) {
                $("#id").val(data.message.id);
                $("#module_name").val(data.message.module_name);
                $("#display_name").val(data.message.display_name);
                $("#status").val(data.message.status);

                $("#addEditModuleModel").modal('show');
                $(".modal-title").text("Edit Module");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("get data from ajax");
            }
        });
    }


    function deleteModule(id) {
        swal.fire({
            title: "Are you sure?",
            text: "You will not able to recover the data",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Delete it!",
            cancelButtonText: "Cancel",

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('deleteModuleDetails') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        id: btoa(id)
                    },
                    success: function(data) {
                        if (data.status) {
                            swal.fire("Deleted!", data.message, "success");
                            $("#moduleTable").DataTable().ajax.reload();
                        } else {
                            swal.fire("Error", data.message, "error");
                        }
                    },
                    error: function() {
                        swal.fire("Error", "Ajax error occured", "error");
                    }
                });
            }
        });
    }
</script>

@endsection