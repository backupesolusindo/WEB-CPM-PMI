<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />

<div class="row">
    <!-- Column -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Pemesanan Hari Ini</small>
                        <h2>
                          <!-- <i class="ti-arrow-up text-success"></i>  -->
                          <?php echo $jmlPemesanan ?></h2>
                        <div id="sparklinedash"></div>
                    </div>
                    <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Transaksi Hari Ini</small>
                        <h2>
                          <!-- <i class="ti-arrow-up text-purple"></i>  -->
                          <?php echo $jmlTransaksi?></h2>
                        <div id="sparklinedash2"></div>
                    </div>
                    <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Menu Terjual Hari Ini</small>
                        <h2>
                          <!-- <i class="ti-arrow-up text-info"></i>  -->
                          <?php echo $jmlBarangTerjual ?></h2>
                        <div id="sparklinedash3"></div>
                    </div>
                    <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Pendapatan Cash Hari Ini</small>
                        <h2>
                          <!-- <i class="ti-arrow-down text-danger"></i>  -->
                          <?php echo $this->kasir->MataUangRP($pendapatan); ?></h2>
                        <div id="sparklinedash4"></div>
                    </div>
                </div>
                <ul class="list-inline font-12 text-center">
                    <li><i class="fa fa-circle text-cyan"></i> Pendapatan Harian</li>
                    <!-- <li><i class="fa fa-circle text-primary"></i> Site B</li>
                    <li><i class="fa fa-circle text-purple"></i> Site C</li> -->
                </ul>
                <div id="Pendapatan" style="height: 340px;"></div>
                <br>
                <br>
                <h4 class="text-center">GRAFIK 10 MENU TERJUAL TERTINGGI</h4>
                <canvas id="myChart" width="100%" height="50px"></canvas>

            </div>
        </div>
    </div>
    <!-- Column -->
</div>
<!-- ============================================================== -->

<script type="text/javascript">

$(function () {
    "use strict";
    Morris.Area({
        element: 'Pendapatan',
        data: [
                <?php foreach ($grafikPendapatan as $value): ?>
                  {
                    period: '<?php echo date("d-m-Y", strtotime($value->tgl)) ?>',
                    iphone: <?php echo $value->totalSemua ?>,
                  },
                <?php endforeach; ?>
                ],
                lineColors: ['#01c0c8'],
                xkey: 'period',
                ykeys: ['iphone'],
                labels: ['Pendapatan Rp'],
                pointSize: 0,
                lineWidth: 0,
                resize:true,
                fillOpacity: 0.8,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'

    });
});

var sparklineLogin = function() {
        $('#sparklinedash').sparkline([
          0, 5, 6, 10, 9, 12, 4, 9, 12, 10, 9
        ], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '10',
            barColor: '#4caf50'
        });
         $('#sparklinedash2').sparkline([ 0, 5, 6, 10, 9, 12, 4, 9, 12, 10, 9], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '10',
            barColor: '#9675ce'
        });
          $('#sparklinedash3').sparkline([ 0, 5, 6, 10, 9, 12, 4, 9, 12, 10, 9], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '10',
            barColor: '#03a9f3'
        });
           $('#sparklinedash4').sparkline([ 0, 5, 6, 10, 9, 12, 4, 9, 12, 10, 9], {
            type: 'bar',
            height: '30',
            barWidth: '4',
            resize: true,
            barSpacing: '10',
            barColor: '#f96262'
        });

   }
    var sparkResize;

        $(window).resize(function(e) {
            clearTimeout(sparkResize);
            sparkResize = setTimeout(sparklineLogin, 500);
        });
        sparklineLogin();

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.js" integrity="sha512-zO8oeHCxetPn1Hd9PdDleg5Tw1bAaP0YmNvPY8CwcRyUk7d7/+nyElmFrB6f7vg4f7Fv4sui1mcep8RIEShczg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />

<script type="text/javascript">
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'horizontalBar',
    data: {
        labels: [
          <?php foreach ($transaksi as $value): ?>
            "<?php echo $value->nama_menu; ?>",
          <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Grafik',
            data: [
              <?php foreach ($transaksi as $value): ?>
                <?php echo $value->jumlah; ?>,
              <?php endforeach; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
