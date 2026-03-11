<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>

        </div>
        <h3 class="white-text mx-3">Pegawai Akses Monitoring</h3>
        <div>
          <a href="<?php base_url(); ?>Monitoring/input" class="float-right">
          <button type="button" class="btn btn-outline-white btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Tambah Data Baru"><i class="fas fa-pencil-alt mt-0"></i></button>
        </a>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <a href="<?php base_url(); ?>Monitoring/hirarki" class="float-right">
            <button type="button" class="btn btn-primary btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Data Hirarki"><i class="fas fa-pencil-alt mt-0"></i>Hirarki Pegawai</button>
          </a>
          <a href="<?php base_url(); ?>Monitoring/excel" class="float-right">
            <button type="button" class="btn btn-primary btn-rounded btn-sm px-2" data-toggle="tooltip" data-placement="top" data-original-title="Data Monitoring"><i class="fas fa-pencil-alt mt-0"></i>Data Monitoring</button>
          </a>
                  <div class="col-lg-12">
                      <div class="card">
                          <div class="card-body">
                            <div class="table-responsive">
                            <table id="tabel_excel" class="table color-table table-hover table-striped ">
                              <thead>
                                  <tr>
                                      <th width="10%">#</th>
                                      <th>NIP</th>
                                      <th>Nama Lengkap</th>
                                      <th>E-Mail</th>
                                      <th>Kepala Unit</th>
                                      <th>Opsi</th>
                                  </tr>
                              </thead>
                              <tbody>
                                <?php
                                $no = 1;
                                foreach ($Pegawai as $value): ?>
                                  <tr>
                                    <td><?php echo $no ?></td>
                                    <td>'<?php echo $value->NIP ?></td>
                                    <td><?php echo $value->nama_pegawai ?></td>
                                    <td><?php echo $value->email ?></td>
                                    <td><?php echo $value->nama_unit ?></td>
                                    <td>
                                      <a href="<?php echo base_url()?>Monitoring/list_pegawai/<?php echo $value->uuid;?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Data Pegawai"><i class="fas fa-users"></i></a>
                                      <a href="<?php echo base_url()?>Monitoring/hapus/<?php echo $value->idkepala_unit;?>" class="btn-floating btn-sm btn-danger" data-toggle="tooltip" data-placement="top" data-original-title="Hapus Kepala Unit"><i class="fas fa-trash"></i></a>
                                    </td>
                                  </tr>
                                  <?php $no++; endforeach; ?>
                                      </tbody>

                                  </table>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>

      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  $('#tabel_excel').DataTable({
    dom: 'Bfrtip',
    buttons: ['excel'],
  });
});
</script>
