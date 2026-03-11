
<br>
<div class="row">
  <div class="col-md-6">
    <hr>
    <div class="form-group">
      <label>No.Surat :</label>
      <input type="text" name="no_surat" class="form-control spasinone" value="<?php echo @$dinasluar["no_surat"] ?>" required>
    </div>
  </div>
  <div class="col-md-6">
    <hr>
    <div class="form-group">
      <label>Kegiatan :</label>
      <input type="text" name="nama_surat" class="form-control" value="<?php echo @$dinasluar["nama_surat"] ?>" required>
    </div>
  </div>
  <div class="col-md-6">
    <hr>
    <div class="form-group">
      <label>Lokasi :</label>
      <input type="text" name="keterangan" class="form-control" value="<?php echo @$dinasluar["keterangan"] ?>" required>
    </div>
  </div>
  <div class="col-md-12">
    <label>Tanggal Dinas Luar:</label>
    <div class="input-daterange input-group" id="date-range">
      <input type="text" class="form-control inputnone" name="tanggal_mulai" id="tanggal_mulai" value="<?php echo date("d-m-Y") ?>"/>
      <div class="input-group-append">
        <span class="input-group-text bg-info b-0 text-white">S/D</span>
      </div>
      <input type="text" class="form-control inputnone" name="tanggal_selesai" id="tanggal_selesai" value="<?php echo date("d-m-Y") ?>"/>
    </div>
    <br>
  </div>
</div>
<div class="form-actions" >
    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
    <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
</div>
