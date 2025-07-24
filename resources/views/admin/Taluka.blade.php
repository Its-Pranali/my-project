@extends('layout/Main')
@section('content')

<!-- CSRF token (ensure this is in your layout/Main.blade.php head) -->

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">Taluka List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item">Add Taluka</li>
                <li class="breadcrumb-item active">Taluka List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <button class="btn btn-info btn-sm pull-right" onclick="addTaluka()">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taluka Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display nowrap table table-hover table-striped table-bordered" id="talukaTable">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Taluka Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addEditTalukaModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Taluka</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <form id="addEditTalukaForm" method="post">
                        @csrf
                        <input type="hidden" id="id" name="id" />
                        <div class="form-group">
                            <label for="taluka_name">Taluka Name</label>
                            <input type="text" class="form-control" name="taluka_name" id="taluka_name" />
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="">Select Status</option>
                                @foreach(talukaStatus() as $key => $taluka)
                                <option value="{{ $key }}">{{ $taluka }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveTaluka()">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Scripts -->



<script src="{{asset('/public/assets/plugins/jquery/jquery.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        $("#talukaTable").dataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('talukaDatatable') }}",
            "lenghtMenu": [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'taluka_name',
                    name: 'taluka_name',
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
                }
            ]

        });
    });


    function addTaluka(id) {
        save_method = 'add';
        $('#addEditTalukaForm')[0].reset();
        $('#addEditTalukaModal').modal('show');
        $('.modal-tital').text('Add Taluka');
    }

    function saveTaluka() {
        var taluka_name = $("#taluka_name").val();
        var status = $("#satus").val();
        var form = $('#addEditTalukaForm')[0];
        var formData = new FormData(form);
        var url;
        if (save_method == 'add') {
            url = "saveTalukaDetails";
            var msg = "New taluka has been added succesfully";
        } else {
            url = "updateTaluka";
            var msg = "Taluka updated successfully";
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
                    $('#addEditTalukaModal').modal("hide");
                    $('#talukaTable').DataTable().ajax.reload();
                } else {
                    swal.fire("error", data.message, "error");
                }
            },
            error: function(jaXHR, textStatus, errorThrown) {
                swal.fire("error", "Error adding/ Update data", "error");
            }
        });
    }

    function editTaluka(id) {
        save_method = 'update';
        $('modal-title').text('Edit Taluka');
        $('#addEditTalukaForm')[0].reset();
        $.ajax({
            url: "editTalukaDetails",
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
                $("#taluka_name").val(data.message.taluka_name);
                $("#status").val(data.message.status);
                $('#addEditTalukaModal').modal('show');
                $('.modal-title').text('Edit Taluka');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function deleteTaluka(id) {
        swal.fire({
            title: "Are you sure?",
            text: "You will not able to recover the Taluka",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('deleteTalukaDetails') }}",
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
                            $('#talukaTable').DataTable().ajax.reload();
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