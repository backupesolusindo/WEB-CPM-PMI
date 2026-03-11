
<?php echo form_open('user/delete');?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header text-center">
        <h4>LIST USER</h4>
      </div>
      <div class="card-body">
        <a href="<?php base_url(); ?>User/input" class="float-right">
          <h4><span class="badge badge-pill badge-success badge-atas">Tambah </span></h4>
          <button class="btn btn-circle btn-lg btn-success btn-atas" type="button"><i class="fa fa-plus"></i>
          </button>
        </a>
        <!-- hapus -->
          <h4 class="badge-hapus"><span class="badge badge-pill badge-danger">Terpilih <span id="jumlah_pilih">0</span></span></h4>

        <div id="alert"><?php echo $this->core->Hapus_disable(); ?></div>
        <div id="modal"><?php echo $this->core->Hapus_aktif(); ?></div>
        <div class="table-responsive">
          <table id="myTable" class="table color-table info-table tab ">
              <thead>
                  <tr>
                      <th width="10%">#</th>
                      <th>NIK</th>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>Jabatan</th>
                      <th>Admin Unit</th>
                      <th width="%5">opsi</th>
                  </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($user as $data):
                  $id_check = $data->id_user;?>
                  <tr>

                      <td><div class="checkbox">
                          <label for="<?php echo $id_check ?>" class="form-check-label ">
                            <input type="checkbox" id="<?php echo $id_check ?>" name="id[] " value="<?php echo $id_check ?>" class="form-check-input id_checkbox"><?php echo $no;?>
                          </label>
                        </div></td>
                      <td><?php echo $data->pegawai_NIK; ?></td>
                      <td><?php echo $data->nama; ?></td>
                      <td><?php echo $data->username; ?></td>
                      <td><?php echo $data->jabatan; ?></td>
                      <td><?php echo $data->unit_user; ?></td>
                      <td>
                        <a href="<?php echo base_url().'User/edit/'.$id_check; ?>">
                          <button type="button" class="btn-sm btn-outline-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                            <i class="fa fa-edit"></i>
                          </button>
                        </a>
                        <?php if ($id_check > 0): ?>
                          <a href="<?php echo base_url().'User/hapus/'.$id_check; ?>">
                            <button type="button" class="btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Hapus">
                              <i class="fa fa-trash"></i>
                            </button>
                          </a>
                        <?php endif; ?>
                      </td>
                  </tr>
                <?php $no++;  endforeach; ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>
<?php echo $this->core->Fungsi_JS_Hapus(); ?>
