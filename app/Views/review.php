<?=view('templates/header') ?>
    <?=view('templates/navbar') ?>
    <?=view('templates/sidebar') ?>
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?=site_url('dashboard')?>">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?=$title?>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <?php if(!empty(session()->getFlashdata('fail'))) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->getFlashdata('fail'); ?>
                    </div>
                <?php endif; ?>
                <div class="card card-box">
                    <div class="card-body">
                         <div class="tab">
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active text-blue" data-toggle="tab" href="#incoming" role="tab" aria-selected="true">Incoming</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#progress" role="tab" aria-selected="true">On Progress</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#completed" role="tab" aria-selected="true">Completed</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="incoming" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-sm" style="width:100%" id="incoming-table">
                                                <thead>
                                                    <th class="bg-primary text-white">Date Received</th>
                                                    <th class="bg-primary text-white">Control Number</th>
                                                    <th class="bg-primary text-white">Status</th>
                                                    <th class="bg-primary text-white">Remarks</th>
                                                    <th class="bg-primary text-white">Last Update</th>
                                                    <th class="bg-primary text-white">Action</th>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="progress" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-sm" id="process-table" style="width:100%">
                                                <thead>
                                                    <th class="bg-primary text-white">Control #</th>
                                                    <th class="bg-primary text-white">Port/Vessel</th>
                                                    <th class="bg-primary text-white">Equipment Name</th>
                                                    <th class="bg-primary text-white">Problem</th>
                                                    <th class="bg-primary text-white">Immediate Cause</th>
                                                    <th class="bg-primary text-white">Status</th>
                                                    <th class="bg-primary text-white">Action</th>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="completed" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-sm data-table" style="width:100%">
                                                <thead>
                                                    <th class="bg-primary text-white">Control #</th>
                                                    <th class="bg-primary text-white">Port/Vessel</th>
                                                    <th class="bg-primary text-white">Equipment Name</th>
                                                    <th class="bg-primary text-white">Problem</th>
                                                    <th class="bg-primary text-white">Immediate Cause</th>
                                                    <th class="bg-primary text-white">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php foreach($records as $row): ?>
                                                    <tr>
                                                        <td><?=$row->control_number?></td>
                                                        <td><?=$row->warehouseName?></td>
                                                        <td><?=$row->equipment_name?></td>
                                                        <td><?=$row->problem?></td>
                                                        <td><?=$row->cause?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                            <a class="btn font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                                                href="#" role="button" data-toggle="dropdown">
                                                                More
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                                                <a class="dropdown-item" href="#">
                                                                    <i class="dw dw-eye"></i> View
                                                                </a>
                                                                <a class="dropdown-item" href="#">
                                                                    <i class="dw dw-list"></i> Items
                                                                </a>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Review
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="preview"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        View Details
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="view"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Edit Status
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="row g-3" id="frmEdit">
                        <?=csrf_field()?>
                        <input type="hidden" name="request_id" id="request_id"/>
                        <div class="col-lg-12 form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="">Choose</option>
                                <option value="2">Cancelled</option>
                                <option value="3">Close/Completed</option>
                                <option value="4">Accepted</option>
                                <option value="5">Verified</option>
                            </select>
                        </div>
                        <div class="col-lg-12 form-group">
                            <button type="submit" class="btn btn-primary form-control text-white">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?=view('templates/script') ?>
    <script>
    let table1 = $('#incoming-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": window.location.origin + "/incoming-request",
            "type": "GET",
            "dataSrc": function (json) {
                // Handle the data if needed
                return json.data;
            },
            "error": function (xhr, error, code) {
                console.error("AJAX Error: " + error);
                alert("Error occurred while loading data.");
            }
        },
        "searching": true,
        "columns": [
            {
                "data": "date"
            },
            {
                "data": "code"
            },
            {
                "data": "status"
            },
            {
                "data": "remarks"
            },
            {
                "data": "last_update"
            },
            {
                "data": "action"
            }
        ]
    }); 

    let table2 = $('#process-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": window.location.origin + "/process-request",
            "type": "GET",
            "dataSrc": function (json) {
                // Handle the data if needed
                return json.data;
            },
            "error": function (xhr, error, code) {
                console.error("AJAX Error: " + error);
                alert("Error occurred while loading data.");
            }
        },
        "searching": true,
        "columns": [
            {
                "data": "code"
            },
            {
                "data": "from"
            },
            {
                "data": "equipment"
            },
            {
                "data": "problem"
            },
            {
                "data": "cause"
            },
            {
                "data": "status"
            },
            {
                "data": "action"
            }
        ]
    });

    $(document).on('click','.previewRequest',function(){
        $.ajax({
            url: window.location.origin + "/fetch-request",
            type: "GET",
            data: { value: $(this).val() },
            success: function(response) {
                $('#preview').html(response);
                $('#previewModal').modal('show');
            },
            error: function(xhr, error, code) {
                console.error("AJAX Error: " + error);
                alert("Error occurred while fetching request details.");
            }
        });
    });

    $(document).on('click','.viewRequest',function(){
        $.ajax({
            url: window.location.origin + "/view-request",
            type: "GET",
            data: { value: $(this).val() },
            success: function(response) {
                $('#view').html(response);
                $('#viewModal').modal('show');
            },
            error: function(xhr, error, code) {
                console.error("AJAX Error: " + error);
                alert("Error occurred while fetching request details.");
            }
        });
    });

    $(document).on('click','.edit',function(){
        $('#request_id').attr("value",$(this).val());
        $('#editModal').modal('show');
    });

    $(document).on('click','.accept',function()
    {
         Swal.fire({
            title: "Are you sure?",
            text: "Do you want to accept this request?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.origin + "/accept-request",
                    method:"POST",data:{value:$(this).val()},
                    success:function(response)
                    {
                        if (response === "success") {
                            $('#previewModal').modal('hide');
                            table1.ajax.reload();
                            table2.ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: response,
                                icon: "warning"
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click','.reject',function()
    {
         Swal.fire({
            title: "Are you sure?",
            text: "Do you want to reject this request?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.location.origin + "/reject-request",
                    method:"POST",data:{value:$(this).val()},
                    success:function(response)
                    {
                        if (response === "success") {
                            $('#previewModal').modal('hide');
                            table1.ajax.reload();
                            table2.ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: response,
                                icon: "warning"
                            });
                        }
                    }
                });
            }
        });
    });

    $('#frmEdit').on('submit',function(e)
    {
        e.preventDefault();
         Swal.fire({
            title: "Are you sure?",
            text: "Do you want to modify the status of  this request?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                let data = $(this).serialize();
                $.ajax({
                    url: window.location.origin + "/edit-status",
                    method:"POST",data:data,
                    success:function(response)
                    {
                        if (response === "success") {
                            $('#editModal').modal('hide');
                            table2.ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: response,
                                icon: "warning"
                            });
                        }
                    }
                });
            }
        });
    });

    </script>
<?=view('templates/footer') ?>    
    