<?=view('templates/header') ?>
    <?=view('templates/navbar') ?>
    <?=view('templates/sidebar') ?>
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="row mb-2">
                    <div class="col-md-11 col-sm-11">
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
                    <div class="col-md-1 col-sm-1">
                        <div class="dropdown">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle waves-effect" data-toggle="dropdown" aria-expanded="false">
                                Add&nbsp;<span class="caret"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#roleModal"><i class="icon-copy bi bi-plus-circle"></i>&nbsp;Add Role</a>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#codeModal"><i class="icon-copy bi bi-plus-circle"></i>&nbsp;Add Code</a>
                            </div>
                        </div>
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
                                    <a class="nav-link active text-blue" data-toggle="tab" href="#home" role="tab" aria-selected="true">Users Permissions</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#codes" role="tab" aria-selected="true">Codes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#logs" role="tab" aria-selected="true">System Logs</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="home" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="tblrole">
                                                <thead>
                                                    <th class="bg-primary text-white">Role</th>
                                                    <th class="bg-primary text-white">Manage</th>
                                                    <th class="bg-primary text-white">Approval</th>
                                                    <th class="bg-primary text-white">Reports</th>
                                                    <th class="bg-primary text-white">Settings</th>
                                                    <th class="bg-primary text-white">Action</th>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="codes" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" width="100%" id="tbl_code">
                                                <thead>
                                                    <th class="bg-primary text-white">Created At</th>
                                                    <th class="bg-primary text-white">Port/Vessel</th>
                                                    <th class="bg-primary text-white">Code</th>
                                                    <th class="bg-primary text-white">Last Update</th>
                                                    <th class="bg-primary text-white">Action</th>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="logs" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped data-table">
                                                <thead>
                                                    <th class="bg-primary text-white">Date & Time</th>
                                                    <th class="bg-primary text-white">Fullname</th>
                                                    <th class="bg-primary text-white">Activities</th>
                                                </thead>
                                                <tbody>
                                                <?php foreach($logs as $row): ?>
													<tr>
														<td><?php echo $row->Date ?></td>
														<td><?php echo $row->Fullname ?></td>
														<td><?php echo $row->Activity ?></td>
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
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        New System Role
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="row g-3" id="frmRole">
                        <div class="col-lg-12 form-group">
                            <label>Name of Role</label>
                            <input type="text" class="form-control" name="role" required/>
                            <div id="role-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Manage</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio1" name="manage" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio1">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio2" name="manage" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio2">No</label>
                                    </div>
                                </div>
                            </div>
                            <div id="manage-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Approval</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio3" name="approval" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio3">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio4" name="approval" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio4">No</label>
                                    </div>
                                </div>
                            </div><div id="approval-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Reports</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio5" name="report" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio5">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio6" name="report" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio6">No</label>
                                    </div>
                                </div>
                            </div>
                            <div id="report-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Settings</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio7" name="settings" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio7">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio8" name="settings" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio8">No</label>
                                    </div>
                                </div>
                            </div>
                            <div id="settings-error" class="error-message text-danger text-sm"></div>
                            <?=csrf_field()?>
                        </div>
                        <div class="col-lg-12 form-group">
                            <button type="submit" class="form-control btn btn-primary text-white">Save Entry</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Edit System Role
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="row g-3" id="frmEditRole">
                        <input type="hidden" name="role_id" id="role_id"/>
                        <div class="col-lg-12 form-group">
                            <label>Name of Role</label>
                            <input type="text" class="form-control" name="edit-role" id="edit-role" required/>
                            <div id="edit-role-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Manage</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio11" name="edit-manage" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio11">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio12" name="edit-manage" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio12">No</label>
                                    </div>
                                </div>
                            </div>
                            <div id="edit-manage-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Approval</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio13" name="edit-approval" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio13">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio14" name="edit-approval" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio14">No</label>
                                    </div>
                                </div>
                            </div><div id="edit-approval-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Reports</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio15" name="edit-report" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio15">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio16" name="edit-report" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio16">No</label>
                                    </div>
                                </div>
                            </div>
                            <div id="edit-report-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Settings</label>
                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio17" name="edit-settings" value="1" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio17">Yes</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="custom-control custom-radio form-control">
                                        <input type="radio" id="customRadio18" name="edit-settings" value="0" class="custom-control-input"/>
										<label class="custom-control-label" for="customRadio18">No</label>
                                    </div>
                                </div>
                            </div>
                            <div id="edit-settings-error" class="error-message text-danger text-sm"></div>
                            <?=csrf_field()?>
                        </div>
                        <div class="col-lg-12 form-group">
                            <button type="submit" class="form-control btn btn-primary text-white">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        New Port/Vessel Code
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="row g-3" id="frmCode">
                        <?=csrf_field()?>
                        <div class="col-lg-12 form-group">
                            <label class="form-label">Ports/Vessel</label>
                            <select name="port_vessel" class="form-control" required>
                                <option value="">Choose</option>
                                <?php foreach($office as $row): ?>
                                    <option value="<?=$row->warehouseID ?>"><?=$row->warehouseName ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div id="port_vessel-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" name="code" required/>
                            <div id="code-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <button type="submit" class="form-control btn btn-primary text-white">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?=view('templates/script') ?>
    <script>
        var role = $('#tblrole').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": window.location.origin + "/fetch-permission",
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
                    "data": "role"
                },
                {
                    "data": "manage"
                },
                {
                    "data": "approval"
                },
                {
                    "data": "reports"
                },
                {
                    "data": "settings"
                },
                {
                    "data": "action"
                }
            ]
        });
        $('#frmRole').on('submit', function(e) {
            e.preventDefault();
            $('.error-message').html('');
            let data = $(this).serialize();
            $.ajax({
                url: window.location.origin + "/save-role",
                method: "POST",
                data: data,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Great!',
                            text: "Successfully submitted",
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then((result) => {
                            // Action based on user's choice
                            if (result.isConfirmed) {
                                $('#frmRole')[0].reset();
                                $('#roleModal').modal('hide');
                                // Perform some action when "Yes" is clicked
                                role.ajax.reload();
                            }
                        });
                    } else {
                        var errors = response.error;
                        // Iterate over each error and display it under the corresponding input field
                        for (var field in errors) {
                            $('#' + field + '-error').html('<p>' + errors[field] +
                                '</p>'); // Show the first error message
                            $('#' + field).addClass(
                                'text-danger'); // Highlight the input field with an error
                        }
                    }
                }
            });
        });
        var list_code = $('#tbl_code').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": window.location.origin + "/fetch-codes",
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
                    "data": "from"
                },
                {
                    "data": "name"
                },
                {
                    "data": "code"
                },
                {
                    "data": "to"
                },
                {
                    "data": "action"
                }
            ]
        });
        $('#frmCode').on('submit', function(e) {
            e.preventDefault();
            $('.error-message').html('');
            let data = $(this).serialize();
            $.ajax({
                url: window.location.origin + "/save-code",
                method: "POST",
                data: data,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Great!',
                            text: "Successfully submitted",
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then((result) => {
                            // Action based on user's choice
                            if (result.isConfirmed) {
                                $('#frmCode')[0].reset();
                                $('#codeModal').modal('hide');
                                // Perform some action when "Yes" is clicked
                                list_code.ajax.reload();
                            }
                        });
                    } else {
                        var errors = response.error;
                        // Iterate over each error and display it under the corresponding input field
                        for (var field in errors) {
                            $('#' + field + '-error').html('<p>' + errors[field] +
                                '</p>'); // Show the first error message
                            $('#' + field).addClass(
                                'text-danger'); // Highlight the input field with an error
                        }
                    }
                }
            });
        });

        $(document).on('click','.editRole',function(){
            $.ajax({
                url:window.location.origin + "/edit-role",
                method:"GET",
                data:{value:$(this).val()},
                success:function(response)
                {
                    console.log(response);
                    $('#role_id').attr("value", response.role_id);
                    $('#edit-role').attr("value", response.role);
                    $('#editRoleModal').modal('show');
                }
            });
        });
        $('#frmEditRole').on('submit', function(e) {
            e.preventDefault();
            $('.error-message').html('');
            let data = $(this).serialize();
            $.ajax({
                url: window.location.origin + "/modify-role",
                method: "POST",
                data: data,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Great!',
                            text: "Successfully applied changes",
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        }).then((result) => {
                            // Action based on user's choice
                            if (result.isConfirmed) {
                                $('#frmEditRole')[0].reset();
                                $('#editRoleModal').modal('hide');
                                // Perform some action when "Yes" is clicked
                                role.ajax.reload();
                            }
                        });
                    } else {
                        var errors = response.error;
                        // Iterate over each error and display it under the corresponding input field
                        for (var field in errors) {
                            $('#' + field + '-error').html('<p>' + errors[field] +
                                '</p>'); // Show the first error message
                            $('#' + field).addClass(
                                'text-danger'); // Highlight the input field with an error
                        }
                    }
                }
            });
        });
    </script>
<?=view('templates/footer') ?>    
    