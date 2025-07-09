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
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-12 form-group">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Control Number</th>
                                        <th>Port/Vessel</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>   
    </div>
    <?=view('templates/script') ?>
<?=view('templates/footer') ?>    
    