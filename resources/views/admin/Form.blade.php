@extends('layout/Main')
@section('content')

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Form List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Form</li>
                <li class="breadcrumb-item active">Form List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addForm()">
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
                        <div class="teble-responsive">
                            <table class="formTable table table-stripped table-bordered" id="formTable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Form Name</th>
                                        <th>Form Description</th>
                                        <th>Form Status</th>
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
        <div class="modal fade" id="addEditFormModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEditFormModelTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="addEditForm" id="addEditForm" method="POST">
                            @csrf
                            <input type="hidden" id="id" name="id" value="">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="form_name" class="form-label">Form Name</label>
                                    <input type="text" id="form_name" name="form_name" class="form-control">
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="form_description" class="form-label">Form Description</label>
                                    <textarea name="form_description" id="form_description" class="form-control"></textarea>
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        @foreach (talukaStatus() as $key=>$taluka)
                                        <option value="{{$key}}">{{$taluka}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" onclick="saveForm()">Save</button>
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


        $("#formTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('formDatatable') }}",
            "lengthMenu": [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"],
            ],
            columns: [{
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'form_name',
                    name: 'form_name',
                },
                {
                    data: 'form_description',
                    name: 'form_description',
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
        });
    });

    function addForm(id) {
        save_method = 'add';
        $('#addEditForm')[0].reset();
        $('#addEditFormModel').modal('show');
        $('.modal-title').text("add Form");
    }

    function saveForm() {
        var form_name = $("#form_name").val();
        var form_description = $("#form_description").val();
        var status = $("#status").val();

        var form = $("#addEditForm")[0];
        var formData = new FormData(form);

        var url;
        if (save_method == 'add') {
            url = "saveFormDetails";
            var msg = "New Form has been added successfully";
        } else {
            url = "updateForm";
            var msg = "Form has been updated successfully";
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
                    $('#addEditFormModel').modal('hide');
                    $('#formTable').DataTable().ajax.reload();
                } else {
                    swal.fire("error", data.message, "error");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal.fire("error", "Error adding/updating data", "error");
            }
        });
    }

    function editForm(id) {
        save_method = 'update';
        $('.modal-title').text('Edit form');
        $('#addEditForm')[0].reset();
        $.ajax({
            url: "editFormDetails",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            },
            data: {
                id: btoa(id)
            },
            dataType: "JSON",
            success: function(data) {
                $("#id").val(data.message.id);
                $("#form_name").val(data.message.form_name);
                $("#form_description").val(data.message.form_description);
                $("#status").val(data.message.status);
                $("#addEditFormModel").modal('show');
                $('.modal-title').text("Edit Form");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function deleteForm(id){
        swal.fire({
            title:"Are You Sure ?",
            text:"You will not able to recover the form",
            icon:"warning",
            showCancelButton:true,
            confirmButtonColor:"#DD6B55",
            confirmButtonText:"Yes, Delete it!",
            cancelButtonText:"Cancel",
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url:"{{ url('deleteFormDetails') }}",
                    type:"POST",
                    headers:{
                        'X-CSRF-TOKEN':"{{ csrf_token() }}"
                    },
                    data:{
                        id:btoa(id)
                    },
                    success:function(data){
                        if(data.status){
                            swal.fire("Deleted!",data.message,"Sucess");
                            $('#formTable').DataTable().ajax.reload();
                        }
                        else{
                            swal.fire("Error!",data.message,"error");
                        }
                    },
                    error:function(){
                        swal.fire("Error!","Ajax error occured","error");
                    }
                });
            }
        });
    }
</script>

@endsection