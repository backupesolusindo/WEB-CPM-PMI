<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Input Kepala unit</h3>
        <div>

        </div>
      </div>
            <div class="card-body">
             <?php echo form_open_multipart('Monitoring/insert_atasan');?>
               <div class="row">
                 <div class="col-md-12">
                     <div class="form-group">
                         <label>Jabatan Atasan :</label>
                         <select name="jab_atasan" class="form-control select2 col-md-12" required>
                           <option value="pegawai">Pegawai / Kepala Sub Unit / Kepala Unit</option>
                           <option value="wadir">Wakil Direktur</option>
                           <option value="direktur">Direktur</option>
                         </select>
                     </div>
                 </div>
                 <div class="col-md-12">
                     <div class="form-group">
                         <label>Kepala Unit :</label>
                         <select name="uuid" class="form-control select2 col-md-12" required>
                           <?php foreach ($pegawai as $value): ?>
                             <option value="<?php echo $value->uuid; ?>" <?php if ($value->uuid == @$kegiatan['uuid_pic']): ?>
                               <?php echo 'selected'; ?>
                             <?php endif; ?>><?php echo $value->nama_pegawai." | ".$value->jab_struktur." - ".$value->unit ?></option>
                           <?php endforeach; ?>
                         </select>
                     </div>
                 </div>
               </div>
               <div class="form-actions" >
                   <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Simpan</button>
                   <button type="button" class="btn btn-light waves-effect btn-sm kembali" data-dismiss="modal">Kembali</button>
               </div>

             <?php echo form_close(); ?>
            </div>
     </div>
  </div>
</div>
