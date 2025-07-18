@extends('layout/Main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">User List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add User</li>
                <li class="breadcrumb-item active">User List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addUser()">
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
                            <table class="table table-bordered table-stripped userTable" id="userTable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Employee Name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th>Sub Department</th>
                                        <th>Taluka</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addEditUserModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEditUserModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="addEditUserForm" id="addEditUserForm" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="mobile_no" class="form-label">Mobile No</label>
                                    <input type="tel" name="mobile_no" id="mobile_no" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="email" class="form-label">Email Id</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="user_name" class="form-label">Username</label>
                                    <input type="text" name="user_name" id="user_name" class="form-control">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="role_name" class="form-label">Select Role</label>
                                    <select name="role_name" id="role_name" class="form-control">
                                        <option value="">Select Role</option>
                                        @foreach ( $data['role'] as $key => $role )
                                        <option value="{{ $role['id'] }}">{{ $role['role_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="department_name" class="form-label">Select Department</label>
                                    <select name="department_name" id="department_name" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach ($data['department'] as $key => $dept )
                                        <option value="{{ $dept['id'] }}">{{ $dept['department_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="subdepartment_name" class="form-label">Select SubDepartment</label>
                                    <select name="subdepartment_name" id="subdepartment_name" class="form-control">
                                        <option value="">Select Subdepartment</option>
                                        @foreach($data['subdepartment'] as $key=>$sub_dept)
                                        <option value="{{ $sub_dept['id'] }}">{{ $sub_dept['subdepartment_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="taluka_name" class="form-label">Select Taluka</label>
                                    <select name="taluka_name" id="taluka_name" class="form-control">
                                        <option value="">Select Taluka</option>
                                        @foreach($data['taluka'] as $key=>$taluka)
                                        <option value="{{ $taluka['id'] }}">{{ $taluka['taluka_name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="status" class="form-label">Select Status</label>
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
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveUser()">Save</button>
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
                'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content')
            }
        });
        $("#userTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('userDatatable') }}",
            "lengthMenu": [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"],
            ],
            columns: [{
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'user_name',
                    name: 'user_name',
                },
                {
                    data: 'r_name',
                    name: 'r.role_name',
                },
                {
                    data: 'dept_name',
                    name: 'd.department_name',
                },
                {
                    data: 's_dept',
                    name: 'sub_dept.subdepartment_name',
                },
                {
                    data: 'taluka',
                    name: 't.taluka_name',
                },
                {
                    data: 'status',
                    name: 'status',
                    data: function(row, type, val, meta) {
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
                },
            ]
        })
    });

    function addUser(id) {
        save_method = 'add';
        $("#addEditUserForm")[0].reset();
        $("#addEditUserModal").modal('show');
        $(".modal-title").text("Add User");
    }

    function saveUser() {
        var name = $("#name").val();
        var mobile_no = $("#mobile_no").val();
        var email = $("#email").val();
        var user_name = $("#user_name").val();
        var role_name = $("#role_name").val();
        var department_name = $("#department_name").val();
        var subdepartment_name = $("#subdepartment_name").val();
        var taluka_name = $("#taluka_name").val();
        var status = $("#status").val();

        var form= $("#addEditUserForm")[0];
        var formData=new FormData(form);

        var url;
        if(save_method=='add'){
            url="saveUserDetails";
            var msg="New user has been saved successfully";
        }
        else{
            url="";
            var msg="user has been updated successfully";
        }
        $.ajax({
            url:url,
            type:"POST",
            processData:false,
            contentType:false,
            data:formData,
            dataType:"Json",
            success:function(data){
               if(data.status){
                swal.fire("success",data.message,"success");
                $('#addEditUserModal').modal('hide');
                $('#userTable').DataTable().ajax.reload();
               }
               else{
                swal.fire("error",data.message,"error");
               }
            },
            error:function(jqXHR,textStatus,errorThrown){
                swal.fire("error","Error adding/updating data","error");
            }
        });
    }
</script>

@endsection