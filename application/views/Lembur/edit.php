
<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Edit Lembur</h3>
        <div>

        </div>
      </div>
      <div class="card-body">
        <?php echo form_open_multipart('Lembur/update');?>
        <input type="hidden" name="idlembur" value="<?php echo @$data["idlembur"] ?>">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Keterangan Lembur :</label>
              <input type="text" name="keterangan_lembur" class="form-control" value="<?php echo @$data["keterangan_lembur"] ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <label>Tanggal Lembur:</label>
            <div class="input-daterange input-group" id="date-range">
              <input type="text" class="form-control inputnone" name="tgl_mulai" id="tgl_mulai" value="<?php echo date("d-m-Y") ?>"/>
              <div class="input-group-append">
                <span class="input-group-text bg-info b-0 text-white">S/D</span>
              </div>
              <input type="text" class="form-control inputnone" name="tgl_selesai" id="tgl_selesai" value="<?php echo date("d-m-Y") ?>"/>
            </div>
            <br>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Unit :</label>
              <select name="unit" class="form-control select2 col-md-12" required>
                <?php foreach ($unit as $value): ?>
                  <option value="<?php echo $value->idunit; ?>" <?php if ($value->idunit == @$data['unit_idunit']): ?>
                    <?php echo 'selected'; ?>
                  <?php endif; ?>><?php echo $value->nama_unit ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <!-- <div class="col-md-6">
            <div class="form-group">
              <label>Penanggung Jawab (PIC) :</label>
              <select name="pic" class="form-control select2 col-md-12" required>
                <?php foreach ($pegawai as $value): ?>
                  <option value="<?php echo $value->uuid; ?>" <?php if ($value->uuid == @$data['uuid_pic']): ?>
                    <?php echo 'selected'; ?>
                  <?php endif; ?>><?php echo $value->nama_pegawai ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div> -->
          <div class="form-actions" >
            <button type="submit" class="btn btn-success" id="simpan"> <i class="fa fa-check"></i> Simpan</button>
            <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
          </div>
        </div>

        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>
