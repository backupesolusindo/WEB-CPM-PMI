<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <!-- ini Judul -->
        <h3 class="white-text mx-3">Form Input Jadwal Masuk</h3>
        <div>

        </div>
      </div>
            <div class="card-body">
             <?php echo form_open_multipart('JadwalMasuk/insert');?>
             <input type="hidden" name="idjadwal_masuk" value="<?php echo $this->uri->segment(3) ?>">

             <?php $this->load->view($form)?>
             <?php echo form_close(); ?>
            </div>
     </div>
  </div>
</div>
