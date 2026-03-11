
<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">

            <a href="" class="white-text mx-3">Jabatan Roles</a>
            <div>
              <a href="<?php base_url(); ?>Jabatan/input" class="float-right">
                  <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
              </a>
            </div>
      </div>
      <div class="card-body">

        <div class="table-responsive">
          <table id="myTable" class="table table-bordered table-hover table-striped">
                  <thead>
                      <tr>
                        <th width="5%"></th>
                        <th>Kode Jabatan</th>
                          <th>Jabatan</th>
                          <th>Roles</th>
                          <th width="%5">opsi</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1; foreach ($jabatan as $value):
                      $id_check = $value->idjabatan;?>
                      <tr>
                          <td><?php echo $no;?></td>
                          <td><?php echo $id_check; ?></td>
                          <td><?php echo $value->namajabatan; ?></td>
                          <td><?php echo $value->j_roles; ?></td>
                          <td>
                            <a href="<?php echo base_url().'Jabatan/edit/'.$id_check; ?>">
                            <button type="button" class="btn-sm btn-outline-warning" data-toggle="tooltip" data-placement="right" title="" data-original-title="Edit">
                              <i class="fa fa-edit"></i>
                            </button>
                            </a>
                            <a href="<?php echo base_url().'Jabatan/hapus/'.$id_check; ?>">
                            <button type="button" class="btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="right" title="" data-original-title="Hapus">
                              <i class="fa fa-trash"></i>
                            </button>
                            </a>
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
