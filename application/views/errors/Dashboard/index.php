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
                    <div class="col-lg-3 col-md-6 m-b-30 text-center"> <small>Barang Keluar Hari Ini</small>
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
                    <li><i class="fa fa-circle text-cyan"></i> Site A</li>
                    <li><i class="fa fa-circle text-primary"></i> Site B</li>
                    <li><i class="fa fa-circle text-purple"></i> Site C</li>
                </ul>
                <div id="Pendapatan" style="height: 340px;"></div>
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
                    period: '<?php echo $value->tgl ?>',
                    iphone: <?php echo $value->totalSemua ?>,
                  },
                <?php endforeach; ?>
                ],
                lineColors: ['#01c0c8'],
                xkey: 'period',
                ykeys: ['iphone'],
                labels: ['Site A'],
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
