<div class="row">
  <div class="col-md-8">
    <div class="card">

      <div class="card-header blue-gradient white-text">
        Edit Master Skill
      </div>

      <div class="card-body">
        <form action="<?php echo base_url(); ?>SkillInventory/update" method="post">

          <input type="hidden" name="id_skill" value="<?php echo $skill->id_skill; ?>">

          <div class="form-group">
            <label>Nama Skill <span class="text-danger">*</span></label>
            <input type="text" name="nama_skill" class="form-control" value="<?php echo $skill->nama_skill; ?>" required>
          </div>

          <div class="form-group">
            <label>Kategori</label>
            <input type="text" name="kategori" class="form-control" value="<?php echo $skill->kategori; ?>" placeholder="Contoh: IT, Manajemen, Teknis">
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?php echo $skill->deskripsi; ?></textarea>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Update
          </button>
          <a href="<?php echo base_url(); ?>SkillInventory" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>

        </form>
      </div>

    </div>
  </div>
</div>