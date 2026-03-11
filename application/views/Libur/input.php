<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Input Libur Nasional</h3>
        <div>

        </div>
      </div>
            <div class="card-body">
             <?php echo form_open_multipart('Libur/insert');?>
             <input type="hidden" name="idjenis_perizinan" value="<?php echo $this->uri->segment(3) ?>">
             <br>
             <div class="row">
               <div class="col-md-12">
                   <div class="form-group">
                       <label>Keterangan :</label>
                       <input type="text" name="keterangan" id="keterangan" class="form-control">
                   </div>
               </div>
               <div class="col-md-12">
                   <div class="form-group">
                       <label>Tanggal :</label>
                       <input type="text" class="form-control inputnone mydatepicker" name="tanggal" id="tanggal" value="<?php echo date("d-m-Y") ?>">
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
