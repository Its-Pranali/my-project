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
                        <form class="submoduleForm" id="submoduleForm" method="post">
                            @csrf
                            <div class="row">
                                <input type="hidden" id="id" name="id">
                                <div class="col-md-12 form-group">
                                    <label for="module_name" class="form-label">Module Name</label>
                                    <select name="module_name" id="module_name" class="form-control">
                                        <option value="">Select Module Name</option>
                                        @foreach ($data['module'] as $key=>$module )
                                        <option value="{{ $module['id'] }}">{{ $module['module_name'] }}</option>
                                        @endforeach
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

<script src="{{asset('/public/assets/plugins/jquery/jquery.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        $("#submoduleTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('submoduleDatatable') }}",
            lengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"],
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'm_name',
                    name: 'm.module_name',
                },
                {
                    data: 'submodule_name',
                    name: 'submodule_name',
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
                    name: 'action',
                }
            ]
        });
    });


    function addSubmodule(id) {
        save_method = 'add';
        $("#submoduleForm")[0].reset();
        $("#addEditSubmoduleModal").modal('show');
        $(".modal-title").text("Add Submodule")
    }

    function saveSubmodule(id) {
        var module_name = $("#module_name").val();
        var submodule_name = $("#submodule_name").val();
        var display_name = $("#display_name").val();
        var status = $("#status").val();
        var form = $("#submoduleForm")[0];
        var formData = new FormData(form);
        var url;

        if (save_method == 'add') {
            url = "saveSubmoduleDetails";
            msg = "New submodule has been saved successfully";
        } else {
            url = "updateSubmodule";
            msg = "Submodule has been updated successfully";
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
                    $("#addEditSubmoduleModal").modal('hide');
                    $("#submoduleTable").DataTable().ajax.reload();
                } else {
                    swal.fire("error", data.message, "error");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal.fire("error", "Error adding/updating data", "error");
            }
        });
    }

    function editSubmodule(id) {
        save_method = 'update';
        $(".modal-title").text("Edit Submodule");
        $("#submoduleForm")[0].reset();
        $.ajax({
            url: "editSubmoduleDetails",
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
                $("#submodule_name").val(data.message.submodule_name);
                $("#display_name").val(data.message.display_name);
                $("#status").val(data.message.status);

                $("#addEditSubmoduleModal").modal('show');
                $(".modal-title").text("Edit Submodule");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Get data from ajax");
            }
        });
    }

    function deleteSubmodule(id) {
        swal.fire({
            title: "Are you sure?",
            text: "You will not able to recover the data",
            showCancelButton: true,
            icon: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('deleteSubmoduleDetails') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    data: {
                        id: btoa(id)
                    },
                    success: function(data) {
                        if (data.status) {
                            swal.fire("Deleted!", data.message, "success");
                            $('#submoduleTable').DataTable().ajax.reload();
                        } else {
                            swal.fire("Error!", data.message, "error");
                        }
                    },
                    error: function() {
                        swal.fire("Error!", "Ajax Error Occured", "error");
                    }
                });
            }
        });
    }
</script>

@endsection