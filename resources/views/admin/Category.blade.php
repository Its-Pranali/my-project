@extends('layout/Main')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Category List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Category</li>
                <li class="breadcrumb-item active">Category List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addCategory()">
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
                            <table class="catagoryTable table table-bordered table-stripped" id="catagoryTable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Category Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
    </div>
</div>


<div class="modal fade" id="addCategoryModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="addEditCategoryForm" method="post">
                    @csrf
                    <div class="row">
                        <input type="hidden" id="id" name="id" />
                        <div class="col-md-12 form-group">
                            <label for="category_name" class="form-label">category</label>
                            <input type="text" name="category_name" id="category_name" class="form-control">
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
                <button type="button" class="btn btn-primary btn-sm" onclick="saveCategory()">Save</button>
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

        $("#catagoryTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('categoryDatatable') }}",
            "lengthMenu": [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                // {
                //     data: 'id',
                //     name: 'id'
                // },
                {
                    data: 'category_name',
                    name: 'category_name'
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
                    orderable: false
                }
            ]
        });
    });

    function addCategory(id) {
        save_method = 'add';
        $('#addEditCategoryForm')[0].reset();
        $('#addCategoryModal').modal('show');
        $('.modal-title').text('Add Category');
    }


    function saveCategory() {

        var category_name = $("#category_name").val();
        var status = $("#status").val();
        var form = $('#addEditCategoryForm')[0];
        var formData = new FormData(form);

        var url;
        if (save_method == 'add') {
            url = "saveCategoryDetails";
            var msg = "New Category has been added successfully";
        } else {
            url = "updateCategory";
            var msg = "Category updated successfully";
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
                    $('#addCategoryModal').modal('hide');
                    $('#catagoryTable').DataTable().ajax.reload();
                } else {
                    swal.fire("error", data.message, "error");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                swal.fire("error", "Error adding / update data", "error");
            }
        });
    }


    function editCategory(id) {
        // $("#category_id option[class='prepend_select']").remove();
        save_method = 'update';
        $('.modal-title').text('Edit Category');
        $('#addEditCategoryForm')[0].reset();
        $.ajax({
            url: "editCategoryDetails",
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
                $("#category_name").val(data.message.category_name);
                $("#status").val(data.message.status);
                $('#addCategoryModal').modal('show');
                $('.modal-title').text('Edit Category');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function deleteCategory(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You will not be able to recover this Category!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('deleteCategory') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        id: btoa(id)
                    },
                    success: function(data) {
                        if (data.status) {
                            Swal.fire("Deleted!", data.message, "success");
                            $('#catagoryTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire("Error!", data.message, "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "Ajax error occurred.", "error");
                    }
                });
            }
        });
    }
</script>



@endsection