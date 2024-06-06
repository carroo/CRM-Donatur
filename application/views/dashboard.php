<section class="section dashboard">
    <div class="row">
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card info-card kanal-donations-card">

                        <div class="card-body">
                            <h5 class="card-title">Donatur</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $kanal_donations ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pb-0">
                            <h5 class="card-title">Pelabelan Donatur <span></span></h5>



                            <div id="donaturChart" style="min-height: 400px;" class="echart"></div>

                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    echarts.init(document.querySelector("#donaturChart")).setOption({
                                        tooltip: {
                                            trigger: 'item'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center'
                                        },
                                        series: [{
                                            name: 'Access From',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            avoidLabelOverlap: false,
                                            label: {
                                                show: false,
                                                position: 'center'
                                            },
                                            emphasis: {
                                                label: {
                                                    show: true,
                                                    fontSize: '18',
                                                    fontWeight: 'bold'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: <?= $donaturChart ?>
                                        }]
                                    });
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Label</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-tag"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $labels ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card info-card kanal-donations-card">

                        <div class="card-body">
                            <h5 class="card-title">Kanal Donasi</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-heart"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $kanal_donations ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card info-card donation-types-card">

                        <div class="card-body">
                            <h5 class="card-title">Jenis Donasi</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-gift"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $donation_types ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card info-card categories-card">

                        <div class="card-body">
                            <h5 class="card-title">Kategori</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-list"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $categories ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card info-card payment-methods-card">

                        <div class="card-body">
                            <h5 class="card-title">Payment Method</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-wallet"></i>
                                </div>
                                <div class="ps-3">
                                    <h6><?= $payment_methods ?></h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pb-0">
                            <h5 class="card-title">Riwayat Kirim Pesan Pada Tahun Ini<span></span></h5>
                            <div id="historiesChart" style="min-height: 400px;" class="echart"></div>

                            <script>
                                var historyChart = <?php echo $historyChart; ?>;
                                document.addEventListener("DOMContentLoaded", () => {
                                    var options = {
                                        series: [{
                                                name: 'Success',
                                                data: historyChart.map(item => item.data.Success),
                                                color: '#198754'
                                            },
                                            {
                                                name: 'Fail',
                                                data: historyChart.map(item => item.data.Fail),
                                                color: '#dc3545'
                                            },
                                            {
                                                name: 'Pending',
                                                data: historyChart.map(item => item.data.Pending),
                                                color: '#ffc107'
                                            }
                                        ],
                                        chart: {
                                            type: 'bar',
                                            height: 350
                                        },
                                        plotOptions: {
                                            bar: {
                                                horizontal: false,
                                                columnWidth: '55%',
                                                endingShape: 'rounded'
                                            }
                                        },
                                        dataLabels: {
                                            enabled: false
                                        },
                                        stroke: {
                                            show: true,
                                            width: 2,
                                            colors: ['transparent']
                                        },
                                        xaxis: {
                                            categories: historyChart.map(item => item.bulan),
                                        },
                                        yaxis: {
                                            title: {
                                                text: ' Pesan'
                                            }
                                        },
                                        fill: {
                                            opacity: 1
                                        },
                                        tooltip: {
                                            y: {
                                                formatter: function(val) {
                                                    return val + " pesan"
                                                }
                                            }
                                        }
                                    };
                                    var chart = new ApexCharts(document.querySelector("#historiesChart"), options);
                                    chart.render();
                                });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>