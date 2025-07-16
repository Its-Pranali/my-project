@extends('layout/Main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Subdepartment List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Subdepartment</li>
                <li class="breadcrumb-item active">Subdepartment List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addSubdepartment()">
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
                            <table class="table subDepartmentTable table-bordered table-stripped" id="subDepartmentTable">
                                <thead>
                                    <tr>
                                        <td>Sr.No</td>
                                        <td>Department Name</td>
                                        <td>Sub Department Name</td>
                                        <td>Status</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addEditSubdepartmentModal" tabindex="-1" role="dialog" aria-labelledby="addEditSubdepartmentModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEditSubdepartmentModal"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="addEditSubdepartmentForm" id="addEditSubdepartmentForm" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id" class="form-control">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="department_name" class="form-label">Department Name</label>
                                    <select class="form-control department_name" name="department_name" id="department_name">
                                        <option value="">Select Department</option>
                                        @foreach ($data['department'] as $key => $dept )
                                        <option value="{{$dept['id']}}">{{$dept['department_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="subdepartment_name" class="form-label">Sub Department Name</label>
                                    <input type="text" name="subdepartment_name" id="subdepartment_name" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        @foreach (talukaStatus() as $key=>$taluka)
                                        <option value="{{ $key }}">{{ $taluka }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveSubdepartment()">Save</button>
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
        $("#subDepartmentTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('subdepartmentDatatable') }}",
            lengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            columns: [{
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'dept_name',
                    name: 'd.department_name'
                },
                {
                    data: 'subdepartment_name',
                    name: 'subdepartment_name',
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


    function addSubdepartment(id) {
        save_method = 'add';
        $("#addEditSubdepartmentForm")[0].reset();
        $('#addEditSubdepartmentModal').modal('show');
        $('.modal-title').text("Add Sub Department")
    }

    function saveSubdepartment(id) {
        var department_name = $("#department_name").val();
        var subdepartment_name = $("#subdepartment_name").val();
        var status = $("#status").val();

        var form = $("#addEditSubdepartmentForm")[0];
        var formData = new FormData(form);
        var url;
        if (save_method == 'add') {
            url = "saveSubdepartmentDetails";
            var msg = "New Subdepartment has been added successfully";
        } else {
            url = "updateSubdepartment";
            var msg = "Subdepartment has been updated successfully";
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
                    swal.fire("Success", data.message, "success");
                    $("#addEditSubdepartmentModal").modal('hide');
                    $("#subDepartmentTable").DataTable().ajax.reload();
                } else {
                    swal.fire("error", data.message, 'error');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal.fire("error", "Error adding/Update data", "error");
            }
        });
    }

    function editSubdepartment(id) {
        save_method = 'update';
        $(".modal-title").text("Edit Sub Department");
        $("#addEditSubdepartmentForm")[0].reset();
        $.ajax({
            url: "editSubdepartmentDetails",
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
                $("#department_name").val(data.message.department_name);
                $("#subdepartment_name").val(data.message.subdepartment_name);
                $("#status").val(data.message.status);

                $("#addEditSubdepartmentModal").modal('show');
                $(".modal-title").text("Edit Sub Department");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            }
        });
    }

    function deleteSubdepartment(id) {
        swal.fire({
            title: "Are you sure?",
            text: "You will not able to recover this category",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('deleteSubdepartmentDetails') }}",
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
                            $("#subDepartmentTable").DataTable().ajax.reload();
                        } else {
                            swal.fire("Error!", data.message, "error");
                        }
                    },
                    error: function() {
                        swal.fire("Error!", "Ajax error Occured", "error");
                    }
                });
            }
        });
    }
</script>

@endsection