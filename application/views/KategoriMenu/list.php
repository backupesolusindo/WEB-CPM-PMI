<?php echo form_open('KategoriMenu/delete');?>

<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header sunny-morning-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
        </div>
        <h3 class="white-text mx-3">LIST KATEGORI MENU</h3>
        <div>
          <a href="<?php base_url(); ?>KategoriMenu/input" class="float-right">
          <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
        </a>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
                  <div class="col-lg-12">
                                    <table class="table product-overview" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>ID Kategori Menu</th>
                                                <th>Nama Kategori Menu</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php foreach ($kategori_menu as $value): ?>
                                            <tr>
                                                <td><?php echo $value->idkategori_menu;?>
                                                </td>
                                                <td><?php echo $value->nama_kategori;?>
                                                </td>
                                                <td>
                                                  <a href="<?php echo base_url('KategoriMenu/edit/'.$value->idkategori_menu) ?>" class="btn-sm btn-outline-warning" data-toggle="tooltip" title="Edit"><i class="ti-marker-alt"></i></a>
                                                  <a href="<?php echo base_url('KategoriMenu/hapus/'.$value->idkategori_menu) ?>" class="btn-sm btn-outline-danger" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                          <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php echo form_close();?>
<?php echo $this->core->Fungsi_JS_Hapus(); ?>
