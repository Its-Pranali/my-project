@extends('layout/main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Role List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Role</li>
                <li class="breadcrumb-item active">Role List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick='addRole()'><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title">Role List</h4> -->
                        <!-- <h6 class="card-subtitle">Export data to Copy, CSV, Excel, PDF & Print</h6> -->
                        <div class="table-responsive">

                            <table id="roleTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>Role Name</th>
                                        <th>Created By</th>
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
    </div>


    <!-- <div class="modal" id="addEditRoleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"> -->
    <div class="modal fade" id="addEditRoleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addEditRoleForm" method="post">
                        @csrf
                        <input type="hidden" id="id" name="id" />
                        <div class="form-group col-md-12">
                            <label for="role_name">Role Name</label>
                            <input type="text" class="form-control input-sm role_name" name="role_name" id="role_name" />
                            @if ($errors->has('email'))
                            <p class="alert-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <div class="form-group col-md-12">
                            <label>Department Status</label>
                            <select class="form-control status" name="status" id="status">
                                <option value=""> Select Status </option>
                                @foreach(talukaStatus() as $key => $taluka)
                                <option value="{{$key}}">{{$taluka}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('email'))
                            <p class="alert-danger">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                        <Br>
                       
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveRole()">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('/public/assets/plugins/jquery/jquery.min.js')}}"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    let save_method = 'add'; // global scope
    let roleTable;

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        roleTable = $("#roleTable").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/roleList') }}",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'role_name',
                    name: 'role_name'
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data, type, row) {
                        return row.status == 1 ? "Active" : "Inactive";
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[0, 'desc']]
        });
    });

    function addRole() {
        save_method = 'add';
        $('#addEditRoleForm')[0].reset();
        $('#addEditRoleModal').modal('show');
        $('.modal-title').text('Add New Role');
    }

    function editRole(id) {
        save_method = 'update';
        $('#addEditRoleForm')[0].reset();
        $('.modal-title').text('Edit Role');

        $.ajax({
            url: "{{ url('/getRoleDetails') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { id: btoa(id) },
            dataType: "JSON",
            success: function (data) {
                $("#id").val(data.message.id);
                $("#role_name").val(data.message.role_name);
                $("#status").val(data.message.status);
                $('#addEditRoleModal').modal('show');
            },
            error: function () {
                Swal.fire('Error', 'Unable to fetch role data', 'error');
            }
        });
    }

    function saveRole() {
        const role_name = $("#role_name").val();
        const status = $("#status").val();
        const form = $('#addEditRoleForm')[0];
        const formData = new FormData(form);

        if (!role_name) {
            Swal.fire('Error', 'Please enter Role name', 'error');
            return;
        }
        if (!status) {
            Swal.fire('Error', 'Please select status of Role', 'error');
            return;
        }

        let url = save_method === 'add' ? "{{ url('/addNewRole') }}" : "{{ url('/updateRole') }}";

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function (data) {
                if (data.status) {
                    $('#addEditRoleModal').modal('hide');
                    Swal.fire('Success', data.message, 'success');
                    roleTable.ajax.reload(null, false); // ✅ reload without pagination reset
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    }

    function deleteRole(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this Role!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/deleteRole') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { id: btoa(id) },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.status) {
                            Swal.fire('Deleted!', 'Role has been deleted.', 'success');
                            roleTable.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', 'Failed to delete role.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Server error occurred', 'error');
                    }
                });
            }
        });
    }
</script>

@endsection