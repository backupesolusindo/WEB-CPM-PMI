<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Input Jadwal Lokasi Kantor</h3>
        <div>

        </div>
      </div>
            <div class="card-body">
             <?php echo form_open_multipart('JadwalLokasi/insert');?>
               <div class="row">
                 <div class="col-md-6">
                     <div class="form-group">
                         <label>Nama Unit :</label>
                         <select name="idkampus" class="form-control select2 col-md-12" required>
                           <option value="" selected disabled> Pilih Unit</option>
                           <?php foreach ($unit as $value): ?>
                             <option value="<?php echo $value->idkampus; ?>"><?php echo $value->nama_kampus ?></option>
                           <?php endforeach; ?>
                         </select>
                     </div>
                 </div>
                 <div class="col-md-6">
                     <div class="form-group">
                         <label>Nama Pegawai :</label>
                         <select name="uuid" class="form-control select2 col-md-12" required>
                           <option value="" selected disabled> Pilih Pegawai</option>
                           <?php foreach ($pegawai as $value): ?>
                             <option value="<?php echo $value->uuid; ?>" <?php if ($value->uuid == @$kegiatan['uuid_pic']): ?>
                               <?php echo 'selected'; ?>
                             <?php endif; ?>><?php echo $value->nama_pegawai?></option>
                           <?php endforeach; ?>
                         </select>
                     </div>
                 </div>
                 <div class="col-md-12 row">
                   <div class="col-2">
                     <label>Jadwal Hari : </label>
                   </div>
                   <div class="col-3">
                     <div class="custom-control custom-checkbox">
                       <input type="checkbox" class="custom-control-input" name="hari[]" id="Mon" value="Mon">
                       <label class="custom-control-label" for="Mon">Senin</label>
                     </div>
                     <div class="custom-control custom-checkbox">
                       <input type="checkbox" class="custom-control-input" name="hari[]" id="Tue" value="Tue">
                       <label class="custom-control-label" for="Tue">Selasa</label>
                     </div>
                     <div class="custom-control custom-checkbox">
                       <input type="checkbox" class="custom-control-input" name="hari[]" id="Wed" value="Wed">
                       <label class="custom-control-label" for="Wed">Rabu</label>
                     </div>
                     <div class="custom-control custom-checkbox">
                       <input type="checkbox" class="custom-control-input" name="hari[]" id="Thu" value="Thu">
                       <label class="custom-control-label" for="Thu">Kamis</label>
                     </div>
                     <div class="custom-control custom-checkbox">
                       <input type="checkbox" class="custom-control-input" name="hari[]" id="Fri" value="Fri">
                       <label class="custom-control-label" for="Fri">Jum'at</label>
                     </div>
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
