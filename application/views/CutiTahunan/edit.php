<div class="row">
  <div class="col-md-5">
    <div class="card">

      <div class="card-header blue-gradient white-text">
        Edit Cuti Tahunan
      </div>

      <div class="card-body">
        <form action="<?php echo base_url(); ?>CutiTahunan/update" method="post">

          <input type="hidden" name="idcuti_tahunan" value="<?php echo $cuti->idcuti_tahunan; ?>">

          <div class="form-group">
            <label>Pegawai</label>
            <input type="text" class="form-control" value="<?php echo $cuti->NIP; ?> - <?php echo $cuti->nama_pegawai; ?>" disabled>
          </div>

          <div class="form-group">
            <label>Total Cuti (Hari) <span class="text-danger">*</span></label>
            <input type="number" name="total_cuti" class="form-control" required
                   min="1" max="365" value="<?php echo $cuti->total_cuti; ?>">
          </div>

          <div class="form-group">
            <label>Tahun <span class="text-danger">*</span></label>
            <input type="number" name="tahun_cuti" class="form-control" required
                   min="2020" max="2099" value="<?php echo $cuti->tahun_cuti; ?>">
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