<div class="row">
  <div class="col-md-8">
    <div class="card">

      <div class="card-header blue-gradient white-text">
        Edit Personal Development Plan
      </div>

      <div class="card-body">
        <form action="<?php echo base_url(); ?>pdp/update" method="post" enctype="multipart/form-data">

          <input type="hidden" name="id_pdp" value="<?php echo $pdp->id_pdp; ?>">

          <div class="form-group">
            <label>Judul Pelatihan <span class="text-danger">*</span></label>
            <input type="text" name="judul_pelatihan" class="form-control"
                   value="<?php echo $pdp->judul_pelatihan; ?>" required>
          </div>

          <div class="form-group">
            <label>Jenis Kegiatan <span class="text-danger">*</span></label>
            <select name="jenis_kegiatan" class="form-control" required>
              <option value="">-- Pilih Jenis Kegiatan --</option>
              <option value="Training"    <?php if ($pdp->jenis_kegiatan == "Training")    echo "selected"; ?>>Training</option>
              <option value="Workshop"    <?php if ($pdp->jenis_kegiatan == "Workshop")    echo "selected"; ?>>Workshop</option>
              <option value="Sertifikasi" <?php if ($pdp->jenis_kegiatan == "Sertifikasi") echo "selected"; ?>>Sertifikasi</option>
            </select>
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?php echo $pdp->deskripsi; ?></textarea>
          </div>

          <div class="form-group">
            <label>Tanggal Mulai <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_mulai" class="form-control"
                   value="<?php echo $pdp->tanggal_mulai; ?>" required>
          </div>

          <div class="form-group">
            <label>Tanggal Selesai <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_selesai" class="form-control"
                   value="<?php echo $pdp->tanggal_selesai; ?>" required>
          </div>

          <div class="form-group">
            <label>Keterkaitan KPI</label>
            <input type="text" name="keterkaitan_kpi" class="form-control"
                   value="<?php echo $pdp->keterkaitan_kpi; ?>" placeholder="Contoh: KPI Q1 2026">
          </div>

          <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="pending"  <?php if ($pdp->status == "pending")  echo "selected"; ?>>Pending</option>
              <option value="approved" <?php if ($pdp->status == "approved") echo "selected"; ?>>Approved</option>
              <option value="rejected" <?php if ($pdp->status == "rejected") echo "selected"; ?>>Rejected</option>
            </select>
          </div>

          <div class="form-group">
            <label>Bukti Dokumen</label>
            <?php if (!empty($pdp->bukti_dokumen)): ?>
              <div class="mb-2">
                <span class="text-muted">File saat ini: </span>
                <a href="<?php echo base_url('uploads/pdp/' . $pdp->bukti_dokumen); ?>" target="_blank">
                  <i class="fas fa-file"></i> <?php echo $pdp->bukti_dokumen; ?>
                </a>
              </div>
            <?php endif; ?>
            <input type="file" name="bukti_dokumen" class="form-control-file">
            <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB. Kosongkan jika tidak ingin mengubah file.</small>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Update
          </button>
          <button type="button" onclick="window.history.back()" class="btn btn-default">
            <i class="fa fa-mail-reply"></i> Kembali
          </button>

        </form>
      </div>

    </div>
  </div>
</div>