
<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">Laporan Presensi</h3>
        <div>
          <?php if ($_SESSION['jabatan'] == "adminr" || $_SESSION['jabatan'] == "admin"): ?>
            <a href="<?php echo base_url(); ?>Absensi/input" class="float-right">
              <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
            </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body row">
        <div class="col-md-4">
          <label>Menurut Tanggal :</label>
          <div class="input-daterange input-group" id="date-range">
            <input type="text" class="form-control" name="start" id="start" value="<?php echo date("d-m-Y") ?>" readonly/>
            <div class="input-group-append">
              <span class="input-group-text bg-info b-0 text-white">S/D</span>
            </div>
            <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly/>
          </div>
          <br>
          <br>
        </div>
        <div class="col-md-2">
          <label>Unit :</label>
          <select id="unit" class="form-control select2 col-md-12" required onchange="sub_unit()">
            <option value="">Semua Unit</option>
            <?php foreach ($unit as $value): ?>
              <option value="<?php echo $value->nama_unit; ?>"><?php echo $value->nama_unit ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label>Sub Unit :</label>
          <select id="sub_unit" class="form-control select2 col-md-12" required onchange="search()">
            <option value="">Semua Sub Unit</option>
          </select>
        </div>
        <div class="col-md-2">
          <label>Status Datang :</label>
          <select id="status" class="form-control select2 col-md-12" required onchange="search()">
            <option value="">Semua Presensi</option>
            <option value="1">Tepat Waktu</option>
            <option value="2">Toleransi</option>
            <option value="3">Terlambat</option>
          </select>
        </div>
        <div class="col-md-2">
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
        </div>
        <div class="col-12">
          <div class="table-responsive hasilSearch">

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url() ?>/desain/dist/js/pages/jquery.PrintArea.js" type="text/JavaScript"></script>
<script type="text/javascript">
$(document).ready(function(){
  search();
});

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

function search() {
  var start  = $('#start').val();
  var end  = $('#end').val();
  var unit  = $('#unit').val();
  var sub_unit  = $('#sub_unit').val();
  var status  = $('#status').val();
  $.ajax({
    type: "POST",
    url: "<?php echo base_url();?>Laporan/tabelPresensi",
    data: {start: start, end:end, unit:unit, sub_unit:sub_unit, status:status},
    success: function(data){
      $('.hasilSearch').html(data);
      $('#table-print').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel'],
      });
      // alert(data);  //as a debugging message.
    },
    error: function(e) {
      alert(e);
    },
  });

}
</script>
