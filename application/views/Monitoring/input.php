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
             <?php echo form_open_multipart('Monitoring/insert');?>
               <div class="row">
                 <div class="col-md-12">
                     <div class="form-group">
                         <label>Nama Unit :</label>
                         <select name="idunit" class="form-control select2 col-md-12" required>
                           <?php foreach ($unit as $value): ?>
                             <option value="<?php echo $value->idunit; ?>" <?php if ($value->idunit == @$kegiatan['unit_idunit']): ?>
                               <?php echo 'selected'; ?>
                             <?php endif; ?>><?php echo $value->nama_unit ?></option>
                           <?php endforeach; ?>
                         </select>
                     </div>
                 </div>
                 <div class="col-md-12">
                     <div class="form-group">
                         <label>Memonitoring :</label>
                         <select name="monitor" class="form-control select2 col-md-12" required>
                           <!-- <option value="1">Direktur</option>
                           <option value="2">Wadir</option> -->
                           <option value="3">Unit</option>
                           <option value="4">Sub Unit</option>
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
                             <?php endif; ?>><?php echo $value->nama_pegawai?></option>
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
