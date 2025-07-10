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
                <div class="card card-box">
                    <div class="card-body">
                        <div class="card-title">D/PRWRF Monitoring</div>
                        <div class="row g-3">
                            <div class="col-lg-12 form-group">
                                <form method="GET" class="row g-3" id="form">
                                    <div class="col-lg-4">
                                        <select name="from" class="custom-select2 form-control" required>
                                            <option value="">Choose</option>
                                            <?php foreach($office as $row): ?>
                                                <option value="<?=$row->warehouseID ?>"><?=$row->warehouseName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <button type="submit" class="btn btn-primary" id="btnGenerate">
                                            <i class="icon-copy dw dw-refresh"></i>&nbsp;Generate
                                        </button>
                                        <a href="javascript:void(0);" onclick="exportFile(this)" class="btn btn-secondary" id="btnDownload">
                                            <i class="icon-copy dw dw-download"></i>&nbsp;Download
                                        </a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 form-group">
                                <table class="table table-bordered table-sm" id="table">
                                    <thead>
                                        <th class="bg-primary text-white">Control Number</th>
                                        <th class="bg-primary text-white">Equipment Name/Issues</th>
                                        <th class="bg-primary text-white">Problem</th>
                                        <th class="bg-primary text-white">Immediate Cause</th>
                                        <th class="bg-primary text-white">Status</th>
                                    </thead>
                                    <tbody id="result">
                                        <tr><td colspan="5" class="text-center">No Record(s) found</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
    <?=view('templates/script') ?>
    <script>
        $('#form').on('submit',function(e){
           e.preventDefault();
           $('#result').html("<tr><td colspan='5' class='text-center'>Loading..</td></tr>"); 
           let data = $(this).serialize();
           $.ajax({
            url:window.location.origin + "/fetch-report",
            method:"GET",data:data,
            success:function(response)
            {
                if(response === "")
                {
                    $('#result').html("<tr><td colspan='5' class='text-center'>No Record(s) found</td></tr>"); 
                }
                else
                {
                    $('#result').html(response); 
                }
            }
           });
        });

        function exportFile(elem) {
			var table = document.getElementById("table");
			var html = table.outerHTML;
			var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url 
			elem.setAttribute("href", url);
			elem.setAttribute("download","d-prwrf-monitoring.xls"); // Choose the file name
			return false;
		}
    </script>
<?=view('templates/footer') ?>    
    