<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> <i class="fa fa-address-card"></i> FORM MENAMBAHKAN PEGAWAI</h4>
                <br><br>
                  <?php echo form_open_multipart('Pegawai/update', array('class' => "floating-labels"));?>
                  <input type="hidden" name="id" value="<?php echo $pegawai['uuid'] ?>">
                  <?php echo @$error;?>
                  <?php $this->load->view('Pegawai/form')?>
                  <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
