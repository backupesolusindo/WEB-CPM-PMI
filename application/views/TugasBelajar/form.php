
<br>
<div class="row">
  <div class="col-md-12">
    <label>Pegawai :</label>
    <select id="pegawai_uuid" name="pegawai_uuid" class="form-control select2 col-md-12" required onchange="sub_unit()">
      <?php foreach ($pegawai as $value): ?>
        <option value="<?php echo $value->uuid; ?>"><?php echo $value->NIP." | ".$value->nama_pegawai ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-6">
    <br>
    <div class="form-group">
      <label>Nama Kampus:</label>
      <input type="text" name="nama_kampus" class="form-control" value="<?php echo @$data["nama_kampus"] ?>" required>
    </div>
  </div>
  <div class="col-md-6">
    <br>
    <div class="form-group">
      <label>Keterangan :</label>
      <input type="text" name="keterangan" class="form-control" value="<?php echo @$data["keterangan"] ?>" required>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Tahun Mulai Tugas Belajar :</label>
      <input type="text" name="tahun" class="form-control angkasaja" value="<?php echo @$data["tahun"] ?>" required>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Tahun Selesai Tugas Belajar :</label>
      <input type="text" name="tahun_selesai" class="form-control angkasaja" value="<?php echo @$data["tahun_selesai"] ?>" required>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <label>Status :</label>
      <select id="status" name="status" class="form-control select2 col-md-12" required>
        <option value="1">Aktif Tugas Belajar</option>
        <option value="2">Selesai Tugas Belajar</option>
      </select>
    </div>
  </div>

</div>
<div class="form-actions" >
    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
    <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
</div>
