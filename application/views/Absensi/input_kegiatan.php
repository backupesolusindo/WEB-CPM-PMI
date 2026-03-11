<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Input Presensi Lembur</h3>
        <div>

        </div>
      </div>
            <div class="card-body">
             <?php echo form_open_multipart('Absensi/insert_kegiatan');?>
             <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                   <label>ID Kegiatan :</label>
                   <input type="text" class="form-control" name="idkegiatan" id="idkegiatan" value="<?php echo $idkegiatan ?>" readonly/>
                 </div>
               </div>
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Latitude :</label>
                   <input type="text" class="form-control" name="lat" id="latitude" value="<?php echo $kegiatan['latitude'] ?>" readonly/>
                 </div>
               </div>
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Longtitude :</label>
                   <input type="text" class="form-control" name="long" id="longitude" value="<?php echo $kegiatan['longtitude'] ?>" readonly/>
                 </div>
               </div>
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Nama Pegawai :</label>
                   <select name="uuid" class="form-control select2 col-md-12" required>
                     <?php foreach ($pegawai as $value): ?>
                       <option value="<?php echo $value->uuid; ?>"><?php echo $value->NIP." - ".$value->nama_pegawai ?></option>
                     <?php endforeach; ?>
                   </select>
                 </div>
               </div>
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Tanggal :</label>
                   <input type="text" class="form-control inputnone mydatepicker" name="tanggal" id="tanggal" value="<?php echo date("d-m-Y") ?>"/>
                 </div>
                 <br>
               </div>
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Jam Presensi :</label>
                   <input type="text" name="jam_mulai" class="form-control waktu-input inputnone" value="<?php echo date("H:i:s") ?>">
                 </div>
               </div>
               <div class="col-md-12">
                 <h4 class="card-title">Upload Foto Presensi</h4>
                 <label for="input-file-now">Foto Presensi Kegiatan</label>
                 <input type="file" id="foto" name="foto" class="dropify" />
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
