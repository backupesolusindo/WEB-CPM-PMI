<h3 class="box-title"><b>Jadwal Masuk</b></h3>
<div class="row form-group">
  <div class="col-12">
    <label>Jabatan :</label>
    <select name="jabatan_idjabatan" id="select_jabatan" class="form-control select2 col-md-12" required>
      <option disabled selected> -- Pilih Jabatan -- </option>
      <option value="0">Semua Jabatan</option>
      <?php foreach ($jabatan as $value): ?>
        <?php if ($value->idjabatan != "adminr"): ?>
          <option value="<?php echo $value->idjabatan; ?>"
            <?php if ($value->idjabatan == @$jadwalmasuk['jabatan_idjabatan']): ?>selected<?php endif; ?>>
            <?php echo $value->namajabatan ?>
          </option>
        <?php endif; ?>
      <?php endforeach; ?>
    </select>
    <br>
  </div>

  <!-- Multi-select Pegawai Spesifik (opsional) -->
  <div class="col-12" id="wrap_pegawai_spesifik">
    <div class="form-group">
      <label>
        Pegawai Spesifik :
        <small class="text-muted">
          (opsional — kosongkan jika jadwal berlaku untuk semua pegawai jabatan ini)
        </small>
      </label>
      <select name="pegawai_uuid[]" id="select_pegawai" class="form-control select2 col-md-12" multiple>
        <?php if (!empty($pegawai_list)): ?>
          <?php foreach ($pegawai_list as $peg): ?>
            <option value="<?php echo $peg->uuid; ?>"
              <?php if (in_array($peg->uuid, (array)@$selected_pegawai)): ?>selected<?php endif; ?>>
              <?php echo $peg->nama_pegawai; ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
      <small class="text-muted">
        Pilih satu atau lebih pegawai. Jika tidak dipilih, jadwal berlaku untuk seluruh pegawai jabatan ini.
      </small>
    </div>
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
      <label>Batas Jam Masuk Minimal :</label>
      <input type="text" name="batas_absen" id="batas_absen" class="form-control waktu-input"
        placeholder="contoh: 10:00" value="<?php echo @$jadwalmasuk["batas_absen"] ?>" required>
      <small class="text-muted">Jam maksimal pegawai boleh melakukan absensi masuk</small>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Jam Masuk :</label>
      <input type="text" name="jam_masuk" id="masuk" class="form-control waktu-input"
        onblur="hitung()" value="<?php echo @$jadwalmasuk["jam_masuk"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Jam Pulang :</label>
      <input type="text" name="jam_pulang" id="pulang" class="form-control waktu-input"
        onblur="hitung()" value="<?php echo @$jadwalmasuk["jam_pulang"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Jenis Jadwal Masuk :</label>
      <select name="jenis" class="form-control select2 col-md-12" required>
        <option>...Pilih Jenis Jadwal...</option>
        <option value="1" <?php if (@$jadwalmasuk["jenis"] == 1): ?>selected<?php endif; ?>>WFO</option>
        <option value="2" <?php if (@$jadwalmasuk["jenis"] == 2): ?>selected<?php endif; ?>>WFH</option>
        <option value="3" <?php if (@$jadwalmasuk["jenis"] == 3): ?>selected<?php endif; ?>>Mobile Unit</option>
      </select>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Waktu Istirahat Keluar :</label>
      <input type="text" name="isti_keluar" id="isti_keluar" class="form-control waktu-input"
        value="<?php echo @$jadwalmasuk["isti_keluar"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Waktu Istirahat Masuk :</label>
      <input type="text" name="isti_masuk" id="isti_masuk" class="form-control waktu-input"
        value="<?php echo @$jadwalmasuk["isti_masuk"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Total Jam Kerja :</label>
      <input type="text" name="total_jamkerja" id="total_jamkerja" class="form-control"
        value="<?php echo @$jadwalmasuk["total_jamkerja"] ?>" readonly>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Toleransi Kedatangan :</label>
      <input type="text" name="toleransi_kedatangan" id="toleransi_kedatangan" class="form-control waktu-input"
        value="<?php echo @$jadwalmasuk["toleransi_kedatangan"] ?>" required>
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Toleransi Kepulangan :</label>
      <input type="text" name="toleransi_kepulangan" id="toleransi_kepulangan" class="form-control waktu-input"
        value="<?php echo @$jadwalmasuk["toleransi_kepulangan"] ?>" required>
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

<div class="form-actions">
  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Simpan</button>
  <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
</div>

<script type="text/javascript">
  function hitung() {
    var masuk = $('#masuk').val();
    var pulang = $('#pulang').val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>JadwalMasuk/perhitungan_jam",
      data: {
        masuk: masuk,
        pulang: pulang
      },
      success: function(data) {
        $('#total_jamkerja').val(data);
      },
      error: function(e) {
        alert(e);
      }
    });
  }

  $(document).ready(function() {
    // Inisialisasi select2 multi untuk pegawai
    $('#select_pegawai').select2({
      placeholder: "-- Pilih Pegawai (opsional) --",
      allowClear: true
    });

    // Load pegawai saat jabatan berubah
    $('#select_jabatan').on('change', function() {
      var idjabatan = $(this).val();
      var $wrap = $('#wrap_pegawai_spesifik');
      var $select = $('#select_pegawai');

      // Destroy select2 dulu sebelum update options
      if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
      }
      $select.html('');

      // if (!idjabatan || idjabatan === '...Pilih Jabatan...') {
      //   $wrap.hide();
      //   $select.select2({
      //     placeholder: "-- Pilih Pegawai (opsional) --",
      //     allowClear: true
      //   });
      //   return;
      // }

      $.ajax({
        url: '<?php echo base_url(); ?>JadwalMasuk/get_pegawai_by_jabatan',
        type: 'POST',
        data: {
          idjabatan: idjabatan
        },
        dataType: 'json',
        success: function(data) {
          if (data.length > 0) {
            $.each(data, function(i, peg) {
              $select.append('<option value="' + peg.uuid + '">' + peg.nama_pegawai + '</option>');
            });
            $wrap.show();
          } else {
            $wrap.hide();
          }
          $select.select2({
            placeholder: "-- Pilih Pegawai (opsional) --",
            allowClear: true
          });
        }
      });
    });
  });
</script>