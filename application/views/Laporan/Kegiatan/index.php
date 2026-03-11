
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header text-center">
        <h4>Laporan Presensi Kegiatan Pegawai</h4>
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
        <div class="col-md-3">
          <label>Unit :</label>
          <select id="unit" class="form-control select2 col-md-12" required onchange="sub_unit()">
            <option value="">Semua Unit</option>
            <?php foreach ($unit as $value): ?>
              <option value="<?php echo $value->nama_unit; ?>"><?php echo $value->nama_unit ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3" hidden>
          <label>Sub Unit :</label>
          <select id="sub_unit" class="form-control select2 col-md-12" required onchange="search()">
            <option value="">Semua Sub Unit</option>
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
        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</div>
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
    // alert(start+" - "+end);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Laporan/tabelKegiatan",
      data: {start: start, end:end, unit:unit, sub_unit:sub_unit},
      success: function(data){
        $('.hasilSearch').html(data);
        $('#myTable').DataTable();
        // alert(data);  //as a debugging message.
      },
      error: function(e) {
        alert(e);
      },
    });

  }
</script>
