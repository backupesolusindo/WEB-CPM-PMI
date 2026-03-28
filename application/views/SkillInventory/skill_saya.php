<div class="row">

  <!-- Form Tambah Skill Sendiri (Karyawan) -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header blue-gradient white-text">
        Tambah Skill Saya
      </div>
      <div class="card-body">
        <form action="<?php echo base_url(); ?>SkillInventory/simpan_skill_saya" method="post">

          <div class="form-group">
            <label>Skill <span class="text-danger">*</span></label>
            <select name="id_skill" class="form-control" required>
              <option value="">-- Pilih Skill --</option>
              <?php foreach ($skill as $s) { ?>
              <option value="<?php echo $s->id_skill; ?>">
                <?php echo $s->nama_skill; ?><?php echo $s->kategori ? ' (' . $s->kategori . ')' : ''; ?>
              </option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label>Tahun Mulai</label>
            <input type="number" name="tahun_mulai" class="form-control"
                   placeholder="<?php echo date('Y'); ?>" min="1990" max="<?php echo date('Y'); ?>">
          </div>

          <div class="form-group">
            <label>Sertifikasi</label>
            <input type="text" name="sertifikasi" class="form-control" placeholder="Nama sertifikasi jika ada">
          </div>

          <button type="submit" class="btn btn-success btn-block">
            <i class="fas fa-save"></i> Tambahkan Skill
          </button>

        </form>
      </div>
    </div>
  </div>

  <!-- Tabel Skill Milik Karyawan -->
  <div class="col-md-8">
    <div class="card card-cascade narrower z-depth-1">

      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <h3 class="white-text mx-3">SKILL SAYA</h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="myTable">
            <thead class="blue lighten-4">
              <tr>
                <th>No</th>
                <th>Nama Skill</th>
                <th>Kategori</th>
                <th>Tahun Mulai</th>
                <th>Sertifikasi</th>
                <th width="80">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($data as $row) { ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row->nama_skill; ?></td>
                <td><?php echo $row->kategori ? $row->kategori : '<span class="text-muted">-</span>'; ?></td>
                <td><?php echo $row->tahun_mulai ? $row->tahun_mulai : '-'; ?></td>
                <td><?php echo $row->sertifikasi ? $row->sertifikasi : '<span class="text-muted">-</span>'; ?></td>
                <td>
                  <a href="<?php echo base_url(); ?>SkillInventory/hapus_skill_saya/<?php echo $row->id_skill_karyawan; ?>"
                     onclick="return confirm('Yakin hapus skill ini?')"
                     class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

</div>