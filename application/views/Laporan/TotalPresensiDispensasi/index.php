
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header text-center">
        <h4>Laporan Total Presensi Dispensasi</h4>
      </div>
      <div class="card-body row">
        <div class="col-12">
          <h4>PENCARIAN DATA :</h4>
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
          <select id="sub_unit" class="form-control select2 col-md-12" required onchange="search()">
            <option value="">Semua Sub Unit</option>
          </select>
        </div>
        <div class="col-md-4">

        </div>
        <div class="col-md-3">
          <label>Tipe Pegawai :</label>
          <select id="tipe_pegawai" class="form-control select2 col-md-12" required onchange="search()">
            <option value="">Semua Tipe Pegawai</option>
            <?php foreach ($tipe as $value): ?>
              <option value="<?php echo $value->tipe_pegawai ?>"><?php echo $value->tipe_pegawai ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label>Jabatan :</label>
          <select id="jabatan" class="form-control select2 col-md-12" required onchange="search()">
            <option value="">Semua Jabatan</option>
            <?php foreach ($jabatan as $value): ?>
              <option value="<?php echo $value->namajabatan ?>"><?php echo $value->namajabatan ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label>Menurut Tanggal :</label>
          <div class="input-daterange input-group" id="date-range">
            <input type="text" class="form-control" name="start" id="start" value="<?php echo date("01-m-Y") ?>" readonly/>
            <div class="input-group-append">
              <span class="input-group-text bg-info b-0 text-white">S/D</span>
            </div>
            <input type="text" class="form-control" name="end" id="end" value="<?php echo date("d-m-Y") ?>" readonly/>
          </div>
        </div>
        <div class="col-md-2">
          <br>
          <button type="button" class="btn btn-info btn-md" onclick="search()"> <i class="fa fa-search"></i> Cari</button>
        </div>
        <div class="col-12">
          <hr>
          <h4>FORM PRINT : </h4>
          <div class="loader__figure" hidden="true"></div>

        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Lampiran :</label>
            <input type="text" name="lampiran" id="lampiran" class="form-control">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Nomor :</label>
            <input type="text" name="nomor" id="nomor" class="form-control">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Tanggal :</label>
            <input type="text" name="tgl_surat" id="tgl_surat" class="form-control">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Bulan :</label>
            <input type="text" name="bulan_surat" id="bulan_surat" class="form-control">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Jenis Pegawai :</label>
            <input type="text" name="jenis_pegawai" id="jenis_pegawai" class="form-control">
          </div>
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
    var tipe_pegawai  = $('#tipe_pegawai').val();
    var jabatan  = $('#jabatan').val();
    // alert(start+" - "+end);
    $.ajax({
      type: "POST",
      url: "<?php echo base_url();?>Laporan/tabelTotalPresensiDispensasi",
      data: {start: start, end:end, unit:unit, sub_unit:sub_unit, tipe_pegawai:tipe_pegawai, jabatan:jabatan},
      beforeSend: function(){
        $('.loader__figure').attr("hidden",false);
        $('.hasilSearch').attr("hidden",true);
      },
      success: function(data){
        $('.loader__figure').attr("hidden",true);
        $('.hasilSearch').attr("hidden",false);
        $('.hasilSearch').html(data);
        $('#table-print').DataTable({
          dom: 'Bfrtip',
          buttons: ['excel'],
        })
        // alert(data);  //as a debugging message.
      },
      error: function(e) {
        $('.loader__figure').attr("hidden",true);
        alert(e);
      },
    });

  }

</script>
