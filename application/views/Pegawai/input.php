<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> <i class="fa fa-address-card"></i> FORM MENAMBAHKAN PEGAWAI</h4>
                  <?php echo form_open_multipart('Pegawai/insert', array('class' => "floating-labels"));?>
                  <?php echo @$error;?>
                  <?php $this->load->view('Pegawai/form')?>
                  <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
