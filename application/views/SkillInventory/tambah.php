<div class="row">
  <div class="col-md-8">
    <div class="card">

      <div class="card-header blue-gradient white-text">
        Tambah Master Skill
      </div>

      <div class="card-body">
        <form action="<?php echo base_url(); ?>SkillInventory/simpan" method="post">

          <div class="form-group">
            <label>Nama Skill <span class="text-danger">*</span></label>
            <input type="text" name="nama_skill" class="form-control" placeholder="Contoh: Microsoft Excel" required>
          </div>

          <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" placeholder="Contoh: IT, Manajemen, Teknis">
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Keterangan singkat tentang skill ini"></textarea>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Simpan
          </button>
          <a href="<?php echo base_url(); ?>SkillInventory" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>

        </form>
      </div>

    </div>
  </div>
</div>