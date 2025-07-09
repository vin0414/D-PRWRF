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
                                    <a href="<?=site_url('dashboard')?>">D/PRWRF</a>
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
                <div class="row g-3">
                    <div class="col-lg-12 form-group">
                        <div class="row g-3">
                            <div class="col-lg-3">
                                <div class="card card-box bg-primary text-white">
                                    <div class="card-body">
                                        <div class="card-title">Pending</div>
                                        <h1 class="text-center text-white"><?=$pending?></h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-box bg-primary text-white">
                                    <div class="card-body">
                                        <div class="card-title">Review</div>
                                        <h1 class="text-center text-white"><?=$review?></h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-box bg-primary text-white">
                                    <div class="card-body">
                                        <div class="card-title">Close</div>
                                        <h1 class="text-center text-white"><?=$close?></h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="card card-box bg-primary text-white">
                                    <div class="card-body">
                                        <div class="card-title">Total</div>
                                        <h1 class="text-center text-white"><?=$total?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 form-group">
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="card card-box">
                                    <div class="card-body">
                                        <div class="card-title">D/PRWRF Status</div>
                                        <div id="chartContainer" style="height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card card-box">
                                    <div class="card-body">
                                        <div class="card-title">Recent(s)</div>  
                                        <div class="list-group">
                                            <?php if(empty($list)): ?>
                                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <small>No Incoming Request(s)</small>
                                                </a>
                                            <?php else: ?>
                                                <?php foreach($list as $row): ?>
                                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                                    <h6 class="mb-1"><?=$row['control_number']?></h6>
                                                    <small><?=$row['equipment_name']?></small>
                                                </a>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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
    <?=view('templates/script') ?>
    <script>
    const rawData = <?= $chartData ?>;

    // Format data for Google Charts
    const chartArray = [
        ['Ports/Vessel', 'Open', 'Close', 'Total']
    ];

    rawData.forEach(row => {
        chartArray.push([
            row.warehouseName,
            parseInt(row.review),
            parseInt(row.close),
            parseInt(row.total)
        ]);
    });

    google.charts.setOnLoadCallback(requestCharts);

    function requestCharts() {
        var data = google.visualization.arrayToDataTable(chartArray);

        const options = {
            title: 'Request Summary by Ports/Vessel',
            hAxis: { title: 'Ports/Vessel' },
            vAxis: { title: 'D/PRWRF' },
            bars: 'vertical',
            isStacked: true,
            colors: ['#f4b400', '#0f9d58', '#3366cc']
        };

        const chart = new google.visualization.ColumnChart(document.getElementById('chartContainer'));
        chart.draw(data, options);
    }
    </script>
<?=view('templates/footer') ?>    
    