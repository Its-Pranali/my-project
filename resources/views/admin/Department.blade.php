@extends('layout/Main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Department List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Department</li>
                <li class="breadcrumb-item active">Department List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addDepartment()">
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
                            <table class="table departmentTable table-bordered table-stripped" id="departmentTable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Department List</th>
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
        <div class="modal fade" id="addDepartmentModel" tabindex="-1" role="dialog" aria-labelledby="addDepartmentTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDepartmentTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addEditDepartmentForm">
                            @csrf
                            <div class="row">
                                <input type="hidden" id="id" name="id" class="form-control">
                                <div class="col-md-12 form-group">
                                    <label for="department_name" class="form-label">Department Name</label>
                                    <input type="text" name="department_name" id="department_name" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control status" name="status" id="status">
                                        <option value=""> Select Status </option>
                                        @foreach(talukaStatus() as $key => $taluka)
                                        <option value="{{$key}}">{{$taluka}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveDepartment()">Save</button>
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

        $("#departmentTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('departmentDatatable') }}",
            "lengthMenu": [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            columns: [{
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'department_name',
                    name: 'department_name',
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

    function addDepartment(id) {
        save_method = 'add';
        $('#addEditDepartmentForm')[0].reset();
        $('#addDepartmentModel').modal('show');
        $('.modal-title').text('Add Department');
    }

    function saveDepartment() {
        var department_name = $("#department_name").val();
        var status = $("#status").val();
        var form = $('#addEditDepartmentForm')[0];
        var formData = new FormData(form);

        var url;
        if (save_method == 'add') {
            url = "saveDepartmentDetails";
            var msg = "New Department has been added successfully";
        } else {
            url = "updateDepartment";
            var msg = "Department had been updated successfully";
        }
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    swal.fire("success", data.message, "success");
                    $('#addDepartmentModel').modal('hide');
                    $('#departmentTable').DataTable().ajax.reload();
                } else {
                    swal.fire("error", data.message, "error");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal.fire("error", "Error adding/updating data", "error");
            }
        });
    }

    function editDepartment(id){
        save_method='update';
        $('.modal-title').text("Edit Department");
        $('#addEditDepartmentForm')[0].reset();
        $.ajax({
            url:"editDepartmentDetails",
            type:"POST",
            headers:{
                'X-CSRF-TOKEN':"{{ csrf_token() }}",
            },
            data:{
                id:btoa(id)
            },
            dataType:"JSON",
            success:function(data){
                $("#id").val(data.message.id);
                $("#department_name").val(data.message.department_name);
                $("#status").val(data.message.status);

                $("#addDepartmentModel").modal('show');
                $('.modal-title').text("Edit Department");
            },
            error:function(jqHXR,textStatus,errorThrown){
                alert('Error get data from ajax');
            }
        });
    }

    function deleteDepartment(id){
        swal.fire({
            title:"Are you sure?",
            text:"You will not able to recover the Department",
            icon:"warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!"
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url:"{{ url('deleteDepartmentDetails') }}",
                    type:"POST",
                    headers:{
                        'X-CSRF-TOKEN':"{{ csrf_token() }}",
                    },
                    data:{
                        id:btoa(id)
                    },
                    success:function(data){
                        if(data.status){
                            swal.fire("Deleted",data.message,"success");
                            $('#departmentTable').DataTable().ajax.reload();
                        }
                        else{
                            swal.fire("Error!",data.message,"error");
                        }
                    },
                    error:function(){
                        swal.fire("Error!","Ajax error occured","error");
                    }
                })
            }
        })
    }
</script>




@endsection