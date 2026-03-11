<div class="row">
  <div class="col-12">
    <div class="card card-cascade narrower z-depth-1">
      <div class="view view-cascade gradient-card-header blue-gradient narrower py-2 mx-4 mb-3 d-flex justify-content-between align-items-center">
        <div>
          <!-- <a href="<?php base_url(); ?>Pegawai/sinkron" class="float-right">
            <button type="button" class="btn btn-outline-white btn-rounded " data-toggle="tooltip" data-placement="top" data-original-title="Sinkronisasi Data Pegawai"><i class="fas fa-cloud-download-alt"></i> Sinkronisasi Pegawai</button>
          </a> -->
        </div>
        <h3 class="white-text mx-3">Pegawai</h3>
        <div>
          <a href="<?php base_url(); ?>Pegawai/input" class="float-right">
            <button type="button" class="btn btn-sm btn-outline-white btn-rounded " data-toggle="tooltip" data-placement="top" data-original-title="Tambah Pegawai"><i class="fas fa-plus"></i> Tambah</button>
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table id="table-print" class="table color-table table-hover table-striped ">
                    <thead>
                      <tr>
                        <th width="10%">#</th>
                        <th>NIP</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>E-Mail</th>
                        <th>Unit</th>
                        <th>Jenis Unit</th>
                        <th>Struktur Jabatan</th>
                        <th>Tipe Pegawai</th>
                        <th>Status</th>
                        <th>Reset</th>
                        <th>Opsi</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      $no = 1;
                      foreach ($Pegawai as $value): ?>
                        <tr>
                          <td><?php echo $no ?></td>
                          <td><?php echo $value->NIP ?>'</td>
                          <td><?php echo $value->NIK ?>'</td>
                          <td><?php echo $value->nama_pegawai ?></td>
                          <td><?php echo $value->email ?></td>
                          <td><?php echo $value->unit ?></td>
                          <td><?php echo $value->jenis_unit ?></td>
                          <td><?php echo $value->jab_struktur ?></td>
                          <td><?php echo $value->tipe_pegawai ?></td>
                          <td><?php echo $retVal = ($value->status_login == 1) ? '<span class="badge bg-danger">Sudah Login</span>' : '<span class="badge bg-primary">Belum Login</span>'; ?></td>
                          <td>
                            <a href="<?php echo base_url() ?>Pegawai/reset_login/<?php echo $value->uuid; ?>" class="btn-floating btn-sm btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="RESET LOGIN"><i class="fas fa-sync-alt"></i></a>
                            <a href="<?php echo base_url() ?>Pegawai/reset_password/<?php echo $value->uuid; ?>" class="btn-floating btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" data-original-title="RESET PASSWORD"><i class="fas fa-key"></i></a>
                            <a href="<?php echo base_url() ?>Laporan/RealtimeLocatioan/<?php echo $value->uuid; ?>" class="btn-floating btn-sm btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Realtime Location"><i class="fas fa-location-arrow"></i></a>
                          </td>
                          <td>
                            <a href="<?php echo base_url() ?>Pegawai/edit/<?php echo $value->uuid; ?>" class="btn-floating btn-sm btn-warning" data-toggle="tooltip" data-placement="top" data-original-title="EDIT"><i class="fas fa-pen"></i></a>
                            <a href="<?php echo base_url() ?>Pegawai/hapus/<?php echo $value->uuid; ?>" class="btn-floating btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fas fa-trash"></i></a>
                          </td>
                        </tr>
                      <?php $no++;
                      endforeach; ?>
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
  $(document).ready(function() {
    $('#table-print').DataTable({
      dom: 'Bfrtip',
      buttons: ['excel'],
    });
  });
</script>