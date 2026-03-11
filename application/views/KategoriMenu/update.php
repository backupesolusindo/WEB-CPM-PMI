<div class="row">
    <div class="col-12">
        <div class="card card-cascade narrower z-depth-1">
          <div class="view view-cascade gradient-card-header sunny-morning-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
            <div>
            </div>
            <h3 class="white-text mx-3">FORM EDIT KATEGORI MENU</h3>
            <div>
            </div>
          </div>
                  <?php echo form_open_multipart('KategoriMenu/update', array('class' => "floating-labels"));?>
                  <?php echo @$error;?>

                  <div class="col-md-12 card-body row">
                      <div class="col-md-6">
                        <div class="form-group ">
                            <input type="text" class="form-control angkasaja" id="idkategori_menu" name="idkategori_menu" required value="<?php echo @$kategori_menu['idkategori_menu']; ?>">
                            <span class="bar"></span>
                            <label for="idkategori_menu"><i class="fas fa-angle-down green-text"></i> ID Kategori Menu :</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group ">
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required value="<?php echo @$kategori_menu['nama_kategori']; ?>">
                            <span class="bar"></span>
                            <label for="nama_kategori"><i class="fas fa-cart-plus green-text"></i> Nama Kategori Menu :</label>
                        </div>
                      </div>
                      <br><br>
                      <div class="col-md-12">
                        <a href="<?php echo base_url ('KategoriMenu') ?>">
                          <button type="button" name="kembali" class="btn btn-default btn-sm"> <i class="fa fa-mail-reply"></i> Kembali</button>
                        </a>
                        <button type="submit" name="submit" class="btn btn-success pull-right btn-rounded"> <i class="fa fa-save"></i> Simpan</button>
                      </div>
                  </div>

                  <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
