<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Input izin Peserta </h3>
        <div>

        </div>
      </div>
      <div class="card-body">
        <?php echo form_open_multipart('CutiPegawai/insert');?>
        <br>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Peserta :</label>
              <select name="pegawai_uuid" class="form-control select2 col-md-12" required>
                <?php foreach ($pegawai as $value): ?>
                  <option value="<?php echo $value->uuid; ?>"><?php echo $value->nama_pegawai ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Jenis Perizinan:</label>
              <select name="jenis_perizinan" class="form-control select2 col-md-12" required>
                <?php foreach ($jenis as $value): ?>
                  <option value="<?php echo $value->idjenis_perizinan; ?>"><?php echo $value->jenis_izin ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Alasan Izin :</label>
              <input type="text" name="alasan" class="form-control" required>
            </div>
          </div>
          <div class="col-md-12">
            <label>Tanggal Izin :</label>
            <div class="input-daterange input-group" id="date-range">
              <input type="text" class="form-control inputnone" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo date("d-m-Y") ?>"/>
              <div class="input-group-append">
                <span class="input-group-text bg-info b-0 text-white">S/D</span>
              </div>
              <input type="text" class="form-control inputnone" name="tanggal_akhir" id="tanggal_akhir" value="<?php echo date("d-m-Y") ?>"/>
            </div>
            <br>
          </div>
          <div class="col-md-12">
              <div class="form-group">
                  <label>File Pendukung :</label>
                  <input type="file" id="file" class="dropify" name="file"/>
              </div>
          </div>
        </div>
        <div class="form-actions" >
          <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
          <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
