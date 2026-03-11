
<?php echo form_open('Roles/hapus');?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header text-center">
        <h4>LIST USER</h4>
      </div>
      <div class="card-body">
        <a href="<?php base_url(); ?>Roles/input" class="float-right">
          <h4><span class="badge badge-pill badge-success badge-atas">Tambah </span></h4>
          <button class="btn btn-circle btn-lg btn-success btn-atas" type="button"><i class="fa fa-plus"></i>
          </button>
        </a>
        <!-- hapus -->
          <h4 class="badge-hapus"><span class="badge badge-pill badge-danger">Terpilih <span id="jumlah_pilih">0</span></span></h4>

        <div id="alert"><?php echo $this->core->Hapus_disable(); ?></div>
        <div id="modal"><?php echo $this->core->Hapus_aktif(); ?></div>
        <div class="table-responsive">
          <table id="example_group" class="table color-table info-table tab ">
              <thead>
                  <tr>
                      <th width="15%" class="text-right">#</th>
                      <th>Roles</th>
                      <th>Group Roles</th>
                  </tr>
              </thead>
              <tbody>
                <?php $no = 1; foreach ($roles as $data):
                  $id_check = $data->roles;?>
                  <tr>
                      <td class="text-right"><div class="checkbox">
                          <label for="<?php echo $id_check ?>" class="form-check-label ">
                            <input type="checkbox" id="<?php echo $id_check ?>" name="id[] " value="<?php echo $id_check ?>" class="form-check-input id_checkbox"><?php echo $no;?>
                          </label>
                        </div></td>
                      <td><?php echo $data->roles; ?></td>
                      <td><?php echo $data->nama_group; ?></td>
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
