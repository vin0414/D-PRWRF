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
                        <div class="card-title">New D/PRWRF</div>
                        <form method="POST" class="row g-1" id="form1">
                            <div class="col-lg-12 form-group">
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <label class="form-label">Vessel/Port</label>
                                        <select name="from" class="custom-select2 form-control" required>
                                            <option value="">Choose</option>
                                            <?php foreach($office as $row): ?>
                                                <option value="<?=$row->warehouseID ?>"><?=$row->warehouseName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div id="from-error" class="error-message text-danger text-sm"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label">Date Prepared</label>
                                        <input type="date" class="form-control" name="date_prepared" id="date_prepared" value="<?=date('Y-m-d')?>" required/>
                                        <div id="date_prepared-error" class="error-message text-danger text-sm"></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label">Date Needed</label>
                                        <input type="date" class="form-control" name="date_needed" id="date_needed" required/>
                                        <div id="date_needed-error" class="error-message text-danger text-sm"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Equipment Name/Issue</label>
                                <input type="text" class="form-control" name="equipment_name" required/>
                                <div id="equipment_name-error" class="error-message text-danger text-sm"></div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">I. Equipment Details</label>
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-lg-12 form-group">
                                                <input type="text" class="form-control" name="model" placeholder="Model" required/>
                                                <div id="model-error" class="error-message text-danger text-sm"></div>
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <input type="text" class="form-control" name="maker" placeholder="Maker" required/>
                                                <div id="maker-error" class="error-message text-danger text-sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-lg-12 form-group">
                                                <input type="text" class="form-control" name="brand" placeholder="Brand" required/>
                                                <div id="brand-error" class="error-message text-danger text-sm"></div>
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <input type="text" class="form-control" name="serial_number" placeholder="Serial/Part Number" required/>
                                                <div id="serial_number-error" class="error-message text-danger text-sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-lg-12 form-group">
                                                <input type="text" class="form-control" name="size" placeholder="Size" required/>
                                                <div id="size-error" class="error-message text-danger text-sm"></div>
                                            </div>
                                            <div class="col-lg-12 form-group">
                                                <input type="text" class="form-control" name="dimension" placeholder="Dimension" required/>
                                                <div id="dimension-error" class="error-message text-danger text-sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">II. Equipment Defect/Problem</label>
                                <div class="row g-3">
                                    <div class="col-lg-6 form-group">
                                        <textarea name="description" class="form-control" placeholder="Description" required></textarea>
                                        <div id="description-error" class="error-message text-danger text-sm"></div>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <textarea name="cause" class="form-control" placeholder="Immediate Cause" required></textarea>
                                        <div id="cause-error" class="error-message text-danger text-sm"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Materials Needed</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <th class="bg-primary text-white">Qty</th>
                                            <th class="bg-primary text-white">Unit</th>
                                            <th class="bg-primary text-white">Description</th>
                                            <th class="bg-primary text-white">Specification</th>
                                            <th class="bg-primary text-white">Action</th>
                                        </thead>
                                        <tbody id="Table">
                                        <tr>
                                            <td><input type='text' class='form-control' id='qty' name='qty[]' required/></td>
                                            <td><input type='text' class='form-control' id='unit' name='unit[]' required/></td>
                                            <td><input type='text' class='form-control' id='description' name='description[]' required/></td>
                                            <td><input type='text' class='form-control' id='specification' name='specification[]' required/></td>
                                            <td><button type='button' class='btn btn-danger btnDelete'><span class='dw dw-trash'></span></button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm" onclick="addRow()">
            						<span class="icon-copy dw dw-add"></span>&nbsp;Add Item(s)
            					</a>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">III. Work Required</label>
                                <textarea name="work" class="form-control" required></textarea>
                                <div id="work-error" class="error-message text-danger text-sm"></div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label class="form-label">Approver</label>
                                <select name="approver" class="custom-select2 form-control" required>
                                    <option value="">Choose</option>
                                    <?php foreach($editor as $row): ?>
                                        <option value="<?php echo $row->accountID ?>"><?php echo $row->Fullname ?> - <?php echo $row->Department ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="approver-error" class="error-message text-danger text-sm"></div>
                            </div>
                            <div class="col-lg-12 form-group">
                                <button type="submit" class="btn btn-primary"><i class="icon-copy bi bi-send"></i>&nbsp;Submit Form</button>
                            </div>
                            <?=csrf_field()?>
                            <div id="csrf_fastcat-error" class="error-message text-danger text-sm"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?=view('templates/script') ?>
    <script>
        $("#Table").on('click','.btnDelete',function(){
            $(this).closest('tr').remove();
        });
        $(function() {
            var date = new Date();
            var startDate = new Date(); 
            startDate = new Date(startDate);
            var endDate = "", count = 0;
            var noOfDaysToAdd = 3;
            while(count < noOfDaysToAdd){
                endDate = new Date(startDate.setDate(startDate.getDate() + 1));
                if(endDate.getDay() != 0 && endDate.getDay() != 6){
                //Date.getDay() gives weekday starting from 0(Sunday) to 6(Saturday)
                count++;
                }
            }
			$('#date_needed').val(convert(endDate));
			$('#date_needed').attr('min',convert(endDate));
            $('#date_prepared').attr('min',convert(date));
        });

        function convert(str) {
            var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        }
        function addRow() {
            var table = document.getElementById("Table");	
            var row = table.insertRow(0);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            cell1.innerHTML = "<input type='text' class='form-control' id='qty' name='qty[]'/>";
            cell2.innerHTML = "<input type='text' class='form-control' id='unit' name='unit[]'/>";
            cell3.innerHTML = "<input type='text' class='form-control' id='description' name='description[]'/>";
            cell4.innerHTML = "<input type='text' class='form-control' id='specification' name='specification[]'/>";
            cell5.innerHTML = "<button type='button' class='btn btn-danger btnDelete'><span class='dw dw-trash'></span></button>";
        }
        $('#form1').on('submit', function(e) {
            e.preventDefault();
            $('.error-message').html('');
            let data = $(this).serialize();
            $.ajax({
                url: window.location.origin + "/save-request",
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
                                $('#form1')[0].reset();
                                // Perform some action when "Yes" is clicked
                                location.reload();
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
    