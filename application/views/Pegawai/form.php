<div class="row">
  <div class="col-md-6">
    <div class="form-group ">
      <input type="text" class="form-control" id="nip" name="nip" required value="<?php echo @$pegawai['NIP']; ?>">
      <span class="bar"></span>
      <label for="nip">NIP :</label>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group ">
      <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" required value="<?php echo @$pegawai['nama_pegawai']; ?>">
      <span class="bar"></span>
      <label for="nama_pegawai">Nama Lengkap</label>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group ">
      <input type="email" class="form-control" id="email" name="email" value="<?php echo @$pegawai['email'];  ?>">
      <span class="bar"></span>
      <label for="nama">E-mail</label>
    </div>
  </div>
  <div class="col-md-6">
    <div class="row form-group">
      <div class="col-md-3">
        <label>Jabatan :</label>
      </div>
      <div class="col-12 col-md-9">
        <label for="agama" class="form-control"> :</label>

        <select name="jabatan" id="select" class="form-control select2 col-md-12" required>
          <option>...Pilih Jabatan...</option>
          <?php foreach ($jabatan as $value) : ?>
            <option value="<?php echo $value->idjabatan; ?>" <?php if ($value->idjabatan == @$pegawai['jab_struktur']) : ?> <?php echo 'selected'; ?> <?php endif; ?>><?php echo $value->namajabatan ?></option>
          <?php endforeach; ?>
        </select>

      </div>
    </div>
    <div class="row form-group">
      <div class="col-md-3">
        <label>Jenis Unit :</label>
      </div>
      <div class="col-12 col-md-9">
        <select name="jenis_unit" id="jenis_unit" class="form-control select2 col-md-12" required>
          <option value="KANTOR" <?= $st = (@$pegawai['jenis_unit'] == "KANTOR") ? "selected" : ""; ?>>KANTOR</option>
          <option value="CABANG" <?= $st = (@$pegawai['jenis_unit'] == "CABANG") ? "selected" : ""; ?>>CABANG</option>
        </select>
      </div>
    </div>
    <div class="row form-group">
      <div class="col-md-3">
        <label>Unit :</label>
      </div>
      <div class="col-12 col-md-9">
        <select name="unit" id="select" class="form-control select2 col-md-12" required>
          <option>...Pilih Unit...</option>
          <?php foreach ($unit as $value) : ?>
            <option value="<?php echo $value->nama_unit; ?>" <?php if ($value->nama_unit == @$pegawai['unit']) : ?> <?php echo 'selected'; ?> <?php endif; ?>><?php echo $value->nama_unit ?></option>
          <?php endforeach; ?>
        </select>

      </div>
    </div>
  </div>
  <div class="col-md-12">
    <button type="button" name="kembali" class="btn btn-default btn-sm"> <i class="fa fa-mail-reply"></i> Kembali</button>
    <button type="submit" name="submit" class="btn btn-success pull-right"> <i class="fa fa-save"></i> Simpan</button>
  </div>
</div>