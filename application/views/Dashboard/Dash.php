<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.css" integrity="sha512-C7hOmCgGzihKXzyPU/z4nv97W0d9bv4ALuuEbSf6hm93myico9qa0hv4dODThvCsqQUmKmLcJmlpRmCaApr83g==" crossorigin="anonymous" />
<div class="row">
  <!-- column -->
  <div class="col-lg-12 ">
    <div class="card">
      <div class="card-body row">
        <div class="col-md-4">
          <label>Menurut Tanggal : </label>
          <div class="input-daterange input-group" id="date-range">
            <input type="text" class="form-control" name="start" id="start" value="<?php echo '01'.date("-m-Y") ?>" readonly/>
            <div class="input-group-append">
              <span class="input-group-text bg-info b-0 text-white">S/D</span>
            </div>
            <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly/>
          </div>
          <br>
        </div>
        <div class="col-md-3">
          <label>Unit :</label>
          <select id="unit" class="form-control select2 col-md-12" required onchange="sub_unit()">
            <option value="">Semua Unit</option>
            <?php foreach ($unit as $value): ?>
              <option value="<?php echo $value->nama_unit; ?>"><?php echo $value->nama_unit ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label>Sub Unit :</label>
          <select id="sub_unit" class="form-control select2 col-md-12" required onchange="cari()">
            <option value="">Semua Sub Unit</option>
          </select>
        </div>
        <div class="col-md-2">
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="cari()"> <i class="fa fa-search"></i> Cari</button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6 row">
    <div class="col-md-4">
      <div class="card bg-success z-depth-2" style="min-height:140px;">
        <div class="row mt-3">
          <div class="col-md-12 col-12 text-right pr-5">
            <h3 class="ml-4 mt-4 mb-2 font-weight-bold white-text txtTW">0</h3>
            <p class="font-small white-text font-weight-bold">Jumlah Tepat Waktu</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-warning z-depth-2" style="min-height:140px;">
        <div class="row mt-3">
          <div class="col-md-12 col-12 text-right pr-5">
            <h3 class="ml-4 mt-4 mb-2 font-weight-bold white-text txtTO">0</h3>
            <p class="font-small white-text font-weight-bold">Jumlah Toleransi</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card bg-danger z-depth-2" style="min-height:140px;">
        <div class="row mt-3">
          <div class="col-md-12 col-12 text-right pr-5">
            <h3 class="ml-4 mt-4 mb-2 font-weight-bold white-text txtTE">0</h3>
            <p class="font-small white-text font-weight-bold">Jumlah Terlambat</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card aqua-gradient z-depth-2" style="min-height:140px;">
        <div class="row mt-3">
          <div class="col-md-5 col-5 text-left pl-4">
            <a type="button" href="#" class="btn-floating btn-lg green ml-4"><i class="fas fa-envelope-open"></i></a>
          </div>
          <div class="col-md-7 col-7 text-right pr-5">
            <h3 class="ml-4 mt-4 mb-2 font-weight-bold white-text txtCuti">0</h3>
            <p class="font-small white-text font-weight-bold">Jumlah Cuti</p>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card blue-gradient z-depth-2" style="min-height:140px;">
        <div class="row mt-3">
          <div class="col-md-5 col-5 text-left pl-4">
            <a type="button" href="#" class="btn-floating btn-lg info-color accent-2 ml-4"><i class="fas fa-person-booth"></i></a>
          </div>
          <div class="col-md-7 col-7 text-right pr-5">
            <h3 class="ml-4 mt-4 mb-2 font-weight-bold white-text txtKegiatan">0</h3>
            <p class="font-small white-text font-weight-bold">Jumlah Kegiatan</p>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="col-md-12">
      <div class="card morpheus-den-gradient z-depth-2" style="min-height:140px;">
        <div class="row mt-3">
          <div class="col-md-5 col-5 text-left pl-4">
            <a type="button" href="#" class="btn-floating btn-lg primary-color-dark accent-2 ml-4"><i class="fas fa-home"></i></a>
          </div>
          <div class="col-md-7 col-7 text-right pr-5">
            <h3 class="ml-4 mt-4 mb-2 font-weight-bold white-text txtWFH">0</h3>
            <p class="font-small white-text font-weight-bold">Jumlah Presensi WFH</p>
          </div>
        </div>
      </div>
    </div> -->
    <div class="col-md-12">
      <div class="card purple-gradient z-depth-2" style="min-height:140px;">
        <div class="row mt-3">
          <div class="col-md-5 col-5 text-left pl-4">
            <a type="button" href="#" class="btn-floating btn-lg rgba-red-strong accent-2 ml-4"><i class="fas fa-business-time"></i></a>
          </div>
          <div class="col-md-7 col-7 text-right pr-5">
            <h3 class="ml-4 mt-4 mb-2 font-weight-bold white-text txtWFO">0</h3>
            <p class="font-small white-text font-weight-bold">Total Jumlah Presensi</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Persentase Ketepatan Waktu %</h4>
        <div id="morris-donut-chart"></div>
      </div>
    </div>
  </div>
  <!-- column -->
  <!-- column -->
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Grafik Ketepatan Waktu</h4>
        <ul class="list-inline text-center m-t-40">
          <li>
            <h5><i class="fa fa-circle m-r-5 text-success"></i>Tepat Waktu</h5>
          </li>
          <li>
            <h5><i class="fa fa-circle m-r-5 text-warning"></i>Toleransi</h5>
          </li>
          <li>
            <h5><i class="fa fa-circle m-r-5 text-danger"></i>Terlambat</h5>
          </li>
        </ul>
        <div id="extra-area-chart"></div>
      </div>
    </div>
  </div>
  <!-- column -->
  <!-- Column -->
</div>
<!-- ============================================================== -->
<script src="<?php echo base_url() ?>desain/assets/node_modules/raphael/raphael-min.js"></script>
<script src="<?php echo base_url() ?>desain/assets/node_modules/morrisjs/morris.js"></script>
<script type="text/javascript">

  $(document).ready(function(){
    bulanan();
    grafik();
    info()
  });

  function cari(){
    bulanan();
    grafik();
    info()
  }

  function sub_unit() {
    var unit = $("#unit").val();
    // alert(unit);
    $.ajax({
        type  : 'POST',
        url   : '<?php echo base_url() ?>Laporan/sub_unit',
        data  : {unit:unit},
        success : function(response){
          // alert(response);
          $("#sub_unit").html(response);
        }
    });
  }

  function info() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var unit  = $('#unit').val();
    var sub_unit  = $('#sub_unit').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Dashboard/infoBulanan",
      data: {start: start, end:end, unit:unit, sub_unit:sub_unit},
      dataType: "json",
      success: function(data){
        $(".txtCuti").html(data.cuti);
        $(".txtKegiatan").html(data.kegiatan);
        $(".txtWFH").html(data.wfh);
        $(".txtWFO").html(data.wfo);
      },
      error: function(e) {
        alert("Info"+e);
      },
    });
  }

  function bulanan() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var unit  = $('#unit').val();
    var sub_unit  = $('#sub_unit').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Dashboard/chartbulanan",
      data: {start: start, end:end, unit:unit, sub_unit:sub_unit},
      dataType: "json",
      success: function(data){
        $(".txtTW").html(data.tepat);
        $(".txtTO").html(data.toleransi);
        $(".txtTE").html(data.terlambat);
        var total = data.tepat + data.toleransi + data.terlambat;
        var ptepat = data.tepat / total * 100;
        var ptoleransi = data.toleransi / total * 100;
        var pterlambat = data.terlambat / total * 100;
        $("#morris-donut-chart").html("");
        Morris.Donut({
          element: 'morris-donut-chart',
          data: [{
            label: "Tepat Waktu %",
            value: Math.round(ptepat)

          }, {
            label: "Toleransi %",
            value: Math.round(ptoleransi)
          }, {
            label: "Terlambat %",
            value: Math.round(pterlambat)
          }],
          resize: true,
          colors:['#02c292', '#fec107', '#e46a76']
        });
      },
      error: function(e) {
        alert("Dashboard"+e);
      },
    });
  }

  function grafik() {
    var start  = $('#start').val();
    var end  = $('#end').val();
    var unit  = $('#unit').val();
    var sub_unit  = $('#sub_unit').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Dashboard/grafik",
      data: {start: start, end:end, unit:unit, sub_unit:sub_unit},
      dataType: "json",
      success: function(data){
        $("#extra-area-chart").html("");

        Morris.Area({
          element: 'extra-area-chart',
          data: data,
          lineColors: ['#02c292', '#fec107', '#e46a76'],
          xkey: 'tanggal',
          ykeys: ['tepat', 'toleransi', 'terlambat'],
          labels: ['Tepat Waktu', 'Toleransi', 'Terlambat'],
          pointSize: 0,
          lineWidth: 0,
          resize:true,
          fillOpacity: 0.8,
          behaveLikeLine: true,
          gridLineColor: '#e0e0e0',
          hideHover: 'auto'

        });
      },
      error: function(e) {
        alert("Grafiks"+e);
      },
    });
  }
</script>
