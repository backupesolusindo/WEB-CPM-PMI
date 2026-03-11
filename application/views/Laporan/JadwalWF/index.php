<link href="<?php echo base_url() ?>desain/assets/node_modules/calendar/dist/fullcalendar.css" rel="stylesheet" />
<style media="screen">
.fc .fc-content {
  background-color: #4fc3f773 !important;
  padding: 2px;
  color: #fff;
}
.fc-time{
  display: none;
}
</style>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header text-center">
        <h4>Laporan Jadwal Kerja Pegawai</h4>
      </div>
      <div class="card-body row">
        <div class="col-md-4">
          <label>Unit :</label>
          <select id="unit" class="form-control select2 col-md-12" required onchange="getPegawai()">
            <?php foreach ($unit as $value): ?>
              <option value="<?php echo $value->nama_unit; ?>"><?php echo $value->nama_unit ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-5">
          <label>Nama Pegawai :</label>
          <select id="pegawai" class="form-control select2 col-md-12" required onchange="search()">
            <option value=""></option>
          </select>
        </div>
        <div class="col-md-2">
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
        </div>
        <div class="col-12" id="hasilKalender">

        </div>
        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url() ?>desain/assets/node_modules/calendar/jquery-ui.min.js"></script>
<script src="<?php echo base_url() ?>desain/assets/node_modules/moment/moment.js"></script>
<script src='<?php echo base_url() ?>desain/assets/node_modules/calendar/dist/fullcalendar.min.js'></script>
<script type="text/javascript">
  $(document).ready(function(){
    getPegawai()
  });
  function search() {
    var pegawai  = $('#pegawai').val();
    // alert(start+" - "+end);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Laporan/CalendarJadwalWF",
      data: {pegawai:pegawai},
      success: function(data){
        $('#hasilKalender').html(data);
        // alert(data);  //as a debugging message.
      },
      error: function(e) {
        alert(e);
      },
    });
  }

  function getPegawai() {
    var unit  = $('#unit').val();
    // alert(start+" - "+end);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Laporan/getPegawai",
      data: {unit:unit},
      success: function(data){
        $('#pegawai').html(data);
        // alert(data);  //as a debugging message.
      },
      error: function(e) {
        alert(e);
      },
    });
  }
</script>
