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
                                    <a class="nav-link active text-blue" data-toggle="tab" href="#home" role="tab" aria-selected="true">My Request</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-blue" data-toggle="tab" href="#items" role="tab" aria-selected="true">Materials Needed</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="home" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-stripe table-sm data-table" style="width:100%">
                                                <thead>
                                                    <th class="bg-primary text-white">Control #</th>
                                                    <th class="bg-primary text-white">Port/Vessel</th>
                                                    <th class="bg-primary text-white">Equipment Name</th>
                                                    <th class="bg-primary text-white">Problem</th>
                                                    <th class="bg-primary text-white">Immediate Cause</th>
                                                    <th class="bg-primary text-white">Status</th>
                                                    <th class="bg-primary text-white">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php foreach($records as $row): ?>
                                                <tr>
                                                    <td><?=$row->control_number ?></td>
                                                    <td><?=$row->warehouseName ?></td>
                                                    <td><?=$row->equipment_name ?></td>
                                                    <td><?=substr($row->problem,0,50) ?>...</td>
                                                    <td><?=substr($row->cause,0,50) ?>...</td>
                                                    <td>
                                                        <?php if($row->status==0):?>
                                                            <span class="badge bg-warning text-white">For Review</span>
                                                        <?php elseif($row->status==1):?>
                                                            <span class="badge bg-primary text-white">Reviewed</span>
                                                        <?php elseif($row->status==2): ?>
                                                            <span class="badge bg-danger text-white">Declined</span>
                                                        <?php elseif($row->status==3):?>
                                                            <span class="badge bg-secondary text-white">Close</span>
                                                        <?php elseif($row->status==4):?>
                                                            <span class="badge bg-primary text-white">Accepted</span>
                                                        <?php elseif($row->status==5):?>
                                                            <span class="badge bg-success text-white">Verified</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($row->status==4 || $row->status==5): ?>
                                                            <button type="button" class="btn btn-primary btn-sm upload" value="<?=$row->request_id?>">
                                                                <i class="icon-copy dw dw-upload"></i>&nbsp;Upload
                                                            </button>
                                                        <?php endif;?>
                                                        <?php if($row->status==3): ?>
                                                            <a href="<?=site_url('print/')?><?=$row->request_id?>" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="icon-copy dw dw-print"></i>&nbsp;Print
                                                            </a>
                                                        <?php endif;?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="items" role="tabpanel">
                                    <div class="pd-10">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-stripe table-sm data-table" style="width:100%">
                                                <thead>
                                                    <th class="bg-primary text-white">Control #</th>
                                                    <th class="bg-primary text-white">Qty</th>
                                                    <th class="bg-primary text-white">Units</th>
                                                    <th class="bg-primary text-white">Description</th>
                                                    <th class="bg-primary text-white">Specification</th>
                                                </thead>
                                                <tbody>
                                                <?php foreach($items as $row): ?>
                                                <tr>
                                                    <td><?=$row->control_number ?></td>
                                                    <td><?=$row->qty ?></td>
                                                    <td><?=$row->unit ?></td>
                                                    <td><?=$row->description ?></td>
                                                    <td><?=$row->specification ?></td>
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

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Upload Attachment
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="row g-3" id="form1" enctype="multipart/form-data">
                        <?=csrf_field()?>
                        <input type="hidden" name="request_id" id="request_id"/>
                        <div class="col-lg-12 form-group">
                            <label>Attachment</label>
                            <input type="file" class="form-control" name="file" required/>
                            <div id="file-error" class="error-message text-danger text-sm"></div>
                            <div id="request_id-error" class="error-message text-danger text-sm"></div>
                        </div>
                        <div class="col-lg-12 form-group">
                            <button type="submit" class="btn btn-primary form-control text-white">Upload File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?=view('templates/script') ?>
    <script>
        $(document).on('click','.upload',function()
        {
            $('#request_id').attr("value",$(this).val());
            $('#uploadModal').modal('show');
        });

        $('#form1').on('submit', function(e) {
            e.preventDefault();
             $('.error-message').html('');
            $.ajax({
                url: "<?=site_url('upload-file')?>",
                method: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        $('#form1')[0].reset();
                        $('#uploadModal').modal('hide');
                        Swal.fire({
                            title: "Great!",
                            text: "Successfully submitted",
                            icon: "success"
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
    