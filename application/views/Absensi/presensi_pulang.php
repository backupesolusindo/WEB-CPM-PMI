<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Presensi Pulang</h3>
        <div>

        </div>
      </div>
            <div class="card-body">
             <?php echo form_open_multipart('Absensi/insert_presensi_pulang');?>
             <input type="hidden" name="idabsensi" value="<?php echo $this->uri->segment(3) ?>">
             <input type="hidden" name="uuid" value="<?php echo $absensi['pegawai_uuid'] ?>">
             <div class="row">
               <div class="col-md-12">
                 <hr>
                 <div class="form-group">
                   <label>Nama Pegawai :</label><br>
                   <b><?php echo $absensi['NIP']." - ".$absensi['nama_pegawai'] ?></b>
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
                   <label>Jam Pulang :</label>
                   <input type="text" name="jam_pulang" class="form-control waktu-input inputnone" value="<?php echo date("H:i:s") ?>">
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
