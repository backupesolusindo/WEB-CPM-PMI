<h3 class="box-title"><b>Jadwal Masuk</b></h3>
<div class="row form-group">
  <div class="col-12">
    <label>Jabatan :</label>
    <select name="jabatan_idjabatan" id="select" class="form-control select2 col-md-12" required>
      <option>...Pilih Jabatan...</option>
      <?php foreach ($jabatan as $value): ?>
        <?php if ($value->idjabatan != "adminr"): ?>
          <option value="<?php echo $value->idjabatan; ?>" <?php if ($value->idjabatan == @$jadwalmasuk['jabatan_idjabatan']): ?>
            <?php echo 'selected'; ?>
          <?php endif; ?>><?php echo $value->namajabatan ?></option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
    <br>
    <br>
  </div>

  <div class="col-md-12">
    <div class="form-group">
      <label>Nama Jadwal :</label>
      <input type="text" name="nama" id="nama" class="form-control" value="<?php echo @$jadwalmasuk["nama"] ?>" required>
    </div>
  </div>


  <div class="col-md-4">
    <div class="form-group">
      <label>Jam Masuk :</label>
      <input type="text" name="jam_masuk" id="masuk" class="form-control waktu-input" onblur="hitung()" value="<?php echo @$jadwalmasuk["jam_masuk"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Jam Pulang :</label>
      <input type="text" name="jam_pulang" id="pulang" class="form-control waktu-input" onblur="hitung()" value="<?php echo @$jadwalmasuk["jam_pulang"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Jenis Jadwal Masuk :</label>
      <select name="jenis" class="form-control select2 col-md-12" required>
        <option>...Pilih Jenis Jadwal...</option>
        <option value="1" <?php if (@$jadwalmasuk["jenis"] == 1): ?>selected<?php endif; ?> >WFO</option>
        <option value="2" <?php if (@$jadwalmasuk["jenis"] == 2): ?>selected<?php endif; ?> >WFH</option>
      </select>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Waktu Istirahat Keluar :</label>
      <input type="text" name="isti_keluar" id="isti_keluar" class="form-control waktu-input" value="<?php echo @$jadwalmasuk["isti_keluar"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Waktu Istirahat Masuk :</label>
      <input type="text" name="isti_masuk" id="isti_masuk" class="form-control waktu-input" value="<?php echo @$jadwalmasuk["isti_masuk"] ?>" required>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Total Jam Kerja :</label>
      <input type="text" name="total_jamkerja" id="total_jamkerja" class="form-control" value="<?php echo @$jadwalmasuk["total_jamkerja"] ?>" readonly>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Toleransi Kedatangan :</label>
      <input type="text" name="toleransi_kedatangan" id="toleransi_kedatangan" class="form-control waktu-input" value="<?php echo @$jadwalmasuk["toleransi_kedatangan"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Toleransi Kepulangan :</label>
      <input type="text" name="toleransi_kepulangan" id="toleransi_kepulangan" class="form-control waktu-input" value="<?php echo @$jadwalmasuk["toleransi_kepulangan"] ?>" required>
    </div>
  </div>
  <div class="col-sm-12 row">
    <label class="col-12">Hari Kerja : </label>
    <div class="custom-control custom-checkbox col-3">
      <input type="checkbox" class="custom-control-input" name="hari" id="Mon" value="Mon">
      <label class="custom-control-label" for="Mon">Senin</label>
    </div>
    <div class="custom-control custom-checkbox col-3">
      <input type="checkbox" class="custom-control-input" name="hari" id="Tue" value="Tue">
      <label class="custom-control-label" for="Tue">Selasa</label>
    </div>
    <div class="custom-control custom-checkbox col-3">
      <input type="checkbox" class="custom-control-input" name="hari" id="Wed" value="Wed">
      <label class="custom-control-label" for="Wed">Rabu</label>
    </div>
    <div class="custom-control custom-checkbox col-3">
      <input type="checkbox" class="custom-control-input" name="hari" id="Thu" value="Thu">
      <label class="custom-control-label" for="Thu">Kamis</label>
    </div>
    <div class="custom-control custom-checkbox col-3">
      <input type="checkbox" class="custom-control-input" name="hari" id="Fri" value="Fri">
      <label class="custom-control-label" for="Fri">Jum'at</label>
    </div>
    <div class="custom-control custom-checkbox col-3">
      <input type="checkbox" class="custom-control-input" name="hari" id="Sat" value="Sat">
      <label class="custom-control-label" for="Sat">Sabtu</label>
    </div>
    <div class="custom-control custom-checkbox col-3">
      <input type="checkbox" class="custom-control-input" name="hari" id="Sun" value="Sun">
      <label class="custom-control-label" for="Sun">Minggu</label>
    </div>
  </div>

  <div class="col-md-12" hidden>
    <div class="form-group">
      <label>Jumlah WFH :</label>
      <input type="text" name="jml_wfh" id="jml_wfh" class="form-control" value="<?php echo @$jadwalmasuk["jml_wfh"] ?>">
    </div>
  </div>

  <div class="col-md-12" hidden>
    <div class="form-group">
      <label>Jumlah WFO :</label>
      <input type="text" name="jml_wfo" id="jml_wfo" class="form-control" value="<?php echo @$jadwalmasuk["jml_wfo"] ?>">
    </div>
  </div>
</div>
<div class="form-actions" >
  <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
  <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
</div>

<script type="text/javascript">
function hitung() {
  var masuk  = $('#masuk').val();
  var pulang  = $('#pulang').val();
  $.ajax({
    type: "POST",
    url: "<?php echo base_url();?>JadwalMasuk/perhitungan_jam",
    data: {masuk: masuk, pulang:pulang},
    success: function(data){
      $('#total_jamkerja').val(data);
      // alert(data);  //as a debugging message.
    },
    error: function(e) {
      alert(e);
    },
  });

}
</script>
