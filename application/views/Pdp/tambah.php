<div class="row">
  <div class="col-md-8">
    <div class="card">

      <div class="card-header blue-gradient white-text">
        Tambah Personal Development Plan
      </div>

      <div class="card-body">
        <form action="<?php echo base_url(); ?>pdp/simpan" method="post" enctype="multipart/form-data">

          <div class="form-group">
            <label>Judul Pelatihan <span class="text-danger">*</span></label>
            <input type="text" name="judul_pelatihan" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Jenis Kegiatan <span class="text-danger">*</span></label>
            <select name="jenis_kegiatan" class="form-control" required>
              <option value="">-- Pilih Jenis Kegiatan --</option>
              <option value="Training">Training</option>
              <option value="Workshop">Workshop</option>
              <option value="Sertifikasi">Sertifikasi</option>
            </select>
          </div>

          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
          </div>

          <div class="form-group">
            <label>Tanggal Mulai <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_mulai" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Tanggal Selesai <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_selesai" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Keterkaitan KPI</label>
            <input type="text" name="keterkaitan_kpi" class="form-control" placeholder="Contoh: KPI Q1 2026">
          </div>

          <div class="form-group">
            <label>Bukti Dokumen</label>
            <input type="file" name="bukti_dokumen" class="form-control-file">
            <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 5MB.</small>
          </div>

          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Simpan
          </button>
          <button type="button" onclick="window.history.back()" class="btn btn-default">
            <i class="fa fa-mail-reply"></i> Kembali
          </button>

        </form>
      </div>

    </div>
  </div>
</div>